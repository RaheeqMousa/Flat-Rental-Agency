<?php
    define('DBHOST','localhost');
    define('DBNAME','web1220515_1220515');
    define('DBPASS','');
    define('DBUSERNAME','root');

    function connect_db($host=DBHOST, $db_name=DBNAME, $pass=DBPASS, $username=DBUSERNAME){
        try{

            $pdo= new PDO("mysql:host=$host;dbname=$db_name;charset=utf8mb4;",$username,$pass);

            return $pdo;
        }catch(PDOException $ex){
            die($ex->getMessage());
        }
    }


?>