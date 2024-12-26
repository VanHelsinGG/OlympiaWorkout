<?php   

use OlympiaWorkout\bootstrap\Router\Router;

Router::get('/', 'HomeController@index');
Router::get('/home', 'HomeController@index');
Router::get('/about', 'PageController@about');
Router::post('/contact', 'ContactController@store');