# rest-api-cake
this is simple REST API (JWT authentication) with CakePHP -> snippetbox  

# License
MIT License


## End point
| Method | Pattern                                   | Action                            |
|:-------|:------------------------------------------|:----------------------------------|
| `GET`  | `/snippet/all`                            | Return all snippet                |
| `GET`  | `/snippet/:snippetId`                     | Return a specific snippet         |
| `POST` | `/snippet/create`                         | Create a new snippet              |
| `POST` | `/user/signup`                            | Create a new user                 |
| `POST` | `/user/login`                             | Authenticate and login the user   |
| `POST` | `/user/logout`                            | Logout the user                   |
| `POST` | `/user/resign`                            | Resign from snippetbox            |                                          |
| `GET`  | `/admin/user/all`                         | Return all user data              |
| `POST` | `/admin/user/forced-resign/:userId`       | Forced resign a normal user       |
| `POST` | `/admin/user/make-admin/:userId`          | Change a user role as `admin`     |
| `POST` | `/admin/snippet/forced-delete/:snippetId` | Forced delete a someone's snippet |

## Status Code

- `200`: Succeeded Processing
- `4XX`: Client Error
- `400`: Bad Request
- `401`: Unauthorized
- `403`: Forbidden
- `404`: Not Found
- `405`: Method Not Allowed
- `422`: Unprocessable Entity
- `5XX`: Server Error
- `500`: Internal Server Error


## JSON response format


```
// default response structure
{
  "code": 200,
  "url": "https://example.com",
  "body": {
    "message": "message",
    "property": "value",
    ...
  }
}

// error response structure
{
  "code": 400~500,
  "url": "https://example.com",
  "body" {
    "message": "error message",
    "errorCount": int 0~,
    "error": {
      { error object },
      ...
    }
  }
}

// if unwraped Exception is thrown
{
  "code": 400~500,
  "url": "https://example.com",
  "message": "Error message"
}
```


## Progress :gorilla:
- [x] :gorilla: build environment
- [x] :gorilla: bake controller and model based on table 
- [x] :gorilla: setup for JSON response
- [x] :gorilla: prepare signup method
- [x] :gorilla: prepare create snippet method
- [x] :gorilla: prepare find All or A snippet method
- [x] :gorilla: setup for JSON response when Exception thrown
- [x] :gorilla: prepare authenticate method
- [x] :gorilla: prepare login and logout method
- [x] :gorilla: bug fix around user authentication (JWT auth)
- [x] :gorilla: fix user resign method logic
- [x] :gorilla: fix snippet method logic
- [x] :gorilla: bug fix `ValidationErrorException`
- [x] :gorilla: redesign JSON response structure
- [x] :gorilla: add column users table
- [ ] :gorilla: prepare admin user method (forced resign user, make admin, forced delete snippet)
- [ ] :gorilla: prepare utility method and replace exception as error response
- [ ] :gorilla: prepare test cases


## For me
### how to install cakephp at first time 
```
$ docker compose up -d
$ docker exec -it PHP_CONTAINER_NAME bash

---- into PHP container ----
# pwd
/var/www/html
# composer self-update && composer create-project --prefer-dist cakephp/app:4.* .
```

### how to generate RSA private and public key
```
at PHP container
# pwd
/var/www/html

---- Generate Private Key ----
# openssl genrsa -out config/jwt.key 1024

---- Generate Public Key ----
# openssl rsa -in config/jwt.key -outform PEM -pubout -out config/jwt.pem
```