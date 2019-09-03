<?php
// src/Model/Entity/ResponseLogs.php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class ResponseLogs extends Entity
{
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];
}