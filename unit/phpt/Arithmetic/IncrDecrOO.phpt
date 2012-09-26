--TEST--
Arithmetic - IncrDecrOO
--FILE--
<?php
include dirname(__FILE__)."/../../phpunit-compat/couchbase-phpt-bootstrap.php";
couchbase_phpt_runtest("Arithmetic", "testIncrDecrOO");
--EXPECT--
PHP_COUCHBASE_OK