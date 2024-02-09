<?php
namespace FacturaScripts\Plugins\LogsToFile;

use FacturaScripts\Core\Base\InitClass;

class Init extends InitClass
{
    public function init()
    {
        $this->loadExtension(new Extension\Model\LogMessage());
    }

    public function update()
    {
        //
    }
}