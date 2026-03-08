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

                <form action="offerFlatSubmit.php" method="post" enctype="multipart/form-data" class=" row flex-direction-column gap-32">
                    <h2 >Offer Flat</h2>
                    <section class="row gap-32 width-100 align-items-start">

                        <section class="row border-16 shadow py-32 px-16 gap-16 flex-direction-column">
                            <h3>Flat Details</h3>

                            <div class="row gap-8 width-100">
                                <label for="location">Location:</label>
                                <input type="text" name="location" id="location" required>
                            </div>
                            
                            <div class="row gap-8 width-100">
                                <label for="rent">Rent/Month:</label>
                                <input type="number" name="rent" id="rent" required class="required">
                            </div>
                            
                            <div class="row gap-8 width-100">
                                <label for="available_date_from">Date Available From:</label>
                                <input type="date" name="available_date_from" id="available_date_from" required class="required">
                            </div>
                            

                            <div class="row gap-8 width-100">
                                <label for="available_date_to">Date Available To:</label>
                                <input type="date" name="available_date_to" id="available_date_to" required class="required">
                            </div>

                            <div class="row gap-8 width-100">
                                <label for="bedrooms">Bedrooms:</label>
                                <input type="number" name="bedrooms" id="bedrooms" min="1" required class="required">
                            </div>
                            <div class="row gap-8 width-100">
                                <label for="bathrooms">Bathrooms:</label>
                                <input type="number" name="bathrooms" id="bathrooms" min="1" required class="required">
                            </div>
                            <div class="row gap-8 width-100">
                                <label for="size">Size (sqm):</label>
                                <input type="number" name="size" id="size" required class="required">
                            </div>
                            <div class="row gap-8 width-100">
                                <label for="conditions">Conditions:</label>
                                <textarea name="conditions" id="conditions" cols='40' rows='3' ></textarea>
                            </div>

                            <div class="row gap-8 width-100">
                                <label for="images">Flat Photos (at least 3):</label>
                                <input type="file" name="images[]" id="images" multiple accept="image/*" required>
                            </div>
                        </section>

                        <div class="row justify-content-center gap-32 flex-direction-column">
                        <section class="row border-16 shadow py-32 px-16 gap-16 flex-direction-column">
                            <h3>Flat Address</h3>
                            <div class="row gap-8 width-100">
                                <label for="house_number">House Number:</label>
                                <input id="house_number" name="house_number" required class="required" type="text" placeholder="Enter House Number">
                            </div>
                            <div class="row gap-8 width-100">
                                <label for="street_name">Street Name:</label>
                                <input id="street_name" name="street_name" required class="required" placeholder="Enter street name">
                            </div>
                            <div class="row gap-8 width-100">
                                <label for="city">City:</label>
                                <input id="city" name="city" required class="required" type="text" placeholder="Enter City">
                            </div>
                            <div class="row gap-8 width-100">
                                <label for="postal_code">Postal Code:</label>
                                <input id="postal_code" name="postal_code" required class="required" type="text" placeholder="Enter Postal Code">
                            </div>
                        </section>
                        <section class="row border-16 shadow py-32 px-16 gap-16 flex-direction-column">
                            <h3>Marketing Information (Optional)</h3>
                            <div class="row gap-8 width-100">
                                <label for="title">Title:</label>
                                <input type="text" name="title" id="title">
                            </div>
                            <div class="row gap-8 width-100">
                                <label for="description">Description:</label>
                                <textarea name="description" id="description" cols='40' rows='3'></textarea>
                            </div>                   
                            <div class="row gap-8 width-100">
                                <label for="page_url">URL:</label>
                                <input type="url" name="page_url" id="page_url" placeholder="http:/....">
                            </div>
                        </section>
                        </div>


                        <div class="row justify-content-center gap-32 align-items-start">
                            <section class="row border-16 shadow py-32 px-16 gap-16 flex-direction-column section">
                                <h3>Utilities</h3>

                                <div class="row flex-direction-column gap-8 width-100 shadow border-16 py-32 px-16 box-sizing-border-box">
                                    <label>Backyard</label>
                                    <div class="row gap-8 width-100 justify-content-start"> 
                                        <label for="backyard_individual">Individual</label>
                                        <input type="radio" name="backyard" id="backyard_individual" value="individual">
                                    </div>
                                    <div class="row gap-8 width-100 justify-content-start">
                                        <label for="backyard_shared">Shared</label>
                                        <input type="radio" name="backyard" id="backyard_shared" value="shared">
                                    </div>
                                </div>
                                <div class="row flex-direction-column justify-content-start width-100 gap-8 border-16 py-32 px-16 box-sizing-border-box">
                                    <div class="row gap-8 width-100 justify-content-start">
                                        <label for="playground">Playground</label>
                                        <input type="checkbox" name="playground" id="playground">
                                    </div>
                                    <div class="row gap-8 width-100 justify-content-start">
                                        <label for="furnished">Furnished</label>
                                        <input type="checkbox" name="furnished" id="furnished">
                                    </div>
                                    <div class="row gap-8 width-100 justify-content-start">
                                        <label for="storage">Storage</label>
                                        <input type="checkbox" name="feature" id="storage">
                                    </div>
                                </div> 
                            </section>

                            <section class="row border-16 shadow py-32 px-16 gap-16 flex-direction-column section">
                                <h3>Features</h3>
                                    <div class="row gap-8 width-100 justify-content-start">
                                        <label for="heating">Heating System</label>
                                        <input type="checkbox" name="heating" id="heating">
                                    </div>
                                    <div class="row gap-8 width-100 justify-content-start">
                                        <label for="air_condition">Air Conditioning</label>
                                        <input type="checkbox" name="air_condition" id="air_condition">
                                    </div>
                                    <div class="row gap-8 width-100 justify-content-start">
                                        <label for="access_control">Access Control</label>
                                        <input type="checkbox" name="access_control" id="access_control">
                                    </div>
                        
                                    <div class="row gap-8 width-100 justify-content-start">
                                        <label for="parking">Car Parking</label>
                                        <input type="checkbox" name="parking" id="parking">
                                    </div>

                            </section>
                        </div>            

                        <section class="row border-16 shadow py-32 px-16 gap-16 flex-direction-column">
    <h3>Schedule Flat Previews</h3>

    <div class="row flex-direction-column gap-16 width-100 shadow border-16 py-32 px-16 box-sizing-border-box">

        <div class="row gap-8 width-100">
            <label>Preview Date:</label>
            <input type="date" name="preview_date[]" required>
        </div>

        <div class="row gap-8 width-100">
            <label>Start Time:</label>
            <input type="time" name="preview_time_start[]" required>
        </div>

        <div class="row gap-8 width-100">
            <label>End Time:</label>
            <input type="time" name="preview_time_end[]" required>
        </div>

        <div class="row gap-8 width-100">
            <label>Contact Phone:</label>
            <input type="tel" name="phone[]" placeholder="0599232345" required>
        </div>

    </div>

    <div class="row width-100 justify-content-center">
        <button type="button" id="add-preview">Add Another Preview</button>
    </div>
</section>

                        
                    </section>
<button type="submit">Submit Flat Offer</button>
                </form>
            </main>
        </section>

        <?php include('../includes/footer.php') ?>
        <?php
            if(!empty($_SESSION['error'])){
                echo "<div class='toast toast-error display-none'>
                        <p>".$_SESSION['error']."</p>
                    </div>";
                unset($_SESSION['error']);
            }
        ?>

        <script src="../main.js"></script>
    </body>

</html>