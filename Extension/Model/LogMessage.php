<?php declare(strict_types=1);

namespace FacturaScripts\Plugins\LogsToFile\Extension\Model;

use Closure;
use FacturaScripts\Core\Tools;

/**
 * @property string level
 * @property  string time
 */
class LogMessage
{
    public function testBefore(): Closure
    {
        return function () {
            if (in_array($this->level, ['error', 'critical'])) {

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

                /**
                 * Si no hemos podido leer el archivo de logs
                 * no hacemos nada y que continue guardando en la base de datos
                 * porque si no borraria todos los logs anteriores
                 * y solo quedaria en el log el último.
                 *
                 * De esta forma tambien evitamos el error de que no se puede
                 * pasar null al array_push. array_push($logs-->null, $this)
                 */
                if (false === is_array($logs)) {
                    return true;
                }

                array_push($logs, $this);
                $data = json_encode($logs);

                file_put_contents($pathLogFile, $data);

                return false;
            }

            return true;
        };
    }
}
