<?php

$PW2_CONFIG = array(
    'db_scheme' => 'MySQL', // PdoMySQL
    'db_info' => array(
        'host' => '',
        'user' => '',
        'pass' => '',
        'db' => ''
    ),
    'email' => array(
        'from' => '', // Email from
        'message_header' => '', // Text before report
        'message_footer' => '-------------------' . "\r\n" // Text after report
            . "\r\n"
            . 'phpWatch: https://github.com/tck/phpwatch' . "\r\n",
    ),
    'path' => dirname(__FILE__),
);

if (file_exists($PW2_CONFIG['path'] . '/config.local.php')) {
    include_once $PW2_CONFIG['path'] . '/config.local.php';
}

define('PW2_VERSION', '2.1.0 Beta');
define('PW2_PATH', $PW2_CONFIG['path']);
