<?php
    session_start();
    $auth_page=true;

    if(isset($_POST['role'])){
        if($_POST['role']==="customer"){
            header('Location:CustomerRegisterStep1.php');
        }else if($_POST['role']==="owner"){
            header('Location:OwnerRegisterStep1.php');
        }
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
                <form action="register.php" method="post" >
                    <div class="shadow border-16 row gap-16 flex-direction-column py-32 px-32">
                        <h2>Registration</h2>
                        <div class="row width-100">
                            <label for='role'>Choose a Role:</label>
                            <select name='role' id='role'>
                                <option value='customer'>customer</option>
                                <option value='owner'>owner</option>
                            </select>
                        </div>
                    
                        <button type='submit'>Next Step</button>
                        <a href="login.php">Already have an account?</a>
                    </div>
                </form>
            </main>

        </section>
     
        <?php include('../includes/footer.php') ?>

    </body>
</html>


