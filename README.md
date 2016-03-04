# Lumen REST API components

### Create CRUD endpoints, Model and Repository

```
php artisan rest:create {NAME}
```

Add into routes.php next code:

```
$api = $app->make('Nebo15\REST\Router');
$api->api({API PREFIX}, '{NAME}Controller', [ {MIDDLEWARE} ]);
```