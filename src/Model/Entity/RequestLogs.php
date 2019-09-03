<?php
// src/Model/Entity/RequestLogs.php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class RequestLogs extends Entity
{
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];
}