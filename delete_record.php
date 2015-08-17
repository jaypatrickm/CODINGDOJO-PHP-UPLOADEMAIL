<?php
session_start();
if(isset($_POST['delete']) && $_POST['delete'] == "delete")
{ 
	// delete_record.php
	// include connection page
	require_once('connect-coding-dojo.php');
	//echo "we are here!";

	$id = $_POST['record'];

	$query = "DELETE FROM users
			  WHERE users.id = $id";
	if(run_mysql_query($query))
	{
	    $_SESSION['message'] = "<li>Record has been deleted!</li>";
	}
	else
	{
	    $_SESSION['message'] = "<li>Failed to delete record.</li>"; 
	}
	header('Location: index.php');
}
?>