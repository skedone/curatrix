<?php

$redis = new Redis();
$redis->connect('127.0.0.1');

while(1) {
    $instance = $redis->get('curatrix::instance::point0.local');
    $workers = $redis->keys('curatrix::workers*');

    $running = [];
    foreach($workers as $worker) {
        $running[] = [
            'key' => $worker,
            'data' => \json_decode($redis->get($worker), TRUE)
        ];
    }

    $output = [
        'instance' => \json_decode($instance, TRUE),
        'running' => $running
    ];

    print_r($output); sleep(1);
}

echo json_encode($output);
return;