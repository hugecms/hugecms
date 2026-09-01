<?php

$routes = glob(app_path('Api/*/Routes/route.php'));
foreach ($routes as $route) {
    require $route;
}
