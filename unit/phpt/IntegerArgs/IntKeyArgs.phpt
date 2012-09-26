--TEST--
IntegerArgs - IntKeyArgs
--FILE--
<?php
include dirname(__FILE__)."/../../phpunit-compat/couchbase-phpt-bootstrap.php";
couchbase_phpt_runtest("IntegerArgs", "testIntKeyArgs");
--EXPECT--
PHP_COUCHBASE_OK