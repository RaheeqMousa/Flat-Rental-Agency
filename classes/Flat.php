<?php

    include_once('../database.inc.php');

    class Flat{
        private $flat_id;
        private $refference_number;
        private $owner_id;
        private $location;
        private $address_id;
        private $monthly_rental_cost;
        private $available_date_from;
        private $available_date_to;
        private $number_of_bathrooms;
        private $number_of_bedrooms;
        private $size_in_square_meters;
        private $furnished;
        private $playground;
        private $has_heating;
        private $has_air_conditioning;
        private $has_access_control;
        private $parking;
        private $backyard;
        private $storage;
        private $rent_conditions;
        private $is_rented;
        private $is_approved;
        private $created_at;
        private $images = [];
        private $marketing=[];
        private $address="";

        function __construct()
        {
            $images=[];
            $marketing=[];
            $address="";
            $this->getImagesFromDB();
            $this->getMarketingFromDB();
            $this->getAddress();
        }

        // function getFlatID(){
        //     return $this->flat_id;
        // }
        // function setFlatID($id){
        //     $this->flat_id=$id;
        // }

        // function getOwnerID(){
        //     return $this->owner_id;
        // }
        // function setOwnerID($id){
        //     $this->owner_id=$id;
        // }

        // function getTitle(){
        //     return $this->title;
        // }
        // function setTilte($tit){
        //     $this->title=$tit;
        // }

        // function getLocation(){
        //     return $this->location;
        // }
        // function setLocation($loc){
        //     $this->location=$loc;
        // }

        // function getAddress(){
        //     return $this->address;
        // }
        // function setAddress($a){
        //     $this->address=$a;
        // }

        // function getMonthlyRentalCost(){
        //     return $this->monthly_rental_cost;
        // }
        // function setMonthlyRentalCost($cost){
        //     $this->monthly_rental_cost=$cost;
        // }

        // function getAvailable(){
        //     return $this->available_date_from;
        // }
        // function setAvailable($a){
        //     $this->available_date_from=$a;
        // }

        // function getNumberOfBathrooms(){
        //     return $this->number_of_bathrooms;
        // }
        // function setNumberOfBathrooms($a){
        //     $this->number_of_bathrooms=$a;
        // }

        // function getSizeInSquareMeters(){
        //     return $this->size_in_square_meters;
        // }
        // function setSizeInSquareMeters($a){
        //     $this->size_in_square_meters=$a;
        // }

        function getImagesFromDB(){
            $pdo=connect_db();
            $sql="select image_path from flat_images where flat_id = :flat_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':flat_id',$this->flat_id);
            $stmt->execute();
            while($row = $stmt->fetch()){
                $this->images[]=$row['image_path'];
            }
            $pdo=null;
        }

        function getMarketingFromDB(){
            $pdo=connect_db();
            $sql="select * from marketing_info where flat_id = :flat_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':flat_id',$this->flat_id);
            $stmt->execute();
            while($row = $stmt->fetch()){
                $this->marketing[]=$row;
            }
            $pdo=null;
        }

        function getTableRow(){
            return "
                <tr>
                    <td><a href='FlatDetails.php?id=$this->flat_id' target='_blank'><img src=../Images/".$this->images[0]." alt='flat image' title='flat image' width='200' height='100' class='Img' '></a></td>
                    <td><a href='FlatDetails.php?id=$this->flat_id' target='_blank'>$this->refference_number</a></td>
                    <td>$this->monthly_rental_cost</td>
                    <td>$this->available_date_from</td>
                    <td>".htmlspecialchars($this->location)."</td>
                    <td>$this->number_of_bedrooms</td>
                </tr>
            ";
        }
        
        function getAddress(){
            $desc="";
            $pdo=connect_db();
            $sql="select * from address where address_id=:address_id;";
            $stat=$pdo->prepare($sql);
            $stat->bindValue(':address_id',$this->address_id);
            $stat->execute();
            $add=$stat->fetch();
            $this->address="Postal code: ".$add['postal_code'].", City ".$add['city'].", Street".$add['street_name'].", House number ".$add['house_no'];
            $pdo=null;
        }
        
        function getDescription(){
            $desc="";
            
            $desc.="<p>".$this->address."</p>";
            $desc.="<p>".$this->monthly_rental_cost."$ per month</p>";
             if($this->backyard){
                $desc.="There is a backyard";
            }else{
                $desc.="There is no backyard";
            }
            $desc.="<p>".$this->number_of_bedrooms." bedrooms</p>";
            $desc.="<p>".$this->number_of_bathrooms." bathrooms</p>";
            $desc.="<p>".$this->size_in_square_meters." m^2</p>";
            if($this->has_air_conditioning==0){
                $desc.="<p>There is ait condition</p>";
            }else if($this->has_air_conditioning==0){
                $desc.="<p>There is NO air condition</p>";
            }
            if($this->has_heating==1){
                $desc.="<p>There is heating system</p>";
            }else if($this->has_heating==0){
                $desc.="<p>There is NO heating system</p>";
            }
            if($this->has_access_control==1){
                $desc.="<p>There is Access control</p>";
            }else if($this->has_access_control==0){
                $desc.="<p>There is NO Access control</p>";
            }
            if($this->storage==1){
                $desc.="<p>Has a Storage</p>";
            }else if($this->has_access_control==0){
                $desc.="<p>Does not has a storage</p>";
            }
            if($this->parking==1){
                $desc.="<p>Has a parking</p>";
            }else if($this->has_access_control==0){
                $desc.="<p>Has NO parking</p>";
            }
            if($this->furnished==1){
                $desc.="<p>This flat is Furnished a parking</p>";
            }else if($this->has_access_control==0){
                $desc.="<p>This flat is NOT Furnished</p>";
            }
            
            return $desc;
        }

        function getFlatDetail(){
            $im = "<section class='image-slider'>";

            for($i=0; $i < sizeof($this->images); $i++){
                $im .= "
                    <figure class='slide'>
                        <img src='../Images/".pathinfo($this->images[$i], PATHINFO_FILENAME).".jpg'
                        alt='".$this->flat_id."' title='".$this->flat_id."'>
                    </figure>
                ";
            }

            $im .= "
                <div class='slider-controls'>
                    <div class='row'>
                        <button onclick='prevSlide()'><</button>
                        <button onclick='nextSlide()'>></button>
                    </div>
                </div>
            </section>";

            $market="<ul>";
            
            foreach ($this->marketing as $row) {
                $market .="<ul>";
                if (!empty($row['title'])) {
                    $market .= "<li>" . ($row['title'] ?? '') . "</li>";
                }
                if (!empty($row['description'])) {
                    $market .="<li>" .($this->marketing['description'] ?? '') . "</li>";
                }
                if (!empty($row['url'])) {
                    $market .= "<li><a href='" . $row['url'] . "' target='_blank'>Visit</a></li>";
                }
                $market .="</ul>";
            }

            $market.="</ul>";
         
            return "
                <nav class='row justify-content-center flex-direction-column gap-8 '>
                    $im
                    <form>
                        <button type='submit' name='buttonAction' value='RequestAppointment.php?id=$this->flat_id' > 
                                Request Flat Viewing Appointment
                            </button><br>
                        <button type='submit' name='buttonAction' value='rentFlat.php?id=$this->flat_id' > 
                                Rent the Flat
                        </button>
                    </form>
                </nav>
                <section class='row flex-direction-column justify-content-start align-items-start gap-8 description'>     
                        ".$this->getDescription()."
                </section>
                ".
                (is_array($this->marketing) && sizeof($this->marketing) > 0 ? "
                    <aside>
                        <h2>Marketing Information</h2>
                        $market
                    </aside>
                " : "");
                
            
        }

        
    }


?>