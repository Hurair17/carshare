<link href="trip.css" rel="stylesheet">

<?php
//start session and connect
//session_start();
echo "<br><br>";
include('connection.php');

//retrieve all trips
$sql="SELECT * FROM carsharetrips"; //WHERE user_id='".$_SESSION['user_id']."'";
//$sql = "select phonenumber from users"
if($result = mysqli_query($link, $sql)){
    //print_r($result);
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            //check frequency
            if($row['regular']=="N"){
                $frequency = "One-off journey.";
                $time = $row['date']." at " .$row['time'].".";
            }else{
                $frequency = "Regular."; 
                $array = [];
                    if($row['monday']==1){array_push($array,"Mon");}
                    if($row['tuesday']==1){array_push($array,"Tue");}
                    if($row['wednesday']==1){array_push($array,"Wed");}
                    if($row['thursday']==1){array_push($array,"Thu");}
                    if($row['friday']==1){array_push($array,"Fri");}
                    if($row['saturday']==1){array_push($array,"Sat");}
                    if($row['sunday']==1){array_push($array,"Sun");}
                $time = implode("-", $array)." at " .$row['time'].".";
            }
            echo 
             '<div class="row trip">
                    <div class="col-sm-8 journey">
                        <div><span class="departure">Departure:</span> '.$row['departure'].'.</div>
                        <div><span class="destination">Destination:</span> '. $row['destination'] .'.</div>
                        <div class="time">'.$time.'</div>
                        <div>'.$frequency.'</div>
                    </div>
                    <div class="col-sm-2 price">
                        <div class="price">Rs '.$row['price'].'</div>
                        <div class="perseat">Per Seat</div>
                        <div class="seatsavailable">'.$row['seatsavailable'].' seat left</div>
                        <div class="seatsavailable">Contact No : '.$row['contact'].'</div>


                    </div>
                    
                </div>';
        }
    }else{
        echo '<div class="notrips alert alert-warning">You Have not created any trips yet</div>';
    }
}
?>