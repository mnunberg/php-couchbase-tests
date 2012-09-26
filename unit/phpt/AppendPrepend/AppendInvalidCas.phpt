--TEST--
AppendPrepend - AppendInvalidCas
--FILE--
<?php
include dirname(__FILE__)."/../../phpunit-compat/couchbase-phpt-bootstrap.php";
couchbase_phpt_runtest("AppendPrepend", "testAppendInvalidCas");
--EXPECT--
PHP_COUCHBASE_OK