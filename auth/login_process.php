<?php
        
    require_once('../database.inc.php');
    session_start();
    $auth_page=true;
    $name=$_POST['name'];
    $password=$_POST['pass'];
        
    $pdo=connect_db();
    $query="select user.name, user.role, user.user_id, user.mobile_number, account_id  from accounts s
                join users user
                on user.user_id=s.user_id
                where username=:username and password=:password;";
        
    $statement=$pdo->prepare($query);
    $statement->bindValue(':username',$name);
    $statement->bindValue(':password',$password);
    $statement->execute();
    $user=$statement->fetch();

    if($user){
        $_SESSION['user']['account_id']=$user['account_id'];
        $_SESSION['user']['role']=$user['role'];
        $_SESSION['user']['name']=$user['name'];
        $_SESSION['user']['user_id']=$user['user_id'];
        $_SESSION['user']['phone']=$user['mobile_number'];

        $pdo=null;
        $_SESSION['error']='';

        if (isset($_SESSION['redirect_after_login']) && $_SESSION['user']['role']==="customer") {
            $tmp_redirect = $_SESSION['redirect_after_login'];
            unset($_SESSION['redirect_after_login']);
            header("Location: ../pages/$tmp_redirect");
            exit;
        }

        header("Location:../index.php");
        exit;
    }else{
        $pdo=null;
        $_SESSION['error']="wrong Username or Password";
        header("Location:login.php");
        exit;
    }

?>