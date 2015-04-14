<?php

$redis = new Redis();
$redis->connect('127.0.0.1');
$configuration = [
    'processes' => [
        'process_1' => [
            'key' => 'process_1',
            'workers' => \rand(1,5),
            'cmd' => '/usr/bin/php examples/loop.php'
        ],
        'process_2' => [
            'key' => 'process_2',
            'workers' => \rand(1,5),
            'cmd' => '/usr/bin/php examples/loop.php'
        ],
    ]
];

$redis->set('curatrix::configuration', \json_encode($configuration));