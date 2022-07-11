<?php
class User{
    private $username;
    private $email;
    private $password;
    private $passwordConfirm;

    function __construct($username,$email,$password,$passwordConfirm)
    {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->passwordConfirm = $passwordConfirm;
    }
    public function getusername(){
        return $this->username;
    }
    public function getemail(){
        return $this->email;
    }
    public function getpassword(){
        return $this->password;
    }
    public function notEmpty(){
        if(empty($this->username) || empty($this->email)|| empty($this->password)|| empty($this->passwordConfirm)){
            return false;
        }
        return true;
    }
    public function validUsername(){
        if(preg_match("/^[a-zA-Z0-9]*$/",$this->username)){
            return true;
        }
        return false;
    }
    public function validEmail(){
        if(filter_var($this->email,FILTER_VALIDATE_EMAIL)){
            return true;
        }
        return false;
    }
    public function pwMatch(){
        if($this->password===$this->passwordConfirm){
            return true;
        }
        return false;
    }
    public function checkUser(){
        
        $pdo = new PDO('mysql:host=127.0.0.1:3310;dbname=phplogin','root','mourad');
        
        $stmt = $pdo->prepare('SELECT username FROM users WHERE username=? or email=?;');
        $stmt->execute([$this->username,$this->email]);

        if($stmt->rowCount()==0){
            return true;
        }
        return false;
        }
}
