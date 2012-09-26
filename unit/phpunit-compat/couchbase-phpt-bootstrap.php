<?php

require_once 'PHPUnitCompat.php';
class PHPUnit_Framework_TestCase extends PHPUnitCompat {}

require_once dirname(__FILE__).'/../Common.php';

function couchbase_phpt_runtest($clsname, $name) {
    require_once(dirname(__FILE__)."/../$clsname.php");
    $reflector = new ReflectionClass($clsname);
    $obj = $reflector->newInstance();

    # If any assertions fail here, PHPUnitCompat will fail the test
    # and complain.
    $obj->runSingleTest($name);
    printf("PHP_COUCHBASE_OK\n");
}
