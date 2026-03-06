<?php
    session_start();
    require_once "../database.inc.php";
    $_SESSION['error']='';

    if(!isset($_POST['location']) || !isset($_POST['rent']) || !isset($_POST['available_date_from']) || !isset($_POST['bedrooms'])
        || !isset($_POST['size']) || !isset($_POST['bathrooms'])  || !isset($_POST['conditions']) || !isset($_POST['start-time']) || !isset($_POST['end-time']) 
        || !isset($_POST['phone'])){

        header('Location:../index.php');
    }

    $days = isset($_POST['available_days']) ? $_POST['available_days'] : [];

    if($_POST['available_date_to']<date('Y-m-d')){
        $_SESSION['error']='available date to MUST be greater than the current date!';
    }

    $pdo=connect_db();

    $query="insert into address(house_no,postal_code,city,street_name) values (:house_no,:postal_code,:city,:street_name);";
    $statement=$pdo->prepare($query);
    $statement->bindValue(':house_no', $_POST['house_number']);
    $statement->bindValue(':postal_code', $_POST['postal_code']);
    $statement->bindValue(':city', $_POST['city']);
    $statement->bindValue(':street_name', $_POST['street_name']);

    $statement->execute();
    $address_id=$pdo->lastInsertId();


    $query="
        INSERT INTO flats (
            owner_id, location, monthly_rental_cost, available_date_from, available_date_to, number_of_bedrooms, number_of_bathrooms, size_in_square_meters,
            furnished, has_heating, has_air_conditioning, has_access_control, parking, backyard,
            playground, storage, rent_conditions, address_id
        ) VALUES (
            :owner_id, :location, :monthly_rental_cost, :available_date_from, :available_date_to, :number_of_bedrooms, :number_of_bathrooms, :size_in_square_meters,
            :furnished, :has_heating, :has_air_conditioning, :has_access_control, :parking, :backyard,
            :playground, :storage, :rent_conditions, :address_id
        );";
    
    $statement=$pdo->prepare($query);

    $statement->bindValue(':owner_id', $_SESSION['user']['user_id']);
    $statement->bindValue(':location', $_POST['location']);
    $statement->bindValue(':monthly_rental_cost', $_POST['rent']);
    $statement->bindValue(':available_date_from', $_POST['available_date_from']);
    $statement->bindValue(':available_date_to', $_POST['available_date_to']);
    $statement->bindValue(':number_of_bedrooms', $_POST['bedrooms']);
    $statement->bindValue(':number_of_bathrooms', $_POST['bathrooms']);
    $statement->bindValue(':size_in_square_meters', $_POST['size']);
    $statement->bindValue(':rent_conditions', $_POST['conditions']);
    $statement->bindValue(':has_heating', isset($_POST['heating']) ? 1 : 0);
    $statement->bindValue(':has_air_conditioning', isset($_POST['air_condition']) ? 1 : 0);
    $statement->bindValue(':has_access_control', isset($_POST['access_control']) ? 1 : 0);
    $statement->bindValue(':furnished', isset($_POST['furnished']) ? 1 : 0);
    $statement->bindValue(':parking', isset($_POST['parking']) ? 1 : 0);
    $statement->bindValue(':backyard', isset($_POST['backyard']) ? $_POST['backyard']:'none');
    $statement->bindValue(':playground', isset($_POST['playground']) ? 1 : 0);
    $statement->bindValue(':storage', isset($_POST['storage']) ? 1 : 0);
    $statement->bindValue(':address_id', $address_id);

    $statement->execute();

    $last_flat_id=$pdo->lastInsertId();

    $statement = $pdo->prepare("insert into flat_images (flat_id, image_path) values (:flat_id, :image_path);");
    $uploaded = 0;

    try{
        foreach ($_FILES['images']['tmp_name'] as $index => $tmpPath) {
            $_SESSION['error'] = $uploaded;
            $fileName= basename($_FILES['images']['name'][$index]);
            $ext= strtolower(pathinfo($fileName,PATHINFO_EXTENSION));
            $new_name=$last_flat_id.'_'.$uploaded.'.'. $ext;

            if (move_uploaded_file($tmpPath, "../Images/".$new_name)) {
                $statement->bindValue(':flat_id',$last_flat_id);
                $statement->bindValue(':image_path',$new_name);
                $statement->execute();
                $uploaded++;
            }
        }
    }catch (Exception $e) {
        echo "error is flat images";
    }

    if($uploaded<3){
        $_SESSION['error'] = "Please upload at least 3 images!!";
        header("Location:OfferFlat.php");
    }

    $query="insert into flat_preview (flat_id, preview_day, preview_time_start, preview_time_end, phone)
                    values (:flat_id, :d, :time_from, :time_to, :phone);";
    
    foreach($days as $day){
        $statement = $pdo->prepare($query);
        $statement->bindValue(':flat_id',$last_flat_id);
        $statement->bindValue(':d',$day);
        $statement->bindValue(':time_from',$_POST['start-time']);
        $statement->bindValue(':time_to',$_POST['end-time']);
        $statement->bindValue(':phone',$_POST['phone']);
        $statement->execute();
    }
    
    
    if (isset($_POST['title']) || isset($_POST['description']) || isset($_POST['page_url']) ) {

        $title = $_POST['title'] ?? null;
        $desc  = $_POST['description']  ?? null;
        $url   = $_POST['page_url']   ?? null;
    
        $query = "insert into marketing_info(flat_id, title, description, url) 
                  values (:id, :title, :desc, :url)";
        
        $statement = $pdo->prepare($query);
        $statement->bindValue(':id', $last_flat_id);
        $statement->bindValue(':title', $title);
        $statement->bindValue(':desc', $desc);
        $statement->bindValue(':url', $url);
        
        $statement->execute();
    }
    
    
    $query = "insert into messages (sender_account_id, receiver_account_id, title, message_body, status, confirm) values 
                (:sender,:receiver, :title, :mes, :status,:confirm);";

    $statement = $pdo->prepare($query);
    $statement->bindValue(':title',"New flat pending approval");
    $statement->bindValue(':mes',"Flat ID $last_flat_id requires approval.");
    $statement->bindValue(':receiver','3');
    $statement->bindValue(':sender',$_SESSION['user']['account_id']);
    $statement->bindValue(':confirm',$last_flat_id);
    $statement->bindValue(':status','unread');
    $statement->execute();

    $_SESSION['error']='';
    echo "Flat submitted successfully! Waiting for manager approval.";
    $_SESSION['error']='';

    $pdo=null;
?>
