<?php
    session_start();
    $auth_page=true;
    
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>3lRaheeq agency</title>
        <link href="../main.css" rel="stylesheet">
    </head>
    <body>

        <?php include('../includes/header.php') ?>

        <section class="page-navigation-main">
            <?php include('../includes/leftSideNavigation.php') ?>

            <main class="forms-style">
                <form action="./login_process.php" method="post">
                    <div class="shadow border-16 row gap-16 flex-direction-column py-32 px-32">
                        <h2 >Login</h2>
                        <div class="row flex-direction-column gap-16">
                            <section class="row gap-8">
                                <label for="name">Username</label>
                                <input type="text" id="name" name="name" required class="required" placeholder="Enter Username">
                            </section>
                            
                            <section class="row gap-8">
                                <label for="pass">Password</label>
                                <input id="pass" name="pass" type="password" required class="required" placeholder="Enter Password">
                            </section>

                            <p id="error">
                                <?php echo $_SESSION['error'] ?? ''; ?>
                            </p>
                        </div>

                        <button type="submit">Login</button>
                        <a href="./register.php">Don't have an account?</a>
                    </div>
                </form>
            </main>

        </section>

        
        <?php include('../includes/footer.php') ?>

    </body>
</html>