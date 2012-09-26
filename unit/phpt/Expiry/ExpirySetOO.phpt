--TEST--
Expiry - ExpirySetOO
--FILE--
<?php
include dirname(__FILE__)."/../../phpunit-compat/couchbase-phpt-bootstrap.php";
couchbase_phpt_runtest("Expiry", "testExpirySetOO");
--EXPECT--
PHP_COUCHBASE_OK