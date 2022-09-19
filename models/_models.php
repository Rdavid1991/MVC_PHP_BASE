<?php

$dirs = array_diff(scandir(dirname(__FILE__)), array(".", "..", basename(__FILE__)));

foreach ($dirs as $file) {
    include_once(dirname(__FILE__) . "/".$file);
}
