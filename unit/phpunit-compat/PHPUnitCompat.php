<?php

class PHPUnitCompat_TestPrematureGracefulException extends Exception {}

class PHPUnitCompat_TestSkipException extends
    PHPUnitCompat_TestPrematureGracefulException { }

class PHPUnitCompat_TestIncompleteException extends
    PHPUnitCompat_TestPrematureGracefulException { }

class PHPUnitCompat {

    static $verbose = false;

    protected function dieCommon($msg) {
        die("[PHPUnitCompat] $msg");
    }

    public function markTestIncomplete($comment = "") {
        throw new PHPUnitCompat_TestIncompleteException($comment);
    }

    public function markTestSkipped($comment = "") {
        throw new PHPUnitCompat_TestSkipException($comment);
    }

    protected function matches($type, $other)
    {
        switch ($type) {
            case 'numeric': {
                return is_numeric($other);
            }

            case 'integer':
            case 'int': {
                return is_integer($other);
            }

            case 'float': {
                return is_float($other);
            }

            case 'string': {
                return is_string($other);
            }

            case 'boolean':
            case 'bool': {
                return is_bool($other);
            }

            case 'null': {
                return is_null($other);
            }

            case 'array': {
                return is_array($other);
            }

            case 'object': {
                return is_object($other);
            }

            case 'resource': {
                return is_resource($other);
            }

            case 'scalar': {
                return is_scalar($other);
            }

            case 'callable': {
                return is_callable($other);
            }
        }
    }

    public function assertInternalType($type, $obj, $msg = "") {
        $res = $this->matches($type, $obj);
        if (!$res) {
            $this->dieCommon("Expected type '$type'");
        }
        return true;
    }

    public function assertNull($obj, $msg = "") {
        if (!$obj === NULL) {
            $this->dieCommon("Expected 'NULL'");
        }
        return true;
    }

    public function assertNotNull($obj, $msg = "") {
        if ($obj === NULL) {
            $this->dieCommon("Expected not-NULL");
        }
        return true;
    }

    public function assertContains($needle, $haystack, $msg = "") {
        if (!is_string($haystack)) {
            $this->dieCommon("assertContains supports only string searching");
        }

        if (strpos($haystack, $needle) === FALSE) {
            $this->dieCommon("Couldn't find '$needle'");
        }
        return true;
    }

    public function assertInstanceOf($cls, $obj, $msg = "") {
        if (!$obj instanceof $cls) {
            $this->dieCommon("Expected instance of $cls");
        }
        return true;
    }

    public function assertEquals($expected, $actual, $msg = "") {
        if ($expected !== $actual) {
            $this->dieCommon("Not equal!");
        }
        return true;
    }

    public function assertNotEquals($expected, $actual, $msg = "") {
        if ($expected === $actual) {
            $this->dieCommon("Expected not equals!");
        }
        return true;
    }

    public function assertTrue($obj, $msg = "") {
        if ($obj !== TRUE) {
            $this->dieCommon("Expected TRUE");
        }
        return true;
    }

    public function assertFalse($obj, $msg = "") {
        if ($obj !== FALSE) {
            $this->dieCommon("Expected FALSE");
        }
        return true;
    }

    public function assertNotEmpty($obj, $msg = "") {
        if (empty($obj)) {
            $this->dieCommon("Expected not-empty");
        }
        return true;
    }

    public function assertGreaterThan($gt, $val, $msg = "") {
        if (! ($val > $gt)) {
            $this->dieCommon("Expected value > $gt");
        }
        return true;
    }

    public function assertLessThan($lt, $val, $msg = "") {
        if (!($val < $lt)) {
            $this->dieCommon("Expected value < $lt");
        }
        return true;
    }

    public function assertRegExp($re, $str, $msg = "") {
        if (preg_match($re, $str) < 1) {
            $this->dieCommon("Couldn't match '$re'");
        }
        return true;
    }

    public function assertArrayHasKey($key, $array, $msg = "") {
        if (!array_key_exists($key, $array)) {
            $this->dieCommon("Couldn't assert presence of $key in array");
        }
        return true;
    }

    public function assertCount($exp, $array, $msg = "") {
        if (count(array_keys($array)) != $exp) {
            $this->dieCommon("Couldn't assert count == $exp");
        }
        return true;
    }

    protected function setUp() {

    }

    protected function tearDown() {

    }

    public function runSingleTest($name) {
        $reflector = new ReflectionClass($this);
        $meth = $reflector->getMethod($name);

        if (self::$verbose) {
            fprintf(STDERR, "Running $name..\n");
        }

        $this->setUp();

        $uncaught = NULL;

        try {
            $meth->invoke($this);
        } catch (PHPUnitCompat_TestPrematureGracefulException $exc) {

            if (self::$verbose) {
                printf("\tSkipped.. " . $exc->getMessage());
            }

        } catch (Exception $exc) {
            $uncaught = $exc;
        }

        $this->tearDown();

        if (self::$verbose) {
            fprintf(STDERR, "\tDone..\n");
        }

        if ($uncaught) {
            throw $uncaught;
        }
    }

    public function runAllTests() {
        $reflector = new ReflectionClass($this);
        $methods = $reflector->getMethods();
        foreach ($methods as $meth) {
            $name = $meth->getName();
            if (preg_match("/^test/", $name) ) {
                $this->runSingleTest($name);
            }
        }
    }
}

function phpunit_compat_error_handler($errno,
                                      $errstr,
                                      $errfile,
                                      $errline,
                                      $errcontext) {

    throw new ErrorException($errstr, $errno, $errno, $errfile, $errline);
}

set_error_handler("phpunit_compat_error_handler");

?>
