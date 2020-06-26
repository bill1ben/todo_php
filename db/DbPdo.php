<?php

class DbPDO {

    const HOST = 'localhost';
    const DBNAME = 'todo';
    const USER = 'ubuntu';
    const PSSWD = 'ubuntu';


    public static function pdoConnexion(){

        try{
            $connexion = new PDO('mysql:host='.self::HOST.';port=3306;dbname='.self::DBNAME,
                self::USER, self::PSSWD);
            $connexion->exec('SET NAMES utf8');
        }catch(Exception $e){
            echo 'erreur : '.$e->getMessage().'<br />';
            echo 'N° : '.$e->getCode().'<br />';
            die();
        }
        return $connexion;
    }

}