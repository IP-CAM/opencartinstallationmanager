<?php

define('ASSETS_DIR', getcwd() . '/assets/');
define('STORE_DIR', 'C:\xampp\htdocs\opencart');
define('STORE_URL', 'http://localhost/opencart/');

// Install Store
$versions = scandir(ASSETS_DIR);

// Stores List
$stores = scandir(STORE_DIR);

include 'oci.php';
include 'index.view.php';

    