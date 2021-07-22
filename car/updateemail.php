<?php
//start session and connect
session_start();
include ('connection.php');

//get user_id and new email sent through Ajax
$user_id = $_SESSION['user_id'];
$newemail = $_POST['email'];

//check if new email exists
$sql = "SELECT * FROM users WHERE email='$newemail'";
$result = mysqli_query($link, $sql);
$count = $count = mysqli_num_rows($result);
if($count>0){
    echo "<div class='alert alert-danger'>There is already as user registered with that email! Please choose another one!</div>"; exit;
}


//get the current email
$sql = "SELECT * FROM users WHERE user_id='$user_id'";
$result = mysqli_query($link, $sql);

$count = mysqli_num_rows($result);

if($count == 1){
    $row = mysqli_fetch_assoc($result); 
    $email = $row['email']; 
}else{
    echo "<div class='alert alert-danger'>There was an error retrieving the email from the database</div>";exit;   
}

//create a unique activation code
$activationKey = bin2hex(openssl_random_pseudo_bytes(16));

//insert new activation code in the users table
$sql = "UPDATE users SET activation2='$activationKey' WHERE user_id = '$user_id'";
$result = mysqli_query($link, $sql);
if(!$result){
    echo "<div class='alert alert-danger'>There was an error inserting the user details in the database.</div>";exit;
}
//else{
//     //send email with link to activatenewemail.php with current email, new email and activation code
//     $message = "Please click on this link prove that you own this email:\n\n";
// $message .= "http://carsharingwebsitefinal.thecompletewebhosting.com/activatenewemail.php?email=" . urlencode($email) . "&newemail=" . urlencode($newemail) . "&key=$activationKey";
// if(mail($newemail, 'Email Update for you Online Notes App', $message, 'From:'.'developmentisland@gmail.com')){
//        echo "<div class='alert alert-success'>An email has been sent to $newemail. Please click on the link to prove you own that email address.</div>";
// }
    
// }

    //Store them in three variables
    $sql = "UPDATE users SET email='$newemail', activation2='0' WHERE (email='$email' AND activation2='$activationKey') LIMIT 1";
    $result = mysqli_query($link, $sql);
        //If query is successful, show success message
    if(mysqli_affected_rows($link) == 1){
        session_destroy();
        setcookie("rememeberme", "", time()-3600);
        echo '<div class="alert alert-success">Your email has been updated.</div>';
        echo '<a href="index.php" type="button" class="btn-lg btn-sucess">Log in<a/>';
        
    }else{
        //Show error message
        echo '<div class="alert alert-danger">Your email could not be updated. Please try again later.</div>';
        echo '<div class="alert alert-danger">' . mysqli_error($link) . '</div>';
        
    }


?>