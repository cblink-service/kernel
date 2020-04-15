# test

### install
```shell script
composer require cblink-service/kernel
```


### used

```php

$config = [
    // use private url
    'private' => true,
    'base_url' => ' private url',
    'app_id' => 'app id',
    'key' => 'key id',
    'secret' => 'secret key',
];

// Application is your package App
// Application must be extends Cblink\\Service\Kernel\ServiceContainer
$app = new Application($config);

$app->logger->info('test', []);

```

### extends

```php
// TestServiceProvider must be declared Pimple\ServiceProviderInterface
// app call register method or implement method 'getCustomProviders' 
$app->register(TestServcePovider::class);


// used 
$app->test->xxx();

```
