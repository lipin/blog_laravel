<?php
//打印sql语句用的
//Event::listen('illuminate.query', function ($sql) {
//    var_dump($sql);
//});
return array(


    'connections' => array(
        'mysql' => array(
            'driver'    => 'mysql',
            'host'      => 'localhost',
            'database'  => 'blog',
            'username'  => 'homestead',
            'password'  => 'secret',
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix'    => '',
        ),
    ),


);
