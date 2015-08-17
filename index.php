<?php
session_start();
if (!empty($_SESSION)) {
echo 'VAR DUMP for troubleshooting: ';
var_dump($_SESSION);	
} else {
echo 'We have an empty session!';
var_dump($_SESSION);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Upload Email</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<ul>
		<?php
			if(isset($_SESSION['errors']))
			{ 
				echo $_SESSION['error_log'];
			} 
			else if(isset($_SESSION['OK']))
			{
				echo $_SESSION['OK'];
				echo $_SESSION['message'];
				unset($_SESSION['email']);
				unset($_SESSION['first_name']);
				unset($_SESSION['last_name']);
				unset($_SESSION['password']);
				unset($_SESSION['confirm_password']);
				unset($_SESSION['date_of_birth']);
				unset($_SESSION['profile_picture']);
			}
		?>
	</ul>
	<form enctype="multipart/form-data" action="validation.php" method="post">
		<input type="hidden" name="registration" value="registration">
		<div <?php if(isset($_SESSION['email_flag'] )) { echo "class='" . $_SESSION['email_flag'] . "'"; }?>>
			<label for="email">Email:</label>
			<input type="text" id="email" name="email" placeholder="you@email.com" value="<?php if (isset($_SESSION['email'])) { echo $_SESSION['email'];}?>" autofocus>
		</div>
		<div <?php if(isset($_SESSION['first_name_flag'] )) { echo "class='" . $_SESSION['first_name_flag'] . "'"; }?>>
			<label for="first_name">First Name:</label>
			<input type="text" id="first_name" name="first_name" placeholder="First Name" value="<?php if (isset($_SESSION['first_name'])) { echo $_SESSION['first_name'];}?>" >
		</div>
		<div <?php if(isset($_SESSION['last_name_flag'] )) { echo "class='" . $_SESSION['last_name_flag'] . "'"; }?>>
			<label for="last_name">Last Name:</label>
			<input type="text" id="last_name" name="last_name" placeholder="Last Name" value="<?php if (isset($_SESSION['last_name'])) { echo $_SESSION['last_name'];}?>" >
		</div>
		<div <?php if(isset($_SESSION['password_flag'] )) { echo "class='" . $_SESSION['password_flag'] . "'"; }?>>
			<label for="password">Password:</label>
			<input type="password" id="password" name="password" placeholder="Password" value="<?php if (isset($_SESSION['password'])) { echo $_SESSION['password'];}?>" >
		</div>
		<div <?php if(isset($_SESSION['confirm_password_flag'] )) { echo "class='" . $_SESSION['confirm_password_flag'] . "'"; }?>>
			<label for="confirm_password">Confirm Password:</label>
			<input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" value="<?php if (isset($_SESSION['confirm_password'])) { echo $_SESSION['confirm_password'];}?>" >
		</div>
		<div <?php if(isset($_SESSION['date_of_birth_flag'] )) { echo "class='" . $_SESSION['date_of_birth_flag'] . "'"; }?>>
			<label for="date_of_birth">Date of Birth</label>
			<input type="text" id="date_of_birth" name="date_of_birth" maxlength="10" placeholder="MM/DD/YYYY" value="<?php if (isset($_SESSION['date_of_birth'])) { echo $_SESSION['date_of_birth'];}?>" >
		</div>
		<div <?php if(isset($_SESSION['profile_picture_flag'] )) { echo "class='" . $_SESSION['profile_picture_flag'] . "'"; }?> >
	    	<label for="profile_picture">Upload Profile Picture:</label> 
	    	<input name="profile_picture" type="file"/>
	    </div>
		<div class="button">
			<button type="submit">Submit Registration</button>
		</div>
	</form>
	<form id="start-over" action="validation.php" method="post">
		<input type="hidden" name="unset" value="unset">
		<input type="submit" value="Start Over!"/>
	</form>
	<?php
		// index.php
		// include connection page
		require_once('connect-coding-dojo.php');
		// get a single record from the table interests joining musics
		$query = "SELECT * 
		          FROM users";
		// since we've included the connection page, we can use the $connection variable
		$results = fetch_all($query);
		
		foreach($results as $row)
		{
	?>
    <div>
    	<ul>
    		<li>Email: <?= $row['email'] ?></li>
    		<li>First Name: <?= $row['first_name'] ?></li>
    		<li>Last Name: <?= $row['last_name'] ?></li>
    		<?php
    			if (!empty($row['date_of_birth'])) 
    			{
    				echo "<li>Date of Birth : ". $row['date_of_birth'] . "</li>";
    			}
    			if (!empty($row['profile_picture'])) 
    			{
    				echo "<li><img src='uploads/" . $row['profile_picture'] . "' width='202' /></li>";
    			}
    		?>
      		<li>Created at : <?php $date = date_create($row['created_at']); echo date_format($date, 'm/d/Y h:ma'); ?></li>
      		<li>
				<form id="delete" action="delete_record.php" method="post">
					<input type="hidden" name="delete" value="delete">
					<input type="hidden" name="record" value="<?= $row['id'] ?>">
					<input type="submit" value="Delete Record"/>
				</form>
      		</li>
      	</ul>
    </div>
    <?php
    	}
    ?>
</body>
</html>