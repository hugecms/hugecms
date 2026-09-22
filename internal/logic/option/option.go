package option

import (
	"context"
	"time"

	"github.com/gogf/gf/v2/database/gdb"
	"github.com/gogf/gf/v2/frame/g"
	"github.com/gogf/gf/v2/os/gcache"
	"github.com/gogf/gf/v2/os/gtime"

	"hugecms/internal/dao"
	"hugecms/internal/model"
	"hugecms/internal/model/entity"
	"hugecms/internal/service"
)

type sOption struct {
	cache *gcache.Cache
}

func init() {
	service.RegisterOption(New())
}

func New() service.IOption {
	return &sOption{
		cache: gcache.New(),
	}
}

// Search 分页查询配置
func (s *sOption) Search(ctx context.Context, in model.OptionSearchInput) (*model.OptionSearchOutput, error) {
	if in.Page <= 0 {
		in.Page = 1
	}
	if in.PageSize <= 0 {
		in.PageSize = 10
	}

	m := dao.Options.Ctx(ctx)
	if in.OptionKey != "" {
		m = m.Where("option_key LIKE ?", "%"+in.OptionKey+"%")
	}

	total, err := m.Count()
	if err != nil {
		return nil, err
	}

	var options []entity.Options
	if err := m.Page(in.Page, in.PageSize).OrderAsc("id").Scan(&options); err != nil {
		return nil, err
	}

	list := make([]model.OptionItem, 0, len(options))
	for _, o := range options {
		list = append(list, model.OptionItem{
			Id:          int64(o.Id),
			OptionKey:   o.OptionKey,
			OptionValue: o.OptionValue,
			Autoload:    int(o.Autoload),
			CreatedAt:   o.CreatedAt,
			UpdatedAt:   o.UpdatedAt,
		})
	}

	return &model.OptionSearchOutput{
		List:  list,
		Total: total,
		Page:  in.Page,
		Size:  in.PageSize,
	}, nil
}

// Get 获取指定配置值，带缓存
func (s *sOption) Get(ctx context.Context, key string) (string, error) {
	cacheKey := "option:" + key
	v, err := s.cache.GetOrSetFunc(ctx, cacheKey, func(ctx context.Context) (interface{}, error) {
		val, err := dao.Options.Ctx(ctx).Where("option_key", key).Value("option_value")
		if err != nil {
			return "", err
		}
		return val.String(), nil
	}, 10*time.Minute)
	if err != nil {
		return "", err
	}
	return v.String(), nil
}

// Save 保存单条配置
func (s *sOption) Save(ctx context.Context, in model.OptionSaveInput) error {
	now := gtime.Now()
	count, err := dao.Options.Ctx(ctx).Where("option_key", in.OptionKey).Count()
	if err != nil {
		return err
	}

	if count > 0 {
		_, err = dao.Options.Ctx(ctx).Where("option_key", in.OptionKey).Data(g.Map{
			"option_value": in.OptionValue,
			"autoload":     in.Autoload,
			"updated_at":   now,
		}).Update()
	} else {
		_, err = dao.Options.Ctx(ctx).Data(g.Map{
			"option_key":   in.OptionKey,
			"option_value": in.OptionValue,
			"autoload":     in.Autoload,
			"created_at":   now,
			"updated_at":   now,
		}).Insert()
	}

	if err == nil {
		_, _ = s.cache.Remove(ctx, "option:"+in.OptionKey)
		_, _ = s.cache.Remove(ctx, "options:autoload")
	}
	return err
}

// BatchSave 批量保存配置
func (s *sOption) BatchSave(ctx context.Context, in model.OptionBatchSaveInput) error {
	err := g.DB().Transaction(ctx, func(ctx context.Context, tx gdb.TX) error {
		now := gtime.Now()
		for k, v := range in.Options {
			count, err := dao.Options.Ctx(ctx).TX(tx).Where("option_key", k).Count()
			if err != nil {
				return err
			}
			if count > 0 {
				_, err = dao.Options.Ctx(ctx).TX(tx).Where("option_key", k).Data(g.Map{
					"option_value": v,
					"updated_at":   now,
				}).Update()
			} else {
				_, err = dao.Options.Ctx(ctx).TX(tx).Data(g.Map{
					"option_key":   k,
					"option_value": v,
					"autoload":     1,
					"created_at":   now,
					"updated_at":   now,
				}).Insert()
			}
			if err != nil {
				return err
			}
			_, _ = s.cache.Remove(ctx, "option:"+k)
		}
		return nil
	})

	if err == nil {
		_, _ = s.cache.Remove(ctx, "options:autoload")
	}
	return err
}

// Delete 删除配置
func (s *sOption) Delete(ctx context.Context, key string) error {
	_, err := dao.Options.Ctx(ctx).Where("option_key", key).Delete()
	if err == nil {
		_, _ = s.cache.Remove(ctx, "option:"+key)
		_, _ = s.cache.Remove(ctx, "options:autoload")
	}
	return err
}

// GetAllAutoload 获取所有自动加载的配置
func (s *sOption) GetAllAutoload(ctx context.Context) (map[string]string, error) {
	cacheKey := "options:autoload"
	v, err := s.cache.GetOrSetFunc(ctx, cacheKey, func(ctx context.Context) (interface{}, error) {
		var list []entity.Options
		err := dao.Options.Ctx(ctx).Where("autoload", 1).Scan(&list)
		if err != nil {
			return nil, err
		}
		res := make(map[string]string)
		for _, item := range list {
			res[item.OptionKey] = item.OptionValue
		}
		return res, nil
	}, 30*time.Minute)
	if err != nil {
		return nil, err
	}
	return v.MapStrStr(), nil
}
