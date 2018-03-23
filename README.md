# Api Auth

This is a very tiny system based upon Symfony 3.4. It uses FOSUserBundle to manage users and allow login actions with a simple X-Auth-Token.

## Getting Started

Just clone this repo and configure the Symfony app, then test the HTTP requests.

### Prerequisites

Clone the repository

```
git clone https://github.com/boreales/api-auth.git
```

Install the dependencies

```
composer install
```

Create a database and then update the doctrine schema

```
php bin/console doctrine:schema:update --force
```

Create a user in your database with the FOSUser CLI

```
php bin/console fos:user:create
```

Give to your user a API role

```
php bin/console fos:user:promote username ROLE_API
```

You're ready to test !

## Running the tests

Use [Postman](https://www.getpostman.com/) to make your tests.

Make a GET request on localhost or any domain wherever you put your repo.

```
GET http://localhost/api/web/app_dev.php/webservice
```

You won't be able to see the data because you're not logged in.

To login make a POST request on this route with your username/password data :

```
POST http://localhost/api/web/app_dev.php/auth
```

You should get your authentication token in response. Copy its value and paste it into the Postman headers as X-Auth-Token.
Then retry the data route :

```
GET http://localhost/api/web/app_dev.php/webservice
```

Now you can see the data in response ;).

If you wanna logout here's the route. Just make a GET request on it with your X-Auth-Token header.

```
GET http://localhost/api/web/app_dev.php/auth-break
```

## Authors

* **Boréales Créations** - *Initial work* - [boreales](https://github.com/boreales)

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details

## Acknowledgments

Mainly inspired from the french tutorial : [Zeste de Savoir](https://zestedesavoir.com/tutoriels/1280/creez-une-api-rest-avec-symfony-3/)
