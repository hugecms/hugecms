<?php

declare(strict_types=1);

$routeFiles = glob(app_path('Api/*/Routes/route.php'));
foreach ($routeFiles as $routeFile) {
    require $routeFile;
}
