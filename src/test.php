<?php

require_once '../vendor/autoload.php';

use MJDymalla\PHP\ISO3166;

$test = ISO3166::getSubDivisions('AU', 'ja');

print_r($test);

echo "\n";
