--TEST--
Errors - ErrorCodes
--FILE--
<?php
include dirname(__FILE__)."/../../phpunit-compat/couchbase-phpt-bootstrap.php";
couchbase_phpt_runtest("Errors", "testErrorCodes");
--EXPECT--
PHP_COUCHBASE_OK