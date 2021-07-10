<?php

use \ActiveRecord\Model;

class Flight extends Model
{
    static $belongs_to = [
        ['airplane'],
        ['origin_airport', 'foreign_key' =>'origin', 'class_name' => 'Airport'],
        ['destination_airport', 'foreign_key' =>'destination', 'class_name' => 'Airport']
    ];

    static $has_many = array(
        array('scale'),
        ['ticket', 'foreign_key' =>'ticket_id', 'class_name' => 'Ticket']
    );
}