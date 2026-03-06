<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>3lRaheeqUnity</title>
        <link href="../main.css" rel="stylesheet">
    </head>

    <body>

        <?php include('../includes/header.php') ?>

        
        <section class="page-navigation-main">
            <?php include('../includes/leftSideNavigation.php') ?>
            <main id="contact-us">

                <section id="contact-section">
                
                    <section>
                        <p>Direct contact with our developers!</p>
                        <img src="../Images/contact.png" alt="contact us image of 3lRaheeqUnity" title="Contact Us image" >
                    </section>
                    
                    <form method="post" enctype="multipart/form-data" action="http://yhassouneh.studentprojects.ritaj.ps/util/process.php ">
                        <section>
                            <label for="senderName">Sender Name:</label>
                            <input type="text" id="senderName" name="SenderName" required placeholder="Enter Your name">
                        </section>
                        
                        <br>

                        <section>
                            <label for="senderEmail">Sender Email:</label>
                            <input type="email" id="senderEmail" name="SenderEmail" required placeholder="example@gmail.com">
                        </section>
                    
                        
                        <br>
                        <section>
                            <label for="senderLocation">Sender Location (city) "start with a letter":</label>
                            <input type="text" id="senderLocation" name="SenderLocation" pattern="^[A-Za-z][A-Za-z0-9\s]*$" required placeholder="Enter your city">
                        </section>
                        <br>
                        
                        <section>
                            <label for="messageSubject">Message Subject:</label>
                            <input type="text" id="messageSubject" name="MessageSubject" required placeholder="Enter the subject of your Message"> 
                        </section>       
                        
                        <br>
                        <section>
                            <label for="messageBody">Message:</label>
                            <br>
                            <textarea id="messageBody" name="MessageBody" rows="5" required placeholder="Enter your message"></textarea>
                        </section>
                        <br>
                        <section>
                            <button type="submit"><img src="../Images/sendImg.jpg" alt="This is send Message image" title="Send Message Img" width="20" height="20" >&nbsp; Send</button>
                            <button type="reset"><img src="../Images/ResetImg.jpg" alt="This is reset fields image" title="Reset fields Img" width="20" height="20" >&nbsp; Reset</button>
                        </section>
                    </form>

                </section>

                <br>

                <section>
                    <strong>Address: </strong>  Al-Ersal street, Ramallah, Palestine<br>
                    <strong>Phone: </strong> +972 55-410-0151<br>
                    <strong>Email: </strong><a href="mailto:1220515@stbzu.birzeit.edu">1220515@stbzu.birzeit.edu</a> 
                </section>

            </main>
        </section>

        

        <?php include('../includes/footer.php') ?>

    </body>
</html>