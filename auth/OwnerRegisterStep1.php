<?php
    session_start();
    $auth_page=true;

    //if regestration is succes then redirect to step2
    require_once('../database.inc.php');
    $message="";


    function generate_id(){
        do {
            $user_id = rand(100000000,999999999);

            $pdo=connect_db();
            $statement = $pdo->prepare("select count(*) from users where user_id=:id;");
            $statement->bindValue(':id',$user_id);
            $statement->execute();
            $exists = $statement->fetchColumn();
            $pdo=null;
        } while ($exists > 0);

        return $user_id;
    }

    if(isset($_POST['National_ID']) && isset($_POST['name']) && isset($_POST['dob']) && isset($_POST['house_number']) && isset($_POST['street_name']) && isset($_POST['city']) && isset($_POST['postal_code']) 
    && isset($_POST['mobile_number']) &&  isset($_POST['tel_number']) && isset($_POST['email']) && isset($_POST['branchName']) && isset($_POST['bankName']) && isset($_POST['AccountNumber'])  ){

            $pdo = connect_db();

            $query = "SELECT COUNT(*) FROM users WHERE national_id = :national_id";
            $stmt = $pdo->prepare($query);
            $stmt->bindValue(':national_id', $_POST['National_ID']);
            $stmt->execute();

            if ($stmt->fetchColumn() > 0) {

                $message = "National ID already exists.";

                $pdo = null;
            }else{
            
                $id=generate_id();

                $_SESSION['register']['id']=$id;
                $_SESSION['register']['role']='owner';
                $_SESSION['register']['national_id']=$_POST['National_ID'];
                $_SESSION['register']['date_of_birth']=$_POST['dob'];
                $_SESSION['register']['username']=$_POST['name'];
                $_SESSION['register']['house_no']=$_POST['house_number'];
                $_SESSION['register']['street_name']=$_POST['street_name'];
                $_SESSION['register']['city']=$_POST['city'];
                $_SESSION['register']['postal_code']=$_POST['postal_code'];
                $_SESSION['register']['mobile_number']=$_POST['mobile_number'];
                $_SESSION['register']['telephone_number']=$_POST['tel_number'];
                $_SESSION['register']['email']=$_POST['email'];
                $_SESSION['register']['AccountNumber']=$_POST['AccountNumber'];
                $_SESSION['register']['branchName']=$_POST['branchName'];
                $_SESSION['register']['bankName']=$_POST['bankName'];

                if($_FILES['image']['error']===UPLOAD_ERR_OK && $_FILES["photo"]["type"] === "image/jpeg"){
                    $_SESSION['register']['image']=$_FILES['image']['tmp_name'];
                }else if($_FILES['image']['error']===UPLOAD_ERR_OK && $_FILES["photo"]["type"] !== "image/jpeg"){
                    $message="The Image must be jpeg/jpg type !!";
                }

                $pdo=null;

                header('Location:OwnerRegisterStep2.php');
            }
    }else if( (isset($_POST['National_ID']) && trim($_POST['National_ID'])==="") || (isset($_POST['name']) && trim($_POST['name'])==="") 
                || (isset($_POST['tel_number']) && trim($_POST['tel_number'])==="") || (isset($_POST['city']) && trim($_POST['city'])==="") 
                ||  (isset($_POST['mobile_number'])&& trim($_POST['tel_number'])==="" )|| (isset($_POST['postal_code']) && trim($_POST['postal_code'])==="")
                || (isset($_POST['street_name'])&& trim($_POST['street_name'])==="" )|| (isset($_POST['house_number']) && trim($_POST['house_number'])==="")
                || (isset($_POST['branchName']) && trim($_POST['branchName'])==="" ) || (isset($_POST['bankName']) && trim($_POST['bankName'])==="" ) 
                || (isset($_POST['AccountNumber'])&& trim($_POST['AccountNumber'])==="" )) {

        $message="The fields cannot be empty or only spaces !!";
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
                
            <?php
                $form= $_POST??[];
                $form=array_merge($form, $_SESSION['register']??[]);
            ?>
                <form method="post" action="OwnerRegisterStep1.php" enctype="multipart/form-data" class="row gap-16">
                    <h2>Owner Step-1 Registeration (Personal Information)</h2>
                    <div class="row gap-32 justify-content-center">
                        <section class="row flex-direction-column gap-16 shadow border-16 py-32 px-16">
                            <h3>Personal Information</h3>
                            <div class="row width-100">
                                <label for="nationalID">National ID:</label>
                                <input type="text" id="nationalID" required class="required" name="National_ID" placeholder="Enter National ID" value="<?php echo htmlspecialchars($form['National_ID'] ?? ''); ?>">  
                            </div>    
                    
                            <div class="row width-100">
                                <label for="name">Name:</label>
                                <input id="name" name="name" required class="required" type="text" pattern="^[A-Za-z]+$" placeholder="Enter your Username" value="<?php echo htmlspecialchars($form['username'] ?? ''); ?>">
                            </div>
                        
                            <div class="row width-100">
                                <label for="dob">Date Of Birth:</label>
                                <input id="dob" name="dob" required class="required" type="date" placeholder="Enter your DOB" value="<?php echo htmlspecialchars($form['date_of_birth'] ?? ''); ?>">
                            </div>
                            <div class="row width-100">
                                <label for="image">Image:</label>
                                <input id="image" name="image" type="file" placeholder="Enter your Image">
                            </div>
                        </section>
                        <section class="row flex-direction-column gap-16 shadow border-16 py-32 px-16">
                            <h3>Address Information</h3>
                    <div class="row width-100">
                        <label for="house_number">House Number:</label>
                        <input id="house_number" name="house_number" required class="required" type="text" placeholder="Enter House Number" value="<?php echo htmlspecialchars($form['house_no'] ?? ''); ?>">
                    </div>
                  
                    <div class="row width-100">
                        <label for="street_name">Street Name:</label>
                        <input id="street_name" name="street_name" required class="required" placeholder="Enter street name" value="<?php echo htmlspecialchars($form['street_name'] ?? ''); ?>">
</div>
                 
                    <div class="row width-100">
                        <label for="city">City:</label>
                        <input id="city" name="city" required class="required" type="text" placeholder="Enter city" value="<?php echo htmlspecialchars($form['city'] ?? ''); ?>">
</div>
             
                    <div class="row width-100">
                        <label for="postal_code">Postal Code:</label>
                        <input id="postal_code" name="postal_code" required class="required" type="text" placeholder="Enter Postal Code" value="<?php echo htmlspecialchars($form['postal_code'] ?? ''); ?>">
                    </div>
                    </section>
                    <section class="row flex-direction-column gap-16 shadow border-16 py-32 px-16">
                            <h3>Connect Information</h3>           
                        <div class="row width-100">
                            <label for="mobile_number">Mobile number:</label>
                            <input id="mobile_number" name="mobile_number" required class="required" type="text" placeholder="Enter Mobile Number" value="<?php echo htmlspecialchars($form['mobile_number'] ?? ''); ?>">
                        </div>
                    
                        <div class="row width-100">
                            <label for="tel_number">telephone number:</label>
                            <input id="tel_number" name="tel_number" required class="required" type="text" placeholder="Enter Telephone Number" value="<?php echo htmlspecialchars($form['telephone_number'] ?? ''); ?>">
                        </div>
                    
                        <div class="row width-100">
                            <label for="email">Email:</label>
                            <input type="email" name="email" id="email" required class="required" placeholder="Enter email address" value="<?php echo htmlspecialchars($form['email'] ?? ''); ?>">
                        </div>     
                    </section>
                    <section class="row flex-direction-column gap-16 shadow border-16 py-32 px-16">
                            <h3>Bank Account Information</h3>
                 
                        <div class="row width-100">
                            <label for="bankName">Bank Name:</label>
                            <input id="bankName" name="bankName" type="text" required class="required" placeholder="Enter your Bank Name">
                        </div>
                    
                        <div class="row width-100">
                            <label for="branchName">Bank Branch:</label>
                            <input id="branchName" name="branchName" type="text" required class="required" placeholder="Enter your Bank Branch">
                        </div>
                
                        <div class="row width-100">
                            <label for="AccountNumber">Account Number:</label>
                            <input id="AccountNumber" name="AccountNumber" type="text" required class="required" placeholder="Enter your Account Number">
                        </div>
                    </section>         
                    <!-- Display message -->
                    
                </div>
                    <button type="submit">Next Step</button>
                </form>
            </main>
        </section>
        
        <?php
            if(!empty($message)){
                echo "<div class='toast toast-error display-none'>
                        <p>".$message."</p>
                    </div>";
            }
        ?>
       <?php include('../includes/footer.php') ?>
       <script src="../main.js"></script>
    </body>

</html>