--TEST--
Arithmetic - Expiry
--FILE--
<?php
include dirname(__FILE__)."/../../phpunit-compat/couchbase-phpt-bootstrap.php";
couchbase_phpt_runtest("Arithmetic", "testExpiry");
--EXPECT--
PHP_COUCHBASE_OK