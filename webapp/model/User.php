<?php

use \ActiveRecord\Model;

class User extends Model
{
    static $has_many = [['user', 'foreign_key' =>'user_id', 'class_name' => 'User']];

    public function ShowTypeOfUser($tipouser){
        switch ($tipouser){
            case '1':
                return 'Administrador';
                break;
            case '3':
                return 'Gestor de Voo';
                break;
            case '4':
                return 'Operador de Checkin';
                break;
            case '2':
                return 'Utilizador';
                break;
            case '5':
                return 'Gestor De Marketing';
                break;
        }
    }
}
