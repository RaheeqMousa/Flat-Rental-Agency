<?php
    session_start();
    $error= isset($_SESSION['error']) ? $_SESSION['error']: '';

?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>3lRaheeq agency</title>
        <link rel="stylesheet" href="../main.css">
    </head>

    <body>

        <?php include('../includes/header.php') ?>
    
        <section class="page-navigation-main">

            <?php include('../includes/leftSideNavigation.php') ?>
            <main class="forms-style">

                <form action="offerFlatSubmit.php" method="post" enctype="multipart/form-data">
                    <?php echo "<p id='error'>". $_SESSION['error'] ?? ''."</p>" ?>
                    <br>

                    <fieldset>
                        <legend>Flat Address</legend>
                        <section>
                            <label for="house_number">House Number:</label>
                            <input id="house_number" name="house_number" required class="required" type="text" placeholder="Enter House Number">
                        </section>
                        <br>
                        <section>
                            <label for="street_name">Street Name:</label>
                            <input id="street_name" name="street_name" required class="required" placeholder="Enter street name">
                        </section>
                        <br>
                        <section>
                            <label for="city">City:</label>
                            <input id="city" name="city" required class="required" type="text" placeholder="Enter City">
                        </section>
                        <br>
                        <section>
                            <label for="postal_code">Postal Code:</label>
                            <input id="postal_code" name="postal_code" required class="required" type="text" placeholder="Enter Postal Code">
                        </section>
                    </fieldset>

                    <fieldset>
                        <legend>Flat Details</legend>

                        <section>
                            <label for="location">Location:</label>
                            <input type="text" name="location" id="location" required>
                        </section>
                        
                        <section>
                            <label for="rent">Rent/Month:</label>
                            <input type="number" name="rent" id="rent" required class="required">
                        </section>
                        
                        <section>
                            <label for="available_date_from">Date Available From:</label>
                            <input type="date" name="available_date_from" id="available_date_from" required class="required">
                        </section>
                        

                        <section>
                            <label for="available_date_to">Date Available To:</label>
                            <input type="date" name="available_date_to" id="available_date_to" required class="required">
                        </section>

                        <section>
                            <label for="bedrooms">Bedrooms:</label>
                            <input type="number" name="bedrooms" id="bedrooms" min="1" required class="required">
                        </section>
                        <section>
                            <label for="bathrooms">Bathrooms:</label>
                            <input type="number" name="bathrooms" id="bathrooms" min="1" required class="required">
                        </section>
                        <section>
                            <label for="size">Size (sqm):</label>
                            <input type="number" name="size" id="size" required class="required">
                        </section>
                        <section>
                            <label for="conditions">Conditions:</label>
                            <textarea name="conditions" id="conditions" ></textarea>
                        </section>

                        <section>
                            <section>
                                <label for="heating">Heating System</label>
                                <input type="checkbox" name="heating" id="heating">
                            </section>
                            
                            <section>
                                <label for="air_condition">Air Conditioning</label>
                                <input type="checkbox" name="air_condition" id="air_condition">
                            </section>

                            <section>
                                <label for="access_control">Access Control</label><br>
                                <input type="checkbox" name="access_control" id="access_control"> 
                            </section>   
                        </section>
                        
                        <section>
                            <p>Features:</p>
                            <section>
                                <label for="parking">Car Parking</label>
                                <input type="checkbox" name="parking" id="parking" value="parking">
                            </section>

                            <section>
                                <label>Backyard</label>
                                <section>
                                    <label for="backyard_individual">Backyard (Individual)</label>
                                    <input type="radio" name="backyard" id="backyard_individual" value="individual">
                                </section>
    
                                <section>
                                    <label for="backyard_shared">Backyard (Shared)</label>
                                    <input type="radio" name="backyard" id="backyard_shared" value="shared">
                                </section>
                            </section>

                            <section>
                                <label for="playground">Playground</label>
                                <input type="checkbox" name="playground" id="playground" value="playground"> 
                            </section>

                            <section>
                                <label for="furnished">furnished</label>
                                <input type="checkbox" name="furnished" id="furnished" value="furnished">
                            </section>

                            <section>
                                <label for="storage">Storage</label><br>
                                <input type="checkbox" name="feature" id="storage" value="storage">
                            </section>         

                            <section>
                                <label for="images">Flat Photos (at least 3):</label>
                                <input type="file" name="images[]" id="images" multiple accept="image/*" required class="required">
                            </section>
                        </section>
                         
                    </fieldset>

                    <fieldset>
                        <legend>Marketing Information (Optional)</legend>
                        <section>
                            <label for="title">Title:</label>
                            <input type="text" name="title" id="title">
                        </section>
                        

                        <section>
                            <label for="description">Description:</label>
                            <textarea name="description" id="description" cols='30' rows='3'></textarea>
                        </section>
                        
                        <section>
                            <label for="page_url">URL:</label>
                            <input type="url" name="page_url" id="page_url" placeholder="http:/....">
                        </section>
                    </fieldset>

                    <fieldset>
                        <legend>Timetable Information</legend>
                        <section>
                            <label >Available Days:</label>

                            <section>
                                <section>
                                    <label for="monday">Monday</label>
                                    <input type="checkbox" id="monday" name="available_days[]" value="Monday">   
                                </section>

                                <section>
                                    <label for="tuesday">Tuesday</label>
                                    <input type="checkbox" id="tuesday" name="available_days[]" value="Tuesday">
                                </section>

                                <section>
                                    <label for="wednesday">Wednesday</label>
                                    <input type="checkbox" id="wednesday" name="available_days[]" value="Wednesday">
                                </section>

                                <section>
                                    <label for="thursday">Thursday</label>
                                    <input type="checkbox" id="thursday" name="available_days[]" value="Thursday">
                                </section>

                                <section>
                                    <label for="friday">Friday</label>
                                    <input type="checkbox" id="friday" name="available_days[]" value="Friday">
                                </section>

                                <section>
                                    <label for="saturday">Saturday</label>
                                    <input type="checkbox" id="saturday" name="available_days[]" value="Saturday">
                                </section>

                                <section>
                                    <label for="sunday">Sunday</label>
                                    <input type="checkbox" id="sunday" name="available_days[]" value="Sunday">
                                </section>
                            </section>
                        </section>
                        
                        <section>
                            <label for="time">Time start:</label>
                            <input type="text" name="start-time" id="time" required class="required" placeholder="10:00 AM">
                        </section>

                        <section>
                            <label for="end-time">Time end:</label>
                            <input type="text" name="end-time" id="end-time" required class="required" placeholder="10:30AM">
                        </section>
                        
                        <section>
                            <label for="phone">Contact Phone:</label>
                            <input type="text" name="phone" id="phone" required class="required">
                        </section>
                    </fieldset>

                    
                    <button type="submit">Send To Approve</button>

                </form>
            </main>
        </section>

        <?php include('../includes/footer.php') ?>
    </body>

</html>