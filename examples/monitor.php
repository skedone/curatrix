<?php

$redis = new Redis();
$redis->connect('127.0.0.1');

$instance = $redis->get('curatrix::instance::point0.local');
$workers = $redis->keys('curatrix::workers*');

$running = [];
print_r($workers);
foreach($workers as $worker) {
    $running[] = [
        'key' => $worker,
        'data' => \json_decode($redis->get($worker), TRUE)
    ];
}

echo json_encode([
    'instance' => \json_decode($instance, TRUE),
    'running' => $running
]);
return;