<?php

namespace App\Traits;
trait ZKConnection{
    public function pingDevice($ip)
{
    $isWindows = stripos(PHP_OS, 'WIN') === 0;

    if ($isWindows) {

        $command = "ping -n 1 -w 1 $ip";
        exec($command, $output, $status);

        return $status === 0;
    } else {

        $port = 4370; 
        $timeout = 2;
        $connection = @fsockopen($ip, $port, $errno, $errstr, $timeout);

        if ($connection) {
            fclose($connection);
            return true;
        }

        return false;
    }
}


}
