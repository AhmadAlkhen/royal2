Installation :
1- Clone the repository:
(https://github.com/AhmadAlkhen/royal2.git)

2 - Install dependencies, :
composer install

3 - Copy .env.example to .env and configure your environment variables, including database settings.

4-Generate application key:
php artisan key:generate

5 - Migrate the database:
php artisan migrate

6- Seed the database :
php artisan db:seed

Then Start the development server:

php artiasn serve

The routes will be :

login : http://localhost:8000/api/auth/login

user list : http://localhost:8000/api/users/index

user store : http://localhost:8000/api/users/store

etc...
