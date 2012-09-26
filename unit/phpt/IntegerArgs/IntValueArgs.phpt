--TEST--
IntegerArgs - IntValueArgs
--FILE--
<?php
include dirname(__FILE__)."/../../phpunit-compat/couchbase-phpt-bootstrap.php";
couchbase_phpt_runtest("IntegerArgs", "testIntValueArgs");
--EXPECT--
PHP_COUCHBASE_OK