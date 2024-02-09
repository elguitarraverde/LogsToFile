<?php

namespace FacturaScripts\Plugins\LogsToFile\Extension\Model;

use Closure;
use FacturaScripts\Core\Tools;

class LogMessage
{
    public function testBefore(): Closure
    {
        return function () {
            if ($this->channel === 'master' && $this->level === 'error') {

                $logs = [];

                $pathDirectoryLogs = Tools::folder('MyFiles', 'Logs');
                if (false === is_dir($pathDirectoryLogs)) {
                    mkdir($pathDirectoryLogs, 0777, true);
                }

                $logDate = date('Y-m-d', strtotime($this->time));
                $pathLogFile = Tools::folder('MyFiles', 'Logs', $logDate . '_logs.json');

                if (is_file($pathLogFile)) {
                    $logs = json_decode(file_get_contents($pathLogFile), true);
                }

                array_push($logs, $this);
                $data = json_encode($logs);

                file_put_contents($pathLogFile, $data);

                return false;
            }
        };
    }
}
