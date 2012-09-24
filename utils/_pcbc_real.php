<?php
define('COUCHBASE_KEY_EEXISTS', 1);
$options = getopt(
    "h:".
    "u:".
    "p:".
    "b:".
    "c:".
    "k:".
    "v:".
    "C:".
    "E:".
    "I:".
    "j".
    "e".
    "F:"
);

$helpmsg = <<<EOF
Usage: $argv[0] -c COMMAND < options >

Commands:
    get: Get a key
    set, get, append, prepend, replace, add: Mutate a key
    rm: Remove a key
    incr, decr: Perform an arithmetic operation

Connection Options:
    -h <hostname>
    -u <username>
    -p <password>
    -b <bucket>

Key Options:
    -k <key>

Value Options:
    -e Evaluate the value (if present), thus "array(1,2,3)" is an array
    -F Load value from this file
    -v <value>
    -I <initial-arithmetic-value>

View Options:
    -P <params> comma-separated list of parameters to pass to the view.

Misc Options:
    -C <cas>
    -E <exp>
    -j serialize with JSON

EOF;

foreach (array('h', 'u', 'p', 'b', 'k', 'v', 'C', 'E', 'I') as $k) {
    if (!array_key_exists($k, $options)) {
        $options[$k] = '';
    }
}
################################################################################

$host = $options['h'];
$bucket = $options['b'];
$username = $options['u'];
$password = $options['p'];
$cmd = $options['c'];
$key = $options['k'];
$cas = $options['C'];
$exp = $options['E'];
$value = $options['v'];
$arith_initial = $options['I'];
$valfile = $options['F'];

$do_eval = array_key_exists('e', $options);
$use_json = array_key_exists('j', $options);
$handle = new Couchbase($host, $username, $password, $bucket);

if ($use_json) {
    print "Using JSON for serialization..\n";
    $handle->setOption(COUCHBASE::OPT_SERIALIZER,
                       COUCHBASE::SERIALIZER_JSON);
}

if ($valfile) {
    $value = file_get_contents($valfile);
    if (!$value) {
        die("File does not exist or is empty");
    }
}

if ($value && $do_eval) {
    $evalstr = "return $value;";
    $value = eval($evalstr);
    var_dump($value);
}

$cas = $cas ? $cas : 0;
$exp = $exp ? intval($exp) : 0;



if (!$cmd) {
    print "Must have command!\n";
    print $helpmsg;
    exit(1);
}

$cmd = strtolower($cmd);
$is_ok = false;
$ret = NULL;

class CommandContext {
    
    function __construct($handle, $key, $value, $exp, $cas, $initial) {
        $this->handle = $handle;
        $this->key = $key;
        $this->value = $value;
        $this->cas = $cas;
        $this->exp = $exp;
        $this->initial = $initial;
        
        $this->r_cas = NULL;
        $this->r_value = NULL;
    }
    
    function setResult($ok, $value, $cas = 0) {
        $this->r_value =  $value;
        $this->r_cas = $cas;
        $this->ok = (bool)$ok;
        var_dump($this->ok);
        var_dump($ok);
    }
}

if ($cmd == 'help') {
    print $helpmsg;
    exit(0);
}


$cmdhandler = array();

$cmdhandler['get'] = function($cmd, $ctx) {
    $retval = $ctx->handle->get($ctx->key);
    $ctx->setResult($retval, $retval);
};


$cmdhandler['incr'] = function($cmd, $ctx) {
    
    $value = intval($ctx->value);
    $do_create = 0;
    $arith_initial = 0;
    
    if ($ctx->initial) {
        $do_create = 1;
        $arith_initial = intval($arith_initial);
    } else {
        $arith_initial = 0;
    }
    
    $fn_name = ($cmd == "incr") ? "increment" : "decrement";
    
    $retval = call_user_method($fn_name, $ctx->handle,
                               $ctx->$key, $value, $do_create,
                               $exp, $arith_initial);
    
    $ctx->setResult(gettype($ret) == 'integer', $ret);
};

$cmdhandler['decr'] = $cmdhandler['incr'];

$cmdhandler['rm'] = function($cmd, $ctx) {
    $retval = $ctx->handle->delete($ctx->key, $ctx->cas);
    $ctx->setResult($retval, '');
};

$cmdhandler['set'] = function($cmd, $ctx) {
    $retval = call_user_method($cmd, $ctx->handle,
                               $ctx->key,
                               $ctx->value,
                               $ctx->exp,
                               $ctx->cas);
    
    $ctx->setResult($retval, $ctx->value, $retval);
};

foreach (array("add", "replace", "append", "prepend") as $mcmd) {
    $cmdhandler[$mcmd] = $cmdhandler['set'];
}

if (!array_key_exists($cmd, $cmdhandler)) {
    die("No such command $cmd");
}

$ctx = new CommandContext($handle, $key, $value, $exp, $cas, $arith_initial);

$cmdhandler[$cmd]($cmd, $ctx);

print "Parameters passed\n";
print "Key: $ctx->key\n";
print "Value: ";
var_dump($ctx->value);
print "Cas: $ctx->cas\n";
print "Exp: $ctx->exp\n";

if ($ctx->ok) {
    print "OK:\n";
    print "Value: " . var_dump($ctx->r_value);
    print "Cas: " . $ctx->r_cas . "\n";
} else {
    print "FAIL:\n";
    print "Code: " . $handle->getResultCode() . "\n";
    print "Description: " . $handle->getResultMessage() . "\n";
}