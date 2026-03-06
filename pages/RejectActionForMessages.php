<?php
    session_start();
    require_once ('../database.inc.php');

    $confirmation_id = $_POST['id'] ?? null;
    $title = $_POST['title'] ?? null;

    if (!$title || !$confirmation_id) {
        header('Location:../index.html');
    }

    $pdo=connect_db();

    if ($title === 'New flat pending approval') {
        
        $query="update flats set is_approved =0 WHERE flat_id = :id;";
        $statement = $pdo->prepare($query);
        $statement->bindValue(':id',$confirmation_id);
        $statement->execute();
        
        //get owner account id
        $query="select a.account_id
                from flats f
                join accounts a on a.user_id = f.owner_id
                where f.flat_id=:id;
                ";
        $statement=$pdo->prepare($query);
        $statement->bindValue(':id',$confirmation_id);
        $statement->execute();
        $owner=$statement->fetch();
        
        //send rejection message to the owner
        $query = "insert into messages (receiver_account_id, sender_account_id, title, message_body, status) values 
                    (:receiver, :sender, :title, :mes, 'unread');";
        $statement = $pdo->prepare($query);
    
    
        $statement->bindValue(':receiver',$owner['account_id']);
        $statement->bindValue(':sender',3);
        $statement->bindValue(':title','Rent offer Rejected');
        $statement->bindValue(':mes',"A flat Rent Offer has been Rejected by the manager");
        $statement->execute();
    }

    if ($title === 'Rented Flat') {
        $ids=explode(' ',$confirmation_id);
        $renal_id=$ids[0];
        $flat_id=$ids[1];
        $rev_acc_id=$ids[2];
        
        $query="update flats set is_rented = :rent WHERE flat_id = :flat_id;";
        $statement=$pdo->prepare($query);
        $statement->bindValue(':flat_id',$flat_id);
        $statement->bindValue(':rent',1);
        $statement->execute();
    
        $query="update rentals set is_approved= :is_approved WHERE rental_id = :rental_id;";
        $statement=$pdo->prepare($query);
        $statement->bindValue(':rental_id',$renal_id);
        $statement->bindValue(':is_approved',1);
        $statement->execute();
        
        $query = "insert into messages (receiver_account_id, sender_account_id, title, message_body, status) values 
                    (:receiver, :sender, :title, :mes, 'unread');";
        $statement = $pdo->prepare($query);
    
        $statement->bindValue(':receiver',$rev_acc_id);
        $statement->bindValue(':sender',$_SESSION['user']['account_id']);
        $statement->bindValue(':title','Rental Rejected');
        $statement->bindValue(':mes',"Sorry your rental has been rejected");
        $statement->execute();
        
    }

    if ($title === 'preview request') {
        
        $ids=explode(' ',$confirmation_id);
        $prev_requ_id=$ids[0];
        $customer_account_id=$ids[1];
        $owner_id=$ids[2];
        
        //make the status of the prev request rejected
        $query="update preview_requests set status = 'rejected' WHERE id = :id;";
        $statement = $pdo->prepare($query);
        $statement->bindValue(':id',$prev_requ_id);
        $statement->execute();
        
        //get flat requested appointment ref numebr
        $query="select f.refference_number from preview_requests s 
            join flats f on f.flat_id=s.flat_id
            WHERE preview_id = :id;";
        $statement = $pdo->prepare($query);
        $statement->bindValue(':id',$prev_requ_id);
        $statement->execute();
        $flat=$statement->fetch();
        
        //get owner info
        $query="select s.name as name, s.mobile_number as mobile_number from users s where user_id=:id;";
        $statement=$pdo->prepare($query);
        $statement->bindValue(':id',$owner_id);
        $statement->execute();
        $user=$statement->fetch();
    
        //send reject message to the customer
        $query = "insert into messages (receiver_account_id, title, message_body, status) values 
                    (:receiver, :title, :mes, 'unread');";
        $statement = $pdo->prepare($query);
    
        $statement->bindValue(':receiver',$customer_account_id);
        $statement->bindValue(':title','Preview Rejected');
        $statement->bindValue(':mes',"Sorry your preview request for flat ".$flat['refference_number']." has been rejected by ".$user['name']." mobile number ".$user['mobile_number']);
        $statement->execute();
        
        
    }

    echo "<h3>REQUEST REJECTED SUCCESSFULLY</h3>";

    $pdo=null;
?>