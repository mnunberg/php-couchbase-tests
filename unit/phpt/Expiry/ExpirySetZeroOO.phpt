--TEST--
Expiry - ExpirySetZeroOO
--FILE--
<?php
include dirname(__FILE__)."/../../phpunit-compat/couchbase-phpt-bootstrap.php";
couchbase_phpt_runtest("Expiry", "testExpirySetZeroOO");
--EXPECT--
PHP_COUCHBASE_OK