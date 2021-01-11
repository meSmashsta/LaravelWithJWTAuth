<?php

namespace App;

class Console {
    public static function log($tag, $msg) {
        $stdout = fopen('php://stdout', 'w');
        fwrite($stdout, "$tag - $msg" . PHP_EOL);
    }
}