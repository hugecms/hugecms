package comment

import (
	"context"

	"github.com/gogf/gf/v2/database/gdb"
	"github.com/gogf/gf/v2/errors/gerror"
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gtime"

	"hugecms/internal/dao"
	"hugecms/internal/model"
	"hugecms/internal/model/entity"
	"hugecms/internal/service"
)

type sComment struct{}

func init() {
	service.RegisterComment(New())
}

func New() service.IComment {
	return &sComment{}
}

// Search 分页查询评论
func (s *sComment) Search(ctx context.Context, in model.CommentSearchInput) (*model.CommentSearchOutput, error) {
	if in.Page <= 0 {
		in.Page = 1
	}
	if in.PageSize <= 0 {
		in.PageSize = 10
	}

	m := dao.Comments.Ctx(ctx)
	if in.ContentId > 0 {
		m = m.Where("content_id", in.ContentId)
	}
	if in.Status != "" {
		m = m.Where("status", in.Status)
	}
	if in.Keyword != "" {
		m = m.Where("author_name LIKE ? OR content LIKE ?", "%"+in.Keyword+"%", "%"+in.Keyword+"%")
	}

	total, err := m.Count()
	if err != nil {
		return nil, err
	}

	var list []entity.Comments
	if err := m.Page(in.Page, in.PageSize).OrderDesc("id").Scan(&list); err != nil {
		return nil, err
	}

	// 提取关联内容标题
	contentIds := make([]uint64, 0, len(list))
	for _, c := range list {
		contentIds = append(contentIds, c.ContentId)
	}
	contentTitleMap := make(map[uint64]string)
	if len(contentIds) > 0 {
		type ContentTitle struct {
			Id    uint64 `json:"id"`
			Title string `json:"title"`
		}
		var titles []ContentTitle
		_ = dao.Contents.Ctx(ctx).Fields("id, title").WhereIn("id", contentIds).Scan(&titles)
		for _, t := range titles {
			contentTitleMap[t.Id] = t.Title
		}
	}

	items := make([]model.CommentItem, 0, len(list))
	for _, c := range list {
		items = append(items, model.CommentItem{
			Id:            int64(c.Id),
			ContentId:     int64(c.ContentId),
			ContentTitle:  contentTitleMap[c.ContentId],
			UserId:        int64(c.UserId),
			ParentId:      int64(c.ParentId),
			ReplyToUserId: int64(c.ReplyToUserId),
			AuthorName:    c.AuthorName,
			AuthorEmail:   c.AuthorEmail,
			AuthorUrl:     c.AuthorUrl,
			Content:       c.Content,
			Ip:            c.Ip,
			UserAgent:     c.UserAgent,
			Status:        c.Status,
			LikeCount:     int(c.LikeCount),
			CreatedAt:     c.CreatedAt,
			UpdatedAt:     c.UpdatedAt,
		})
	}

	return &model.CommentSearchOutput{
		List:  items,
		Total: total,
		Page:  in.Page,
		Size:  in.PageSize,
	}, nil
}

// GetTree 获取指定内容的已通过审核评论树（前台展示）
func (s *sComment) GetTree(ctx context.Context, contentId int64) ([]model.CommentTreeNode, error) {
	var list []entity.Comments
	err := dao.Comments.Ctx(ctx).
		Where("content_id", contentId).
		Where("status", "approved").
		OrderAsc("id").
		Scan(&list)
	if err != nil {
		return nil, err
	}

	return buildCommentTree(list, 0), nil
}

func buildCommentTree(items []entity.Comments, parentId uint64) []model.CommentTreeNode {
	var nodes []model.CommentTreeNode
	for _, item := range items {
		if item.ParentId == parentId {
			children := buildCommentTree(items, item.Id)
			nodes = append(nodes, model.CommentTreeNode{
				Id:            int64(item.Id),
				ContentId:     int64(item.ContentId),
				UserId:        int64(item.UserId),
				ParentId:      int64(item.ParentId),
				ReplyToUserId: int64(item.ReplyToUserId),
				AuthorName:    item.AuthorName,
				AuthorEmail:   item.AuthorEmail,
				AuthorUrl:     item.AuthorUrl,
				Content:       item.Content,
				Status:        item.Status,
				LikeCount:     int(item.LikeCount),
				Children:      children,
				CreatedAt:     item.CreatedAt,
			})
		}
	}
	return nodes
}

