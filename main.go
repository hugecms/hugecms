package main

import (
	_ "hugecms/internal/logic"

	_ "github.com/gogf/gf/contrib/drivers/mysql/v2"

	"github.com/gogf/gf/v2/os/gctx"

	"hugecms/internal/cmd"
)

func main() {
	cmd.Main.Run(gctx.GetInitCtx())
}
