<?php

declare(strict_types=1);

$routes = glob(app_path('Modules/*/Routes/route.php'));
foreach ($routes as $route) {
    require $route;
}
