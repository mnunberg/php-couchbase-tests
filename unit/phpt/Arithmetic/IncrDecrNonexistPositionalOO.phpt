--TEST--
Arithmetic - IncrDecrNonexistPositionalOO
--FILE--
<?php
include dirname(__FILE__)."/../../phpunit-compat/couchbase-phpt-bootstrap.php";
couchbase_phpt_runtest("Arithmetic", "testIncrDecrNonexistPositionalOO");
--EXPECT--
PHP_COUCHBASE_OK