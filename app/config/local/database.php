<?php
//打印sql语句用的
//Event::listen('illuminate.query', function ($sql) {
//    var_dump($sql);
//});
return array(


    'connections' => array(
        'mysql' => array(
            'driver'    => 'mysql',
            'host'      => isset($_ENV['MYSQL_DB_HOST']) ? $_ENV['MYSQL_DB_HOST'] : 'localhost',
            'database'  => isset($_ENV['MYSQL_DB_NAME']) ? $_ENV['MYSQL_DB_NAME'] : 'blog',
            'username'  => isset($_ENV['MYSQL_DB_USER']) ? $_ENV['MYSQL_DB_USER'] : 'homestead',
            'password'  => isset($_ENV['MYSQL_DB_PWD']) ? $_ENV['MYSQL_DB_PWD'] : 'secret',
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix'    => '',
        ),
    ),


);
