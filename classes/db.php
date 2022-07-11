<?php 
class dbConnection{
    public static function connect(){
            return new PDO('mysql:host=127.0.0.1:3310;dbname=phplogin', 'root', 'mourad');
    }
}

?>