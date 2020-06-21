#Movie store

This repo was developed using __Laravel 7__

##Requirements

I've created a docker compose file for development, so we can take advantage of this in order to have
a dev environment setup easily, even though is not a must to have docker installed, it would be preferable.

- [composer](https://getcomposer.org/)
- [npm](https://www.npmjs.com/get-npm)
- [docker](https://docs.docker.com/engine/install/)
- [docker-compose](https://docs.docker.com/compose/install/)

#### Install dependencies

```
$ cd src && composer install && npm install
```

#### Configure application

We will copy the .env.example to .env

```
$ cp .env.example to .env
```

You can change the configuration for the DB if you don't feel comfortable with the defaults.

#### Build and Run the app with docker-composer

Run in `src` folder:
```
$ docker-compose build  && docker-compose up -d
```

#### Setting up the project for the first run

There is a script named `setup-app.sh`, we need to run it, we will be prompted
for the email and password for the admin:

```
$ docker-compose exec php sh setup-app.sh
```

if we failed creating the user or we want to create another want we can execute:

```
$ docker-compose exec php php artisan app:create-admin
```

And that's it we can go to http://localhost:8081


####Troubleshooting

If you ran `docker-compose` with `sudo` you could have received some permission error.

 For the app:
 
```
$ docker exec -it php sh
```
in the container:

```
# chgrp -R www-data /var/www/html/storage /var/www/html/bootstrap/cache && chmod -R ug+rwx /var/www/html/storage /var/www/html/bootstrap/cache
```

For mysql:
```
$ docker exec -it mysql sh
```

in the container:

```
# chown mysql:mysql -R /var/lib/mysql/laravel/
```
