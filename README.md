## About This Project

This project is a test subject for Wefox interview.

This project achieves these goals:
- Full backend editorial platform (based on NOVA) with login for Admin to manipulate resources.
- Well documented (SWAGGER) APIs with authentications with CRUD functionality.
- Automated installation with bash commands in a Docker container.
- Well organazied code with Laravel Pint.
- Stable code with Larastan.

## Requirements
- php@8.1
- Postgres 14.5

## Installation

1. Clone the project with:
    - `git clone  https://github.com/pedramkat/wefox.git`
2. Go to the root directory of the wefox project
    - `cd wefox`
3. Lunch the deploy command
    - `bash docker/init-docker.sh `

## XDEBUG lunch.json

Add this configuration to your file .vscode/lunch.json if you are using xdebug:

````
{
    "version": "0.2.0",
    "configurations": [
        {
            "name": "Listen for Xdebug",
            "type": "php",
            "request": "launch",
            "port": 9200,
            "pathMappings": {
                "/var/www/html/wefox": "${workspaceRoot}"
            }
        }
    ]
}
````

Now the platform is up and running see the **Usage** section below.
## Usage
Backend login:
http://0.0.0.0:8077

Admin login:
Email: admin@wefox.com
Password: admin

## Swagger API documentation
http://0.0.0.0:8077/api/documentation

**Important:** To check the API with swagger after receiving a login token from the /api/login section, insert it to the Authorize section. Green button on top right. (ex. `Bearer 6|yiaRHtOKorrGkmREN1sFYcAc3OUKwypijISPT9w612e62d47`)

## Testing

**Lunch Docker bash**
docker exec -it php81_wefox bash 

**Lunch Docker psql**
docker exec -it postgres_wefox psql -U wefox

**Lunch the test**
` php artisan test --env=testing`

**Lunch the phpstan**
`./vendor/bin/phpstan analyse`

**Lunch the php cs fixer test**
`./vendor/bin/pint --test`

### License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
