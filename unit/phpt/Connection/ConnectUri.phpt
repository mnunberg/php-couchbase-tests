--TEST--
Connection - ConnectUri
--FILE--
<?php
include dirname(__FILE__)."/../../phpunit-compat/couchbase-phpt-bootstrap.php";
couchbase_phpt_runtest("Connection", "testConnectUri");
--EXPECT--
PHP_COUCHBASE_OK