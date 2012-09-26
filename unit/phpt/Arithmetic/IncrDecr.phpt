--TEST--
Arithmetic - IncrDecr
--FILE--
<?php
include dirname(__FILE__)."/../../phpunit-compat/couchbase-phpt-bootstrap.php";
couchbase_phpt_runtest("Arithmetic", "testIncrDecr");
--EXPECT--
PHP_COUCHBASE_OK