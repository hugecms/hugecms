package attachment

import (
	"context"
	"fmt"
	"path/filepath"
	"strings"

	"github.com/gogf/gf/v2/crypto/gmd5"
	"github.com/gogf/gf/v2/errors/gerror"
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gfile"
	"github.com/gogf/gf/v2/os/gtime"

	"hugecms/internal/dao"
	"hugecms/internal/model"
	"hugecms/internal/model/entity"
	"hugecms/internal/service"
)

type sAttachment struct{}

func init() {
	service.RegisterAttachment(New())
}

func New() service.IAttachment {
	return &sAttachment{}
}

// Upload 上传附件并持久化记录到 attachments 表
func (s *sAttachment) Upload(ctx context.Context, in model.AttachmentUploadInput) (*model.AttachmentUploadOutput, error) {
	if in.File == nil {
		return nil, gerror.New("未检测到上传文件")
	}

	// 1. 组织存储相对路径与物理路径: resource/public/upload/YYYYMM/
	dateFolder := gtime.Now().Format("Ym")
	subPath := filepath.Join("upload", dateFolder)
	saveDir := filepath.Join("resource", "public", subPath)
	if !gfile.Exists(saveDir) {
		if err := gfile.Mkdir(saveDir); err != nil {
			return nil, fmt.Errorf("创建上传目录失败: %w", err)
		}
	}

	// 2. 生成安全文件名 (MD5+随机前缀保留扩展名)
	originalName := in.File.FileHeader.Filename
	ext := strings.ToLower(filepath.Ext(originalName))
	hashName, err := gmd5.EncryptString(fmt.Sprintf("%s_%d", originalName, gtime.TimestampNano()))
	if err != nil {
		hashName = fmt.Sprintf("%d", gtime.TimestampMicro())
	}
	saveFileName := hashName + ext
	in.File.Filename = saveFileName

	// 3. 执行文件保存
	savedName, err := in.File.Save(saveDir, false)
	if err != nil {
		return nil, fmt.Errorf("保存文件失败: %w", err)
	}

	// 相对访问路径与完整 URL (支持静态映射 /upload/*)
	relativePath := fmt.Sprintf("/upload/%s/%s", dateFolder, savedName)
	fileUrl := relativePath

	now := gtime.Now()
	// 4. 入库 attachments 表
	res, err := dao.Attachments.Ctx(ctx).Data(g.Map{
		"uploader_id":    in.UploaderId,
		"file_name":      originalName,
		"file_path":      relativePath,
		"storage_driver": "local",
		"cdn_url":        fileUrl,
		"file_size":      in.File.Size,
		"mime_type":      in.File.Header.Get("Content-Type"),
		"created_at":     now,
		"updated_at":     now,
	}).Insert()
	if err != nil {
		return nil, fmt.Errorf("写入附件记录失败: %w", err)
	}

	id, err := res.LastInsertId()
	if err != nil {
		return nil, err
	}

	return &model.AttachmentUploadOutput{
		Id:            id,
		FileName:      originalName,
		FilePath:      relativePath,
		FileUrl:       fileUrl,
		FileSize:      in.File.Size,
		MimeType:      in.File.Header.Get("Content-Type"),
		StorageDriver: "local",
	}, nil
}

// Delete 删除附件（含双路径引用跟踪校验保护）
func (s *sAttachment) Delete(ctx context.Context, id int64) error {
	// 1. 检查 attachment_relations 引用计数
	relCount, err := dao.AttachmentRelations.Ctx(ctx).Where("attachment_id", id).Count()
	if err != nil {
		return err
	}
	if relCount > 0 {
		return gerror.New("该附件已被内容图集关联引用，禁止删除")
	}

	// 2. 检查物理文件路径并删除
	var att entity.Attachments
	if err := dao.Attachments.Ctx(ctx).WherePri(id).Scan(&att); err != nil {
		return err
	}
	if att.Id == 0 {
		return gerror.New("附件不存在")
	}

	// 物理清理本地文件
	if att.StorageDriver == "local" && att.FilePath != "" {
		fullPath := filepath.Join("resource", "public", att.FilePath)
		_ = gfile.Remove(fullPath)
	}

	_, err = dao.Attachments.Ctx(ctx).WherePri(id).Delete()
	return err
}
