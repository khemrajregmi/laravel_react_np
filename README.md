<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Project

This project is News aggregator build with Laravel Reactjs and Tailwindcss. In this project differeent categories new are fetch from NewYork Times , Newsapi.org and The Guardian


## Requirements
`Composer version 2.X`

`php 8.2`

## Getting Started

1. Clone this repository and `cd` into it.
2. Execute `composer install` a to download dependencies of laravel.
3. copy `.env.example` and make `.env`
4. `./vendor/bin/sail up ` to run docker environment
5. `php artisan migrate`, `php artisan db:seed`
6. `php artisan app:fetch-news-and-articles` to run cronjob 
7. `php artisan serve`
8. you  can access to database with phpmyadmin http://0.0.0.0:8001/
9. then for the fronted `cd frontend`  and then install frontend dependencies with `npm install`
10. finally `npm run dev`


## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

![login page](./resources/images/Screenshot 2023-06-06 at 00.08.44.png)
![Register page](./resources/images/Screenshot%202023-06-06%20at%2000.22.58.png)
![Profile and Preference](./resources/images/Screenshot%202023-06-06%20at%2000.08.37.png)
![News feed with view more feature](./resources/images/Screenshot%202023-06-06%20at%2000.09.01.png)


