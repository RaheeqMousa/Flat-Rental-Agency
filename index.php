<?php
    session_start();
    $index_page=true;
    include('includes/header.php');
    include_once('database.inc.php');

    $user = $_SESSION['user']['account_id'] ?? null;
    $role = $_SESSION['user']['role'] ?? null;

    $pdo = connect_db();
    if($user && $role == 'customer'){
      $sql = "SELECT flat_id, refference_number, location, monthly_rental_cost, 
              number_of_bedrooms, number_of_bathrooms, size_in_square_meters, 
              created_at, is_approved
              FROM flats 
              WHERE is_approved = 1 AND is_rented = 0
              ORDER BY created_at DESC 
              LIMIT 6";
                      
      $stmt = $pdo->prepare($sql);
      $stmt->execute();
      $flats = $stmt->fetchAll();
      $pdo = null;
    }

    
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>3lRaheeq agency</title>
    <link href="./main.css" rel="stylesheet">
</head>

<body>


    <section class="page-navigation-main">
        <?php include('includes/leftSideNavigation.php')?>       
    
        <main class="">
            <!-- Recently Added Flats Section -->
            <section class="recently-added-section height-70vh">              
                <?php
                    if((!$role || $role=='customer')) {
                      echo "<div class='section-header'>
                                <h2><img alt='' title='bed' src='./Images/house-black.svg' width=50 height=50/> Recently Added Flats</h2>
                                <p class='section-subtitle'>Discover our newest listings</p>
                            </div>";

                      if(!empty($flats)){
                        echo '<div class="flats-grid">';
                        
                        foreach($flats as $flat) {
                            // Get first image
                            $pdo = connect_db();
                            $imgSql = "SELECT image_path FROM flat_images WHERE flat_id = :flat_id LIMIT 1";
                            $imgStmt = $pdo->prepare($imgSql);
                            $imgStmt->bindValue(':flat_id', $flat['flat_id']);

                            $imgStmt->execute();
                            $image = $imgStmt->fetch();
                            $imagePath = $image ? trim($image['image_path']) : 'placeholder.jpg';
                            $imageName=pathinfo($imagePath, PATHINFO_FILENAME);
                            $pdo = null;

                            $daysAgo = date_diff(date_create($flat['created_at']), date_create(date('Y-m-d')))->format('%a');
                            $isNew = $daysAgo < 7 ? 'NEW' : '';
                            
                            echo "
                            <div class='flat-card'>
                                <div class='flat-image-container'>
                                    <img src='./Images/$imageName.jpg' alt='{$flat['refference_number']}' class='flat-image'>
                                    <div class='flat-badges'>
                                        <span class='price-badge'>\${$flat['monthly_rental_cost']}/mo</span>
                                        " . ($isNew ? "<span class='new-badge'>$isNew</span>" : "") . "
                                    </div>
                                </div>
                                
                                <div class='flat-info'>
                                    <h3>{$flat['refference_number']}</h3>
                                    <p class='location'>" . htmlspecialchars($flat['location']) . "</p>
                                    
                                    <div class='flat-features row justify-content-around'>
                                        <div class='feature'>
                                            <img alt='' title='bed' src='./Images/bed.svg' width=30 height=30/>
                                            <span>{$flat['number_of_bedrooms']} Beds</span>
                                        </div>
                                        <div class='feature'>
                                            <img alt='' title='bath' src='./Images/bathrooms.svg' width=30 height=30 />
                                            <span>{$flat['number_of_bathrooms']} Baths</span>
                                        </div>
                                        <div class='feature'>
                                            <img alt='' title='area' src='./Images/size.svg' width=30 height=30 />
                                            <span>{$flat['size_in_square_meters']} m²</span>
                                        </div>
                                    </div>
                                    
                                    <a href='pages/FlatDetails.php?id={$flat['flat_id']}' class='view-btn'>View Details →</a>
                                </div>
                            </div>
                            ";
                        }
                        
                        echo '</div>';
                      } else {
                        echo "
                        <div class='row justify-content-center'>
                          <p class='no-flats-message'>No flats available at the moment. Please
                          check back later.</p>
                         </div>";
                      }
                    } else if($role == 'owner') {
                        echo "
                          <div class='row justify-content-center gap-16 height-70vh flex-direction-column'>
                              <img src='./Images/owner.png' alt='Owner Dashboard' width='150' height='150'>
                              <h2>Welcome Owner</h2>
                              <p>Manage your flats, add new properties, and track rental requests.</p>
                          </div>
                        ";
                    }else{
                        echo "
                          <div class='row justify-content-center gap-16 height-70vh flex-direction-column'>
                              <img src='Images/manager.png' alt='Manager Dashboard'>
                              <h2>Welcome Manager</h2>
                              <p>Review property listings, approve flats, and manage the platform.</p>
                          </div>
                          ";
                    }
                ?>
            </section>
        </main>
    </section>



    <?php include('includes/footer.php') ?>

</body>

</html>