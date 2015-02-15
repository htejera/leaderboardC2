<?php
/**
* Some test for sanidad.php.
*/
include('../app/inc/Sanidad.php');

$crypt=Cryptme::instance();
$f3->set('cryptme',$crypt);
$sanidad=Sanidad::instance($f3);

// Method: noNaughty
$test->expect(
    strcmp($sanidad->noNaughty('good'),'good') == 0,
    sprintf($msg,'noNaughty','Return value should be "good"')
);

$test->expect(
    strcmp($sanidad->noNaughty('fuck'),'Anonymous') == 0,
    sprintf($msg,'noNaughty','Return value should be Anonymous')
);

$test->expect(
    strcmp($sanidad->noNaughty('&034;'),'&quot;') == 0,
    sprintf($msg,'noNaughty','Return value should be &quot;')
);

// Method: auditScore
$test->expect(
    $sanidad->auditScore(3000) == 3000,
    sprintf($msg,'auditScore','Return value should be 3000')
);

$test->expect(
    $sanidad->auditScore(0) == 0,
    sprintf($msg,'auditScore','Return value should be 0')
);

$test->expect(
    $sanidad->auditScore(6001) == 0,
    sprintf($msg,'auditScore','Return value should be 0')
);

$test->expect(
    $sanidad->auditScore(-1) == 0,
    sprintf($msg,'auditScore','Return value should be 0')
);


// Method: antiCheat     
$test->expect(
    $sanidad->antiCheat("m6WbwJ2WnYmMv5Wa") == true,
    sprintf($msg,'antiCheat','Return value should be true')
);

$test->expect(
    $sanidad->antiCheat("MWR$56ODS152567") == false,
    sprintf($msg,'antiCheat','Return value should be false')
);

// Method: sanitize
$test->expect(
    strcmp($sanidad->sanitize('<script>var link</script>'),'var link') == 0,
    sprintf($msg,'antiCheat','Return value should be "var link"')
);