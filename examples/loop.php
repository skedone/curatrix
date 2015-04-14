<?php

$count = 0;
$random = \rand(1, 1);
while($count < $random) {
    print ++$count . ' is less than ' . $random . "\n";
    sleep(10);
}