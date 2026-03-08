<?php
    session_start();
    $auth_page=true;
    
    //if regestration is succes then redirect to step3
    require_once('../database.inc.php');

    $error = "";
    $email = $_SESSION['eaccount']['email'];
    $name=$_SESSION['eaccount']['name'];

     if (isset($_POST["confirm"])) {
            $pdo = connect_db();

            $query= "Insert into address(house_no,street_name,city,postal_code) values (:house_no,:street_name,:city,:postal_code);";
            $statement= $pdo->prepare($query);
            $statement->bindValue(':house_no',$_SESSION['register']['house_no']);
            $statement->bindValue(':street_name',$_SESSION['register']['street_name']);
            $statement->bindValue(':city',$_SESSION['register']['city']);
            $statement->bindValue(':postal_code',$_SESSION['register']['postal_code']);
            $statement->execute();
            $address_id=$pdo->lastInsertId();

            $query = "Insert into users(user_id,national_id,name,date_of_birth,
            mobile_number,telephone_number,email,role,address_id) values 
            (:id,:national_id,:name,:date_of_birth,:mobile_number,:telephone_number,:email,:role,:address_id);";

            $statement= $pdo->prepare($query);

            
            $statement->bindValue(':id',$_SESSION['register']['id']);
            $statement->bindValue(':national_id',$_SESSION['register']['national_id']);
            $statement->bindValue(':name',$_SESSION['register']['username']);
            $statement->bindValue(':date_of_birth',$_SESSION['register']['date_of_birth']);
            $statement->bindValue(':address_id',$address_id);
            $statement->bindValue(':mobile_number',$_SESSION['register']['mobile_number']);
            $statement->bindValue(':telephone_number',$_SESSION['register']['telephone_number']);
            $statement->bindValue(':email',$_SESSION['register']['email']);
            $statement->bindValue(':role',$_SESSION['register']['role']);
            $statement->execute();


            if(isset($_SESSION['register']['image'])){

                $lastID=$pdo->lastInsertId();
                $new_name=$lastID.'.jpeg';
                $query = "update users set image=:image where user_id=:lastid;";
                $statement= $pdo->prepare($query);
                $statement->bindValue(':lastid',$lastID);
                $statement->bindValue(':image',$lastID.'.jpeg');
                $statement->execute();
                move_uploaded_file( $_SESSION['register']['image'],'../Images/'.$new_name);
            }


            $query="insert into bankdetails(owner_id,bank_name,branch_name,account_number) values (:owner_id,:bank_name,:branch_name,:account_number);";
            $statement=$pdo->prepare($query);
            $statement->bindValue(':owner_id',$_SESSION['register']['id']);
            $statement->bindValue(':bank_name',$_SESSION['register']['bankName']);
            $statement->bindValue(':branch_name',$_SESSION['register']['branchName']);
            $statement->bindValue(':account_number',$_SESSION['register']['id']);
            $statement->execute();
            

            $query="insert into accounts(user_id,email,password,username) values (:user_id,:email,:password,:username);";
            $statement=$pdo->prepare($query);
            $statement->bindValue(':email',$_SESSION['eaccount']['email']);
            $statement->bindValue(':username',$_SESSION['eaccount']['name']);
            $statement->bindValue(':password',$_SESSION['eaccount']['password']);
            $statement->bindValue(':user_id',$_SESSION['register']['id']);
            $statement->execute();
            $acc_id=$pdo->lastInsertId();

            $query = "insert into messages (receiver_account_id, title, message_body, status) values 
                (:receiver, :title, :mes, :status);";

            $statement = $pdo->prepare($query);

            $statement->bindValue(':receiver',$acc_id);
            $statement->bindValue(':title','Owner ID');
            $statement->bindValue(':status','unread');
            $statement->bindValue(':mes','Welcome to our platform, Your ID is '.$_SESSION['register']['id']);

            $statement->execute();

            $pdo=null;
            header("Location:ConfirmationPage.php");

    }
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <title>Birzeit Flat Rent</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="../main.css" rel="stylesheet">
    </head>

    <body>

        <?php include('../includes/header.php') ?>

        <section class="page-navigation-main">
            <?php include('../includes/leftSideNavigation.php') ?>
            <main class="forms-style">
                
                <form method="post" action="OwnerRegisterStep3.php">
                    <section class="row gap-16 flex-direction-column shadow border-16 py-32 px-32">
                        <h2>Owner Step-3 Registeration</h2>

                        <div class="row width-100">
                            <label for="name">Username:</label>
                            <input type="text" id="name" name="name" value="<?= htmlspecialchars($name) ?>" required disabled>
                        </div>

                        <div class="row width-100">
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required disabled>
                        </div>

                        <button type="submit" name="confirm">Confirm</button>  
                    </section>
                </form>
            </main>
        </section>
        
        <?php
            if(!empty($error)){
                echo "<div class='toast toast-error display-none'>
                        <p>".$error."</p>
                    </div>";
            }
        ?>
        <?php include('../includes/footer.php') ?>
        <script src="../main.js"></script>
    </body>

</html>