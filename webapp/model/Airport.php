<?php

use \ActiveRecord\Model;

class Airport extends Model
{
    static $has_many = array(
        array('flights')
    );
}
