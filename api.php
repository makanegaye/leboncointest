<?php
/**
 * Created by PhpStorm.
 * User: Makane GAYE
 * Date: 04/02/2019
 * Time: 17:12
 */
define('ROOT', dirname(__DIR__ . '/..'));
require ROOT . '/app/App.php';
App::load();

use App\Components\Api\Api;

$api = new Api();
$api->processApi();