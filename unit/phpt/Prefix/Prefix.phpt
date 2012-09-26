--TEST--
Prefix - Prefix
--FILE--
<?php
include dirname(__FILE__)."/../../phpunit-compat/couchbase-phpt-bootstrap.php";
couchbase_phpt_runtest("Prefix", "testPrefix");
--EXPECT--
PHP_COUCHBASE_OK