--TEST--
Check for couchbase_get_result_code
--SKIPIF--
<?php include "skipif.inc" ?>
--INI--

--FILE--
<?php
include "couchbase.inc";
$handle = couchbase_connect(COUCHBASE_CONFIG_HOST, COUCHBASE_CONFIG_USER, COUCHBASE_CONFIG_PASSWD, COUCHBASE_CONFIG_BUCKET);
$key = uniqid("couchbase_");
var_dump(couchbase_get($handle, $key));
var_dump(COUCHBASE_KEY_ENOENT === couchbase_get_result_code($handle));

var_dump(couchbase_replace($handle, $key, "foo"));
var_dump(COUCHBASE_KEY_ENOENT === couchbase_get_result_code($handle));

var_dump(couchbase_add($handle, $key, "foo"));
var_dump(COUCHBASE_SUCCESS === couchbase_get_result_code($handle));

var_dump(couchbase_delete($handle, $key));
var_dump(COUCHBASE_SUCCESS === couchbase_get_result_code($handle));

var_dump(couchbase_append($handle, $key, "prefix_"));
var_dump(COUCHBASE_NOT_STORED === couchbase_get_result_code($handle));

var_dump(couchbase_prepend($handle, $key, "prefix_"));
var_dump(COUCHBASE_NOT_STORED === couchbase_get_result_code($handle));
?>
--EXPECTF--
NULL
bool(true)
bool(false)
bool(true)
string(%d) %s
bool(true)
bool(true)
bool(true)
bool(false)
bool(true)
bool(false)
bool(true)
