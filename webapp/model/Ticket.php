<?php

use \ActiveRecord\Model;

class Ticket extends Model
{
    static $belongs_to = [
        ['flight', 'foreign_key' => 'flight_id', 'class_name' => 'Flight'],
        ['user', 'foreign_key' => 'user_id', 'class_name' => 'User']
    ];
}