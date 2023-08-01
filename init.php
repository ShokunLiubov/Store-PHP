<?php

const HOST = 'http://localhost';
const BASE_URL = 'make-up';
const DB_HOST = 'localhost';
const DB_NAME = 'makeUpStore';
const DB_USER = 'root';
const DB_PASS = '';
const PATH_PREFIX = 'controllers/';
const PATH_POSTFIX = '.php';

include('routes.php');
include_once('utils/db.php');
include_once('utils/system.php');
include_once('helper.php');

// include_once('model/messages.php');
