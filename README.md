# Requirements
- php 8.1
- composer

# Installation
```shell
$ composer install
```

# Run
```shell 
$ php src/main.php
```
Expected output: 
```shell
[INFO] Registering GET /has_permission/{token}
[INFO] Server running on 127.0.0.1:1337
```
# Run script locally
```shell 
$ curl http://127.0.0.1:1337/has_permission/token1234
```

# Testing
```shell
$ php vendor/bin/phpunit Test
```
