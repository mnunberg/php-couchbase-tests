<?php
if(file_exists("couchbase.local.inc")) {
    include("couchbase.local.inc");
} else {
    define("COUCHBASE_CONFIG_HOST", "127.0.0.1:8091");
    define("COUCHBASE_CONFIG_USER", "");
    define("COUCHBASE_CONFIG_PASSWD", "");
    define("COUCHBASE_CONFIG_BUCKET", "");
}