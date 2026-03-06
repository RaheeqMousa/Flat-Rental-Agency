 <?php
    session_start();
    require_once('../database.inc.php');

    $customer_id=$_POST['id'];
    $name=$_POST['name'];
    $email=$_POST['email'];
    $telephone_number=$_POST['telephone_number'];
    $mobile_number=$_POST['mobile_number'];
    $image=isset($_FILES['image']['tmp_name'])??$_FILES['image']['tmp_name'];

    $pdo=connect_db();
    $query="update users set name=:name ,email=:email ,telephone_number=:telephone_number
            , mobile_number=:mobile_number";


    $new_name=$customer_id.'.jpeg';
    $update_image=false;
                
    if($_FILES['image']['error']===UPLOAD_ERR_OK && $_FILES["image"]["type"] === "image/jpeg"){
        $query.=" ,image=:image";
        $update_image=true;
        move_uploaded_file($_FILES['image']['tmp_name'],'../Images/'.$new_name);
    }else
        $_SESSION['error']="Images must be of type jpeg/jpg only";

    $query.=" where user_id=:user_id;";
    $statement= $pdo->prepare($query);

    $statement->bindValue(':email',$email);
    $statement->bindValue(':name',$name);
    $statement->bindValue(':mobile_number',$mobile_number);
    $statement->bindValue(':telephone_number',$telephone_number);
    $statement->bindValue(':user_id',$_SESSION['user']['user_id']);
    if($update_image)
        $statement->bindValue(':image',$new_name);

    $success=$statement->execute();   

    if($success){
        $_SESSION['error']='';
        $pdo=null;
        header("Location:profile.php");
    }
    
    $pdo=null;
?>