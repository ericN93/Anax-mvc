<?php

return [
    // Set up details on how to connect to the database
    // Set up details on how to connect to the database
    'dsn'     => "mysql:host=blu-ray.student.bth.se; dbname=erna13",
    'username'        => "erna13",
    'password'        => "8F77Kb5P7JnyZs7C",
    'driver_options'  => [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"],
    'table_prefix'    => "phpmvc",

    // Display details on what happens
    'verbose' => false,

    // Throw a more verbose exception when failing to connect
    'debug_connect' => 'true',
];
/*
*På server**
*
*
'dsn'     => "mysql:host=blu-ray.student.bth.se; dbname=erna13",
'username'        => "erna13",
'password'        => "8F77Kb5P7JnyZs7C",
'driver_options'  => [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"],
'table_prefix'    => "phpmvc",

// Display details on what happens
'verbose' => false,

// Throw a more verbose exception when failing to connect
'debug_connect' => 'true',
*/




/*
*localt**
*
*
// Set up details on how to connect to the database
'dsn'     => "mysql:host=localhost;dbname=phpmvc;",
'username'        => "",
'password'        => "",
'driver_options'  => [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"],
'table_prefix'    => "phpmvc",

// Display details on what happens
'verbose' => false,

// Throw a more verbose exception when failing to connect
'debug_connect' => 'true',
*/
