--TEST--
Arithmetic - IncrString
--FILE--
<?php
include dirname(__FILE__)."/../../phpunit-compat/couchbase-phpt-bootstrap.php";
couchbase_phpt_runtest("Arithmetic", "testIncrString");
--EXPECT--
PHP_COUCHBASE_OK