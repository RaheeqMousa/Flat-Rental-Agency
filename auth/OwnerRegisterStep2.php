<?php
    session_start();
    $auth_page=true;
    //if regestration is succes then redirect to step3
    require_once('../database.inc.php');

    $error = "";
    $email = "";
    $password = "";
    $confirm_password = "";
    $name="";
    $id=isset($_SESSION['register']['id'])? $_SESSION['register']['id']:null;
    if (isset($_POST["email"]) && isset($_POST["name"]) && isset($_POST["password"]) && isset($_POST["confirm-pass"])) {

        $email = trim($_POST["email"]);
        $password = $_POST["password"];
        $confirm_password = $_POST["confirm-pass"];
        $name= $_POST["name"];

        $pdo= connect_db();
        $query= "select * from accounts where email=:email;";
        $statement=$pdo->prepare($query);
        $statement->bindValue(':email',$email);
        $statement->execute();
 
        if($statement->rowCount()>0){
            $error="THIS EMAIL ALREADY EXIST !!";
        }else if(trim($_POST["email"])==="" || trim($_POST["password"])==="" || trim($_POST["confirm-pass"])==="" || trim($_POST["name"])===""){
            $error="FIELDS CAN'T BE ONLY SPACES!!";
        }else if ($password !== $confirm_password) {
            $error = "Passwords do not match.";
        }else {

            $_SESSION['eaccount']['name'] = $_POST['name'];
            $_SESSION['eaccount']['email'] = $_POST['email'];
            $_SESSION['eaccount']['password'] = $_POST['password'];
  
            $pdo=null;
            header("Location:OwnerRegisterStep3.php");
        }

        $pdo=null;

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
                
                <form method="post" action="OwnerRegisterStep2.php">
                    <section class="row gap-16 flex-direction-column shadow border-16 py-32 px-32">
                        <h2>Owner Step-2 Registeration</h2>

                        <div class="row width-100">
                            <label for="id">Customer ID:</label>
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                            <input type="text" id="id" name="id" required disabled value="<?php echo $id; ?>">
                        </div>

                        <div class="row width-100">
                            <label for="name">Username:</label>
                            <input type="text" id="name" name="name" value="<?= htmlspecialchars($name) ?>" required class="required">
                        </div>

                        <div class="row width-100">
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required class="required">
                        </div>
                        
                        <div class="row width-100">
                            <label for="pass">Password:</label>
                            <input type="password" id="pass" name="password" required class="required" pattern="^\d.{4,13}[a-z]$">
                        </div>
                        
                        <div class="row width-100">
                            <label for="confirm-pass">Confirm Password:</label>
                            <input type="password" id="confirm-pass" name="confirm-pass" required class="required">
                        </div>

                        <?php if (!empty($error)) echo "<p id='error'>$error</p>"; ?>

                        <button type="submit">Register</button>
                    </section>  
                </form>
            </main>
        </section>
                    
        
        <?php include('../includes/footer.php') ?>
    </body>

</html>