// Create 提交新评论
func (s *sComment) Create(ctx context.Context, in model.CommentCreateInput) (int64, error) {
	var content entity.Contents
	if err := dao.Contents.Ctx(ctx).WherePri(in.ContentId).Scan(&content); err != nil {
		return 0, err
	}
	if content.Id == 0 {
		return 0, gerror.New("关联内容不存在")
	}

	now := gtime.Now()
	// 默认状态为 pending（待审核）
	status := "pending"

	data := g.Map{
		"content_id":   in.ContentId,
		"parent_id":    in.ParentId,
		"author_name":  in.AuthorName,
		"author_email": in.AuthorEmail,
		"author_url":   in.AuthorUrl,
		"content":      in.Content,
		"ip":           in.Ip,
		"user_agent":   in.UserAgent,
		"status":       status,
		"like_count":   0,
		"created_at":   now,
		"updated_at":   now,
	}
	if in.UserId > 0 {
		data["user_id"] = in.UserId
	} else {
		data["user_id"] = nil
	}
	if in.ReplyToUserId > 0 {
		data["reply_to_user_id"] = in.ReplyToUserId
	} else {
		data["reply_to_user_id"] = nil
	}

	res, err := dao.Comments.Ctx(ctx).Data(data).Insert()
	if err != nil {
		return 0, err
	}

	return res.LastInsertId()
}

// Audit 批量审核评论并联动更新内容 comment_count
func (s *sComment) Audit(ctx context.Context, in model.CommentAuditInput) error {
	if len(in.Ids) == 0 {
		return nil
	}

	return g.DB().Transaction(ctx, func(ctx context.Context, tx gdb.TX) error {
		// 查询受影响的 content_ids
		contentIdVars, err := dao.Comments.Ctx(ctx).TX(tx).WhereIn("id", in.Ids).Array("content_id")
		if err != nil {
			return err
		}

		// 更新评论状态
		_, err = dao.Comments.Ctx(ctx).TX(tx).WhereIn("id", in.Ids).Data(g.Map{
			"status":     in.Status,
			"updated_at": gtime.Now(),
		}).Update()
		if err != nil {
			return err
		}

		// 重新统计受影响内容的评论数
		for _, cid := range contentIdVars {
			cId := cid.Int64()
			count, err := dao.Comments.Ctx(ctx).TX(tx).
				Where("content_id", cId).
				Where("status", "approved").
				Count()
			if err != nil {
				return err
			}

			_, err = dao.Contents.Ctx(ctx).TX(tx).WherePri(cId).Data(g.Map{
				"comment_count": count,
				"updated_at":    gtime.Now(),
			}).Update()
			if err != nil {
				return err
			}
		}

		return nil
	})
}

// Delete 删除评论
func (s *sComment) Delete(ctx context.Context, id int64) error {
	var c entity.Comments
	if err := dao.Comments.Ctx(ctx).WherePri(id).Scan(&c); err != nil {
		return err
	}
	if c.Id == 0 {
		return gerror.New("评论不存在")
	}

	return g.DB().Transaction(ctx, func(ctx context.Context, tx gdb.TX) error {
		_, err := dao.Comments.Ctx(ctx).TX(tx).WherePri(id).Delete()
		if err != nil {
			return err
		}

		// 重算 content_count
		count, err := dao.Comments.Ctx(ctx).TX(tx).
			Where("content_id", c.ContentId).
			Where("status", "approved").
			Count()
		if err != nil {
			return err
		}

		_, err = dao.Contents.Ctx(ctx).TX(tx).WherePri(c.ContentId).Data(g.Map{
			"comment_count": count,
			"updated_at":    gtime.Now(),
		}).Update()
		return err
	})
}
