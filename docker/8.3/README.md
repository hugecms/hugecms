```
cd base
docker build -t juling/php:8.3-alpine-v3.24-base .

cd swoole
docker build --build-arg SW_VERSION=v6.2.3 --build-arg COMPOSER_VERSION=2.10.3 --build-arg PHP_BUILD_VERSION=83 -t juling/php:8.3-alpine-v3.24-swoole .
```