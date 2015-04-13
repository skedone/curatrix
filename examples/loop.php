<?php

$count = 0;
$random = \rand(5, 6);
while($count < $random) {
    echo 'foo ' . ++$count;
    sleep(10000000);
}