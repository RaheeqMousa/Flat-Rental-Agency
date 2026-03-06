<?php
    session_start();
    require_once '../database.inc.php';

    $flat_id = $_GET['id'] ?? '';
    $pdo= connect_db();

    if($_SERVER['REQUEST_METHOD']==='POST'){
        $flat_id=$_POST['flat_id'];
    }

    $error='';

    if (!isset($_SESSION['user']['account_id']) || !isset($_SESSION['user']['user_id'])) {
        header("Location:../auth/login.php");
    }

    $query="
        select 
        f.flat_id as flat_id ,
        f.refference_number as refference_number,
        f.monthly_rental_cost as monthly_rent,
        f.available_date_from as available_from,
        f.available_date_to as available_to,
        f.location as location,
    
        flat_address.street_name as street, 
        flat_address.city as city, 
        flat_address.postal_code as postal_code,
        flat_address.house_no as house_number,

        owner.user_id as owner_id, 
        owner.name as owner_name, 
        owner.mobile_number as mobile_number,
        
        owner_address.street_name as owner_street, 
        owner_address.city as owner_city, 
        owner_address.postal_code as owner_postal_code,
        owner_address.house_no as owner_house_number,

        acc.account_id as owner_account_id

        from flats f
        join address flat_address on flat_address.address_id=f.address_id
        join users owner on f.owner_id = owner.user_id
        join address owner_address on owner.address_id = owner_address.address_id
        join accounts acc on acc.user_id=owner.user_id
        where f.flat_id = :flat_id;
    ";

    $statement=$pdo->prepare($query);
    $statement->bindValue(':flat_id',$flat_id);
    $statement->execute();
    $flat=$statement->fetch();

    if(isset($_POST['rent-end']) && isset($_POST['rent-start'])){
        $flat_id = $_POST['flat_id'];
        $start = $_POST['rent-start'];
        $end = $_POST['rent-end'];

        echo $end;
        echo $flat['available_to']." ....." ;

        if($start>=$flat['available_from'] && $end<=$flat['available_to']){
            $months= ceil((strtotime($end)- strtotime($start)) / (30* 24*60*60));
            $total_cost = $months * $flat['monthly_rent'];

            $statement = $pdo->prepare("
                insert into rentals(flat_id, customer_id, start_date, end_date, total_amount)
                values (:flat_id, :customer_id, :start_date, :end_date, :total)
            ");

        
            $statement->bindValue(':flat_id',$flat_id);
            $statement->bindValue(':customer_id',$_SESSION['user']['user_id']);
            $statement->bindValue(':start_date',$start);
            $statement->bindValue(':end_date',$end);
            $statement->bindValue(':total',$total_cost);
            $statement->execute();
            $_SESSION['rent_id']=$pdo->lastInsertId();

            $_SESSION['rent_data']['form']=[
            'rent_id'=>$flat_id,
            'owner_id' =>$flat['owner_id'],
            'owner_name' =>$flat['owner_name'],
            'owner_account_id' =>$flat['owner_account_id'],
            'owner_mobile' =>$flat['mobile_number'],
            'refference_number'=> $flat['refference_number'],
            'flat_id'=> $flat['flat_id'],
            'months'=> $months,
            'total_amount' => $total_cost
            ];
            header("Location:ConfirmRent.php");
        }else{
            $error='choose a valid rent start and end!';
        }

    }

    $pdo=null;
?>

<!DOCTYPE html>
<html>
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
                <form method="post" action="rentFlat.php">
                    <h2>Rent Flat</h2>
                    <input type="hidden" id="flat-id" name="flat_id" value="<?php echo $flat['flat_id'] ?>">

                    <section>
                       <label for="flat-ref">Reference Number:</label>
                       <input type="text" id="flat-ref" value="<?php echo $flat['refference_number'] ?>" disabled> 
                    </section>

                    <section>
                       <label for="available-from">Available from:</label>
                       <input type="text" id="available-from" value="<?php echo $flat['available_from'] ?>" disabled> 
                    </section>
                    <section>
                       <label for="available-to">Available To:</label>
                       <input type="text" id="available-to" value="<?php echo $flat['available_to'] ?>" disabled> 
                    </section>

                    <section>
                       <label for="refference_number">Reference Number:</label>
                       <input type="hidden" id="refference_number" name="refference_number" value="<?php echo $flat['refference_number'] ?>" >
                       <input type="text" id="refference_number" value="<?php echo $flat['refference_number'] ?>" disabled> 
                    </section>
                    
                    <section>
                        <label for="location">Location: </label>
                        <input type="text" id="location" value="<?php echo $flat['location'] ?>" disabled>
                    </section>
                    
                    <section>
                        <label for="flat-address">Flat Address:</label>
                        <input type="text" id='flat-address'
                            value="<?php echo $flat['house_number'] . ', ' .$flat['street'] . ', ' . $flat['city'] . ', ' . $flat['postal_code']; ?>" 
                            disabled>
                    </section>

                    <section>
                        <label for="owner-id">Owner ID: </label>
                        <input type="text" id='owner-id' value="<?php echo $flat['owner_id'] ?>" disabled>
                    </section>

                    <section>
                        <label for="owner-name">Owner Name: </label>
                        <input type="text" id="owner-name" value="<?php echo $flat['owner_name'] ?>" disabled>
                    </section>

                    <section>
                        <label for="owner-mobile">Owner mobile: </label>
                        <input type="text" id="owner-mobile" value="<?php echo $flat['mobile_number'] ?>" disabled>
                    </section>

                    <section>
                        <label for="owner-address">Owner Address:</label>
                        <input type="text" id="owner-address"
                            value="<?php echo $flat['owner_house_number'] . ', '. $flat['owner_street'] . ', ' . $flat['owner_city'] . ', ' . $flat['owner_postal_code'] ?>" 
                            disabled>
                    </section>
                    
                    <section>
                        <label for="rent-start">Rental Start Date: </label>
                        <input type="date" name="rent-start" id="rent-start" required class="required">
                    </section>
                    
                    <section>
                        <label for="rent-end">Rental End Date: </label>
                        <input type="date" name="rent-end" id="rent-end" required class="required">
                    </section>
                    
                    <p class="error"><?php echo $error?></p>
                    <button type="submit">Continue</button>
                </form>
            </main>

        </section>


        <?php include('../includes/footer.php') ?>

    </body>
</html>