<?php

$count = 0;
$random = \rand(15, 50);
while($count < $random) {
    print ++$count . ' is less than ' . $random . "\n";
    sleep(1);
}