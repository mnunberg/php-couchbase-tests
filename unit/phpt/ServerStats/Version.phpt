--TEST--
ServerStats - Version
--FILE--
<?php
include dirname(__FILE__)."/../../phpunit-compat/couchbase-phpt-bootstrap.php";
couchbase_phpt_runtest("ServerStats", "testVersion");
--EXPECT--
PHP_COUCHBASE_OK