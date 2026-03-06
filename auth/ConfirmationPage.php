<?php
    session_start();
    $auth_page=true;

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
            <main class="height-70vh row justify-content-center width-100">    
                <section class="row flex-direction-column gap-8 shadow border-16 py-64 px-32">   
                    <h2 class="mb-16">Registration is Successful !</h2>
                    <p>Welcome, <strong><?php echo $_SESSION['register']['username']; ?></strong>.</p>
                    <p>Your ID is: <strong><?php echo $_SESSION['register']['id']; ?></strong></p>
                    <p>Please save your ID.</p>
                    <a href="login.php">Go login</a>
                </section>
            </main>
        </section>
                    
        
        <?php include('../includes/footer.php') ?>
    </body>

</html>