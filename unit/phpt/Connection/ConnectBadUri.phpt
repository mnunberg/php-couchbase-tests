--TEST--
Connection - ConnectBadUri
--FILE--
<?php
include dirname(__FILE__)."/../../phpunit-compat/couchbase-phpt-bootstrap.php";
couchbase_phpt_runtest("Connection", "testConnectBadUri");
--EXPECT--
PHP_COUCHBASE_OK