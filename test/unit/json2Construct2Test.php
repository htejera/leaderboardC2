<?php
/**
* Some test for utils.php.
*/
include('../app/inc/Json2Construct2.php');

$jsonC2 = Json2Construct2::instance($f3);

// Method: toC2Dictionary
$test->expect(
    json_decode($jsonC2->toC2Dictionary('[["1"],["2"],["3"]]')) != null,
    sprintf($msg,'toC2Dictionary','Return value should be a valid JSON')
);

// Method: toC2Array
$test->expect(
    json_decode($jsonC2->toC2Array(1,1,1,'[["1"]')) != null,
    sprintf($msg,'toC2Array','Return value should be a valid JSON')
);


