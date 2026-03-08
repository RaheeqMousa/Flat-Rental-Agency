<?php
    session_start();
    require_once ('../database.inc.php');

    $pdo=connect_db();
    $customer_id = $_SESSION['user']['user_id'];

    $query="select * from users where user_id = :user_id;";
    $statement=$pdo->prepare($query);
    $statement->bindValue(':user_id',$customer_id);
    $statement->execute();
    $customer=$statement->fetch();
    $pdo=null;

    $is_disabled_fields = "";
    if($_SESSION['user']['role'] !== 'customer'){
        $is_disabled_fields = "disabled";
    }

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
                <form method="post" class="row flex-direction-column gap-16" action="updateProfile.php" enctype="multipart/form-data">
                    <div class="row justify-content-start py-32 px-16 shadow border-16 width-100 box-sizing-border-box">
                        <section>
                            <?php   
                                if($customer['image']) 
                                    echo "<img src='../Images/".$customer['image']."' width='180' height='130' alt='User Photo' title='". $customer['name'] ."'s image >"; 
                                else
                                    echo "<img src='../Images/profiles.jpg' width='60' height='60' alt='User Photo' title='". $customer['name'] ."'s image >"; 
                            ?> 
                        </section>
                        
                        <section class="row">
                            <label for="image">Upload Photo:</label>
                            <input type="file" id="image" name="image">
                        </section>
                    </div>
                    <div class="row gap-16 width-100">
                        <div class="row justify-content-center flex-direction-column py-32 px-16 shadow border-16 gap-16">
                            <section class="row width-100">
                                <label for="cid">Customer ID</label>
                                <input type="number" id="cid" disabled value="<?php echo $customer['user_id'] ?>">
                                <input type="hidden" name="id" value="<?php echo $customer['user_id'] ?>">
                            </section>
                            <section class="row width-100">
                                <label for="name">Name</label>
                                <input placeholder="Enter new name" id="name" name="name" pattern="^[A-Za-z]+$" type="text" value="<?php echo $customer['name']; ?>" <?php echo $is_disabled_fields; ?>>
                            </section>
                            <section class="row width-100">
                                <label for="email">Email</label>
                                <input placeholder="Enter new email" id="email" type="email" name="email" value="<?php echo $customer['email'] ?>"
                                <?php echo $is_disabled_fields; ?>>
                            </section>
                        </div>
                        <div class="row justify-content-center flex-direction-column py-32 px-16 shadow border-16 gap-16">
                            <section class="row width-100">
                                <label for="telephone_number">Telephone number</label>
                                <input placeholder="Enter new telephone numebr" id="telephone_number" name="telephone_number" type="number" value="<?php echo $customer['telephone_number'] ?>"
                                <?php echo $is_disabled_fields; ?>>
                            </section>
                            <section class="row width-100">
                                <label for="mobile_number">Mobile number</label>
                                <input placeholder="Enter new mobile number" id="mobile_number" name="mobile_number" type="number" value="<?php echo $customer['mobile_number'] ?>"
                                <?php echo $is_disabled_fields; ?>
                                >
                            </section>
                        </div>
                        
                    </div>

                    <button <?php echo $is_disabled_fields;  ?> class="<?php echo $is_disabled_fields ? 'disabled-button' : ''  ?>" type='submit'>Update Profile</button>
                      
                </form>
            </main>


        </section>

        <?php
            if(!empty($_SESSION['message'])){
                echo "<div class='toast toast-success display-none'>
                        <p>".$_SESSION['message']."</p>
                    </div>";
                unset($_SESSION['message']);
            }else if(!empty($_SESSION['error'])){
                echo "
                    <div class='toast toast-error display-none'>
                        <p>".$_SESSION['error']."</p>
                    </div>
                ";
                unset($_SESSION['error']);
            }
        ?>

        <?php include('../includes/footer.php') ?>

        <script src="../main.js"></script>

    </body>
</html>


