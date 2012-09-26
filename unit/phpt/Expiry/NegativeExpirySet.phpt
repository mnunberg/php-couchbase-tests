--TEST--
Expiry - NegativeExpirySet
--FILE--
<?php
include dirname(__FILE__)."/../../phpunit-compat/couchbase-phpt-bootstrap.php";
couchbase_phpt_runtest("Expiry", "testNegativeExpirySet");
--EXPECT--
PHP_COUCHBASE_OK