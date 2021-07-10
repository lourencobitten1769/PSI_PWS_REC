<?php

use \ActiveRecord\Model;

class Scale extends Model
{
    static $belongs_to = array(
        ['airplane'],
        ['origin_airport', 'foreign_key' => 'origin', 'class_name' => 'Airport'],
        ['destination_airport', 'foreign_key' => 'destination', 'class_name' => 'Airport'],
        array('flight')
    );

}