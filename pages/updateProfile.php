<?php
    session_start();
    require_once('../database.inc.php');

    $customer_id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $telephone_number = $_POST['telephone_number'];
    $mobile_number = $_POST['mobile_number'];
    $image = $_FILES['image']['tmp_name'] ?? null;

    $pdo = connect_db();

    try {

        $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
        $stmt->bindValue(':user_id', $customer_id);
        $stmt->execute();
        $current = $stmt->fetch();
        $something_changed = false;

        if($name !== $current['name'] || $email !== $current['email'] || $telephone_number !== $current['telephone_number'] || $mobile_number !== $current['mobile_number'] || ($image && $current['image'] !== basename($image))){
            $something_changed = true;
        }

        if(!$something_changed){
            header("Location: profile.php");
            exit;
        }

        //base query
        $query = "UPDATE users SET name = :name, email = :email, telephone_number = :telephone_number, mobile_number = :mobile_number";

        $update_image = false;
        $new_name = $customer_id . '.jpeg';

        //adding image is optional
        if(isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE){
            if($_FILES['image']['error'] === UPLOAD_ERR_OK && $_FILES['image']['type'] === 'image/jpeg'){
                $query .= ", image = :image";
                $update_image = true;
                move_uploaded_file($_FILES['image']['tmp_name'], '../Images/'.$new_name);
            } else {
                $_SESSION['error'] = "Images must be of type jpeg/jpg only";
                header("Location: profile.php");
                exit;
            }
        }

        $query .= " WHERE user_id = :user_id";
        $stmt = $pdo->prepare($query);

        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':telephone_number', $telephone_number);
        $stmt->bindValue(':mobile_number', $mobile_number);
        $stmt->bindValue(':user_id', $customer_id);
        if($update_image){
            $stmt->bindValue(':image', $new_name);
        }

        $success = $stmt->execute();

        if($success){
            $_SESSION['message'] = "Profile updated successfully!";
            $_SESSION['error'] = '';
        } else {
            $_SESSION['error'] = "Failed to update profile. Please try again.";
        }

    } catch(PDOException $e){
        $_SESSION['error'] = "Database error: " . $e->getMessage();
    }

    $pdo = null;
    header("Location: profile.php");
    exit;
?>