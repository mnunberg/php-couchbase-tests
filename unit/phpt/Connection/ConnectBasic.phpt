--TEST--
Connection - ConnectBasic
--FILE--
<?php
include dirname(__FILE__)."/../../phpunit-compat/couchbase-phpt-bootstrap.php";
couchbase_phpt_runtest("Connection", "testConnectBasic");
--EXPECT--
PHP_COUCHBASE_OK