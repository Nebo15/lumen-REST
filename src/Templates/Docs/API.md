# Model {modelName}

### Fillable 

Fields, that you can create or edit:
{fieldsFillable}
### Visible 

Fields, that you can see in API response for one object:
{fieldsVisible}
### Listable

Fields, that you can see in API response in list of objects:
{fieldsListable}

## Get List of {modelName}

```shell
$ curl http://HOST/{routePrefix}/{routeName}
```

```json
{
    "meta": {
        "code": 200
    },
    "data": [
        {
            "Listable fields"
        },
        {
            "Listable fields"
        }
    ]
}
```

## Create a {modelName}

```shell
$ curl -d'{Fillable fields}' 
http://HOST/{routePrefix}/{routeName}/56c31536a60ad644060041af
```

```json
{
    "meta": {
        "code": 200
    },
    "data": {
        "Visible fields"
    }
}
```

## Copy a {modelName}

```shell
$ curl -X POST 
http://HOST/{routePrefix}/{routeName}/56c31536a60ad644060041af/copy
```

```json
{
    "meta": {
        "code": 200
    },
    "data": {   
         "Visible fields"
    }
}
```

## Get a {modelName}

```shell
$ curl http://HOST/{routePrefix}/{routeName}/56c31536a60ad644060041af
```

```json
{
    "meta": {
        "code": 200
    },
    "data": {
        "Visible fields"
    }
}
```

## Update a {modelName}

```shell
$ curl -X PUT -d'{Fillable fields}'
http://HOST/{routePrefix}/{routeName}/56c31536a60ad644060041af
```

```json
{
    "meta": {
        "code": 200
    },
    "data": {
        "Visible fields"
    }
}
```

## Delete a {modelName}

```shell
$ curl -X DELETE http://HOST/{routePrefix}/{routeName}/56c31536a60ad644060041af
```

```json
{
    "meta": {
        "code": 200
    }
}
```