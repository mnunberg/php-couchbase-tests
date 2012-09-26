--TEST--
AppendPrepend - AppendNonExist
--FILE--
<?php
include dirname(__FILE__)."/../../phpunit-compat/couchbase-phpt-bootstrap.php";
couchbase_phpt_runtest("AppendPrepend", "testAppendNonExist");
--EXPECT--
PHP_COUCHBASE_OK