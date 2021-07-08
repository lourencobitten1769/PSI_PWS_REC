<?php

use \ActiveRecord\Model;

class User extends Model
{
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
        }
    }
}
