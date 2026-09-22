// ==========================================================================
// Code generated and maintained by GoFrame CLI tool. DO NOT EDIT.
// ==========================================================================

package internal

import (
	"context"

	"github.com/gogf/gf/v2/database/gdb"
	"github.com/gogf/gf/v2/frame/g"
)

// RecycleBinDao is the data access object for the table recycle_bin.
type RecycleBinDao struct {
	table    string             // table is the underlying table name of the DAO.
	group    string             // group is the database configuration group name of the current DAO.
	columns  RecycleBinColumns  // columns contains all the column names of Table for convenient usage.
	handlers []gdb.ModelHandler // handlers for customized model modification.
}

// RecycleBinColumns defines and stores column names for the table recycle_bin.
type RecycleBinColumns struct {
	Id            string //
	DeletedBy     string // 删除人用户ID
	TargetType    string // 原对象类型：content/term/attachment/user/form_submission/comment
	TargetId      string // 原对象ID
	OriginalData  string // 删除前的全量数据快照（JSON）
	RestoreData   string // 恢复时所需的数据映射（如恢复时需新建ID）
	RetentionDays string // 保留天数（超时由 Scheduler 物理清除）
	CreatedAt     string // 删除时间
	ExpireAt      string // 过期时间（虚拟生成列）
}

// recycleBinColumns holds the columns for the table recycle_bin.
var recycleBinColumns = RecycleBinColumns{
	Id:            "id",
	DeletedBy:     "deleted_by",
	TargetType:    "target_type",
	TargetId:      "target_id",
	OriginalData:  "original_data",
	RestoreData:   "restore_data",
	RetentionDays: "retention_days",
	CreatedAt:     "created_at",
	ExpireAt:      "expire_at",
}

// NewRecycleBinDao creates and returns a new DAO object for table data access.
func NewRecycleBinDao(handlers ...gdb.ModelHandler) *RecycleBinDao {
	return &RecycleBinDao{
		group:    "default",
		table:    "recycle_bin",
		columns:  recycleBinColumns,
		handlers: handlers,
	}
}

// DB retrieves and returns the underlying raw database management object of the current DAO.
func (dao *RecycleBinDao) DB() gdb.DB {
	return g.DB(dao.group)
}

// Table returns the table name of the current DAO.
func (dao *RecycleBinDao) Table() string {
	return dao.table
}

// Columns returns all column names of the current DAO.
func (dao *RecycleBinDao) Columns() RecycleBinColumns {
	return dao.columns
}

// Group returns the database configuration group name of the current DAO.
func (dao *RecycleBinDao) Group() string {
	return dao.group
}

// Ctx creates and returns a Model for the current DAO. It automatically sets the context for the current operation.
func (dao *RecycleBinDao) Ctx(ctx context.Context) *gdb.Model {
	model := dao.DB().Model(dao.table)
	for _, handler := range dao.handlers {
		model = handler(model)
	}
	return model.Safe().Ctx(ctx)
}

// Transaction wraps the transaction logic using function f.
// It rolls back the transaction and returns the error if function f returns a non-nil error.
// It commits the transaction and returns nil if function f returns nil.
//
// Note: Do not commit or roll back the transaction in function f,
// as it is automatically handled by this function.
func (dao *RecycleBinDao) Transaction(ctx context.Context, f func(ctx context.Context, tx gdb.TX) error) (err error) {
	return dao.Ctx(ctx).Transaction(ctx, f)
}
