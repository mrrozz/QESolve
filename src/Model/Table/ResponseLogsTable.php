<?php
// src/Model/Table/ResponseLogsTable.php
namespace App\Model\Table;

use Cake\ORM\Table;

class ResponseLogsTable extends Table
{
    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');
    }
}
