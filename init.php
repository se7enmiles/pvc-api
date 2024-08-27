<?php
header('Content-Type: application/json');

require_once('./app/exception/customHandler.php');

$unions = require_once('./configs/unions.php');
$configs = require_once('./configs/app.php');
require_once('./app/UnionChecker.php');
require_once('./app/db/Db.php');


$method = $_SERVER['REQUEST_METHOD'];

if (strtolower($method) !== 'post') {
    throw new Exception('Api supported only Post method', 500);
}