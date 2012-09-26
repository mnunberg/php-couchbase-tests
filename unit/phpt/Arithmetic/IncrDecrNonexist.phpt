--TEST--
Arithmetic - IncrDecrNonexist
--FILE--
<?php
include dirname(__FILE__)."/../../phpunit-compat/couchbase-phpt-bootstrap.php";
couchbase_phpt_runtest("Arithmetic", "testIncrDecrNonexist");
--EXPECT--
PHP_COUCHBASE_OK