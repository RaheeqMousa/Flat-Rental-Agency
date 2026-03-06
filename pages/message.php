<?php
    session_start();
    if(!isset($index_page)){
        require_once ('../database.inc.php');
    }else{
        require_once ('database.inc.php');
    }
    

    $account_id = $_SESSION['user']['account_id'];

    $pdo=connect_db();
    $query="select * from messages where receiver_account_id = :id order by sent_at desc;";
    $statement = $pdo->prepare($query);
    $statement->bindValue(':id',$account_id);
    $statement->execute();
    $messages = $statement->fetchAll();

    $pdo=null;

    function getTableBody(){
        global $messages;
        global $index_page;
 
        if(!isset($index_page)){
            foreach ($messages as $message){
                echo "<tr id=\"".($message['status'] === 'unread' ? 'unread':'' )."\">";
                    echo "<td>";
                        if ($message['status'] !== 'unread'){
                            echo  "<span>
                                    <img src='../Images/square-check-solid.svg' alt='read icon' title='read icon' width='20' height='20'>
                                </span>";
                        }
                        echo"<a href='view_message.php?id=".$message['message_id']."'>
                                ". $message['title'] ."</a>";
                    echo "</td>";
                    echo "<td>".$message['sent_at'] ."</td>";
                echo "</tr>";
            }
    
        }else if(isset($index_page)){
            foreach ($messages as $message){
                echo "<tr id=\"".($message['status'] === 'unread' ? 'unread':'' )."\">";
                    echo "<td>";

                    if ($message['status'] !== 'unread'){
                        echo  "<span>
                                <img src='Images/square-check-solid.svg' alt='read icon' title='read icon' width='20' height='20'>
                            </span>";
                    }
                    echo"<a href='pages/view_message.php?id=".$message['message_id']."'>
                            ". $message['title'] ."</a>";
                    echo "</td>";
                    echo "<td>".$message['sent_at'] ."</td>";
                echo "</tr>";
            }
        }

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

            <main class="table-area height-70vh">
                    
                    <section>
                        <table>
    <thead>
        <tr>
            <th>Title</th>
            <th>Sent At</th>
        </tr>
    </thead>

    <tbody>
        <?php getTableBody(); ?>
    </tbody>
    
                    
</table>
                    </section>
            </main>

        </section>

        <?php include('../includes/footer.php') ?>

    </body>
</html>


