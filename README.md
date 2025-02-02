# Base Project with Laravel 11 + Docker + Laravel Pint + Laravel PEST + Telescope + Debugar + AdminLTE3 + DataTables server side + Spatie ACL

## Resources

-   Basic user controller
-   2FA authentication
-   Visitors log
-   API routes with JWT auth

## Usage

-   `cp .env.example .env`
-   Edit .env parameters
-   `alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'`
-   `sail composer install`
-   `sail artisan key:generate`
-   `sail artisan jwt:secret`
-   `sail artisan storage:link`
-   `sail artisan migrate --seed`
-   `npm install && npm run dev`
-   `sail stop`

-   `docker-compose exec laravel.test bash`

### Programmer login

-   user: <programador@base.com>
-   pass: 12345678
