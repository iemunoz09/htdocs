<?php
require 'vendor/autoload.php';

use Arrilot\DotEnv\DotEnv;
use Auth0\SDK\Auth0;

DotEnv::load('.env.php');

$auth0 = new Auth0([
  'domain' => DotEnv::get('AUTH0_DOMAIN'),
  'client_id' =>  DotEnv::get('AUTH0_CLIENT_ID'),
  'client_secret' => DotEnv::get('AUTH0_CLIENT_SECRET'),
  'redirect_uri' => DotEnv::get('AUTH0_CALLBACK_URL'),
  'scope' => 'openid profile email',
]);

$userInfo = $auth0->getUser();

?>