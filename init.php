<?php

const HOST = 'http://localhost';
const BASE_URL = '/make-up/';
const DB_HOST = 'localhost';
const DB_NAME = 'makeUpStore';
const DB_USER = 'root';
const DB_PASS = '';
const PATH_PREFIX = 'src/controllers/';
const PATH_POSTFIX = '.php';

session_start();

require_once __DIR__ . '/vendor/autoload.php';
require_once('src/Seeding/Seed.php');
require_once('src/Config/Routes.php');
include_once('src/Utils/Db.php');
include_once('src/Utils/System.php');
include_once('src/Utils/Helper.php');
include_once('src/Utils/Arr.php');
