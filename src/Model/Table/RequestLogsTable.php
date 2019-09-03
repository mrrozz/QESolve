<?php
// src/Model/Table/RequestLogsTable.php
namespace App\Model\Table;

use Cake\ORM\Table;

class RequestLogsTable extends Table
{
    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');
    }
}
