<?php
// login.php

require 'vendor/autoload.php';
use Auth0\SDK\Auth0;

$auth0 = new Auth0([
    'domain' => 'juegagt.us.auth0.com',
    'client_id' => 'CALb7Sqt2G7wO1UaO0mszAR0oVw9nFFT',
    'client_secret' => '1UXD4lhbhyPGgm72kpxM5WNeskMaScW06XnTRDDrmpOW4Oddk6f6tFMQnwl_joDP',
    'redirect_uri' => 'http://localhost/',
    'scope' => 'openid profile email',
]);

$auth0->login();