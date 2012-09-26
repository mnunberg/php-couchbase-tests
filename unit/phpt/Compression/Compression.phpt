--TEST--
Compression - Compression
--FILE--
<?php
include dirname(__FILE__)."/../../phpunit-compat/couchbase-phpt-bootstrap.php";
couchbase_phpt_runtest("Compression", "testCompression");
--EXPECT--
PHP_COUCHBASE_OK