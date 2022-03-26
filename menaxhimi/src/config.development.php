<?php

/**
 * PHPMaker 2022 configuration file (Development)
 */

return [
    "Databases" => [
        "DB" => ["id" => "DB", "type" => "MYSQL", "qs" => "`", "qe" => "`", "host" => "localhost", "port" => "3306", "user" => "root", "password" => "", "dbname" => "volal"]
    ],
    "SMTP" => [
        "PHPMAILER_MAILER" => "smtp", // PHPMailer mailer
        "SERVER" => "vol.al", // SMTP server
        "SERVER_PORT" => 587, // SMTP server port
        "SECURE_OPTION" => "tls",
        "SERVER_USERNAME" => "kontakt@vol.al", // SMTP server user name
        "SERVER_PASSWORD" => "vol2022al", // SMTP server password
    ],
    "JWT" => [
        "SECRET_KEY" => "c81liBon52YQSHBb", // API Secret Key
        "ALGORITHM" => "HS512", // API Algorithm
        "AUTH_HEADER" => "X-Authorization", // API Auth Header (Note: The "Authorization" header is removed by IIS, use "X-Authorization" instead.)
        "NOT_BEFORE_TIME" => 0, // API access time before login
        "EXPIRE_TIME" => 600 // API expire time
    ]
];
