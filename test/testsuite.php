<?php
/**
 * Test suite.
 * So how do we run these tests? you can just open your browser 
 * and specify the address http://localhost/leaderboard/test/testsuite.php 
 * That's all there is to it.
*/
$f3 = require('../lib/base.php');
$f3->config('../config.ini');
$f3->set('DEBUG',1);
$f3->set('AUTOLOAD', '../app/;../app/inc/');

$msg = "Method: %s | %s.";
$db = new DB\SQL('sqlite:../data/test.sqlite'); 
$test = new Test;

// Setup
include('tasks.php');
task_clean_database($db);

// Test
include('integration/leaderboardIntegrationTest.php');
include('unit/json2Construct2Test.php');         
include('unit/sanidadTest.php');
include('unit/utilsTest.php');

// Results
task_display_results($test);