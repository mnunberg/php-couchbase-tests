--TEST--
Compression - CompressionFlags
--FILE--
<?php
include dirname(__FILE__)."/../../phpunit-compat/couchbase-phpt-bootstrap.php";
couchbase_phpt_runtest("Compression", "testCompressionFlags");
--EXPECT--
PHP_COUCHBASE_OK