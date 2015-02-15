<?php
/**
* Some test for utils.php.
*/
include('../app/inc/Utils.php');

$utils=Utils::instance($f3);

// Method: byeArray
$test->expect(
    strcmp($utils->byeArray('["1","2","3"]'),'"1","2","3"') == 0,
    sprintf($msg,'byeArray','Return value should be "1","2","3"')
);

// Method: strReplace
$test->expect(
    strcmp($utils->strReplace('Pierre','Fermat','Pierre Fermat'),'Fermat Fermat') == 0,
    sprintf($msg,'strReplace','Return value should be "Fermat"')
);

