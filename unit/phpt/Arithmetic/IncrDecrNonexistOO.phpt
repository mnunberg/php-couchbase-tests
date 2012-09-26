--TEST--
Arithmetic - IncrDecrNonexistOO
--FILE--
<?php
include dirname(__FILE__)."/../../phpunit-compat/couchbase-phpt-bootstrap.php";
couchbase_phpt_runtest("Arithmetic", "testIncrDecrNonexistOO");
--EXPECT--
PHP_COUCHBASE_OK