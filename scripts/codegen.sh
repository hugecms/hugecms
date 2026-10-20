php artisan gen:entity
php artisan gen:model
php artisan gen:dao
php artisan gen:service
php artisan gen:controller
php artisan gen:route
php artisan gen:enums

vendor/bin/pint app bootstrap config database public routes
vendor/bin/openapi app/Api/Admin -o docs/admin.json
vendor/bin/openapi app/Api/Common -o docs/common.json
vendor/bin/openapi app/Api/Portal -o docs/portal.json
vendor/bin/openapi app/Api/Shop -o docs/shop.json
vendor/bin/openapi app/Api/User -o docs/user.json
