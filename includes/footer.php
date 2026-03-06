        <footer class="row align-items-start">
            <section class="row flex-direction-column gap-16 align-items-start justify-content-start">
                <div class="row gap-8">
                   <?php
                        if(isset($index_page) && $index_page){
                            echo "<img src='Images/logo.svg' alt='3lRaheeq agency logo' title='3lRaheeq agency' width='100' height='30'>";
                        }else{
                            echo "<img src='../Images/logo.svg' alt='3lRaheeq agency logo' title='3lRaheeq agency' width='100' height='30'>";
                        }
                    ?>
                   <p>3lRaheeq agency</p>                 
    </div>
                <p>&copy; 2025 3lRaheeq Agency. All rights reserved.</p>
            </section>    

            <nav>
                <?php 
                    if(isset($auth_page) && $auth_page){
                        echo "<a href='../pages/ContactUs.php'>Contact Us</a>";
                    }else if(isset($index_page) && $index_page){
                        echo "<a href='pages/ContactUs.php'>Contact Us</a>";
                    }else{
                        echo "<a href='./ContactUs.php'>Contact Us</a>";
                    }
                ?>
                
            </nav>
                
            <section class="row flex-direction-column gap-16 align-items-start justify-content-start">
                <p><Strong>Address:</Strong>Al-Irsal Street, Birzeit, Palestine</p>
                <p><strong>Email:</strong><a href="mailto:raheeqmousa99@gmail.com">raheeqmousa99@gmail.com</a></p>
                <p><strong>Phone:</strong><a href="tel:+972 55-410-0151">+972 598411518</a></p>
            </section>
        </footer>