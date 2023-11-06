# Developer Interview Questions

### Requirements:
- PHP 7.4 or higher
- php-pdo
- npm 
- composer

### How to install:

1. Clone project into your project directory
2. In the project directory run:
```commandline
composer install
cp .env.example .env
npm run build
php bin/app
```

### How to run:

```commandline
php -S localhost:8000
```

### How to run tests:

```commandline
php ./vendor/bin/phpunit tests
```
