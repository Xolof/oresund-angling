<?php

/**
 * Config file for Database.
 *
 * Example for MySQL.
 *  "dsn" => "mysql:host=localhost;dbname=test;",
 *  "username" => "test",
 *  "password" => "test",
 *  "driver_options"  => [\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"],
 *
 * Example for SQLite.
 *  "dsn" => "sqlite::memory:",
 *  "dsn" => "sqlite:$path",
 *  "dsn" => "sqlite:" . ANAX_INSTALL_PATH . "/data/db.sqlite",
 *
 */

return [
    "dsn" => "mysql:host=localhost;dbname=qadb;",
    "username" => "olof",
    "password" => "pass",
    "driver_options"  => [\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4'"],
    "fetch_mode"       => \PDO::FETCH_OBJ,
    "table_prefix"     => null,
    "session_key"      => "Anax\Database",
    "emulate_prepares" => false,

    // True to be very verbose during development
    "verbose"         => false,

    // True to be verbose on connection failed
    "debug_connect"   => false,
];
