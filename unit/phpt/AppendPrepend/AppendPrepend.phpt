--TEST--
AppendPrepend - AppendPrepend
--FILE--
<?php
include dirname(__FILE__)."/../../phpunit-compat/couchbase-phpt-bootstrap.php";
couchbase_phpt_runtest("AppendPrepend", "testAppendPrepend");
--EXPECT--
PHP_COUCHBASE_OK