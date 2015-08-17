<?php
session_start();
// process.php
// include connection page
require_once('connect-coding-dojo.php');
//echo "we are here!";

$email = $_SESSION['email'];
$first_name = $_SESSION['first_name'];
$last_name = $_SESSION['last_name'];
$password = $_SESSION['password'];
if(!isset($_SESSION['date_of_birth']))
{
	$date_of_birth = '';
} else {
	$date_of_birth = $_SESSION['date_of_birth'];
}
if(!isset($_SESSION['profile_picture'])){
	$profile_picture = '';
} else {
	$profile_picture = $_SESSION['profile_picture'];
}
// Add validations here to make sure information is correct
// if validations check out we insert the records into the database
$query = "INSERT INTO  users (email, first_name, last_name, password, date_of_birth, profile_picture, created_at, updated_at)
          VALUES('{$_SESSION['email']}','{$_SESSION['first_name']}','{$_SESSION['last_name']}', '{$_SESSION['password']}', '{$date_of_birth}', '{$profile_picture}', NOW(), NOW())";
if(run_mysql_query($query))
{
    $_SESSION['message'] = "<li>New Interest has been added correctly!</li>";
}
else
{
    $_SESSION['message'] = "<li>Failed to add new Interest</li>"; 
}
header('Location: index.php');

?>