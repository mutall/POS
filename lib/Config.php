<?php
//constants
switch ($_SERVER['HTTP_HOST']) {
    case 'mutall.co.ke':
        define('URLROOT', 'http://mutall.co.ke/pos');
        define('DB_HOST', "localhost");
        define('DB_USER', "mutallco");
        define('DB_PASSWORD', "mutall_techub");
        define('DB_NAME', "mutallco_pos");
        break;
    case 'mutalldevs.co.ke':
        define('URLROOT', 'http://mutalldevs.co.ke/pos');
        define('DB_HOST', "localhost");
        define('DB_USER', "mutallde");
        define('DB_PASSWORD', "mutalldatamanagers");
        define('DB_NAME', "mutallde_pos");
        break;
    default:
        define('URLROOT', 'http://localhost/pos');
        define('DB_HOST', "localhost");
        define('DB_USER', "root");
        define('DB_PASSWORD', "");
        define('DB_NAME', "mutall_pos");
}