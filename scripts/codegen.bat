@echo off
rem ==========================================================================
rem 常规代码再生成流水线（不触碰数据库）
rem 前置：库表结构已是最新（首次或表结构变更时先执行 codegen-init.bat）
rem ==========================================================================
php artisan gen:entity
php artisan gen:enums
php artisan gen:model
php artisan gen:dao
php artisan gen:service
php artisan gen:controller
php artisan gen:route
php artisan optimize
.\vendor\bin\pint.bat .\app\
