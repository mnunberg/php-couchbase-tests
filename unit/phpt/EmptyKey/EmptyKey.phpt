--TEST--
EmptyKey - EmptyKey
--FILE--
<?php
include dirname(__FILE__)."/../../phpunit-compat/couchbase-phpt-bootstrap.php";
couchbase_phpt_runtest("EmptyKey", "testEmptyKey");
--EXPECT--
PHP_COUCHBASE_OK