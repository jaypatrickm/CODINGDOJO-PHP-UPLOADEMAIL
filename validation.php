<?php
//start session
session_start();

//unset/destroy/empty session post
if(isset($_POST['unset']) && $_POST['unset'] == "unset")
{ 
	session_destroy();
	$_SESSION = array();
	header('location: index.php');
}

//clear past error/OK messages/flags
unset($_SESSION['error_log']);
unset($_SESSION['errors']);
unset($_SESSION['OK_log']);
unset($_SESSION['OK']);
unset($_SESSION['email_flag']);
unset($_SESSION['first_name_flag']);
unset($_SESSION['last_name_flag']);
unset($_SESSION['password_flag']);
unset($_SESSION['confirm_password_flag']);
unset($_SESSION['date_of_birth_flag']);
unset($_SESSION['profile_picture_flag']);
unset($_SESSION['form_error']);

//set initial error log, for easy adding
$_SESSION['error_log'] = "<li>Houston, we have a problem...</li>";
$_SESSION['OK_log'] = "<li>Houston, we're good.</li>";

//verify with post, which form we are recieving posts from
if(isset($_POST['registration']) && $_POST['registration'] == "registration")
{ 
	//check if empty form
	if( empty($_POST['email']) && empty($_POST['first_name']) && empty($_POST['last_name']) && empty($_POST['password']) && empty($_POST['confirm_password']) )
	{
		$_SESSION['errors'] = '<li>It seems you\'ve sent an empty form. Please fill out the form before submitting.</li>';
		$_SESSION['error_log'] .= $_SESSION['errors'];
		$_SESSION['email_flag'] = 'bad';
		$_SESSION['first_name_flag'] = 'bad';
		$_SESSION['last_name_flag'] = 'bad';
		$_SESSION['password_flag'] = 'bad';
		$_SESSION['confirm_password_flag'] = 'bad';
		$error_flags = 0;
		//Just in case, in the wild chance, they would only input their date of birth 
		if (!empty($_POST['date_of_birth'])) {
			$date_of_birth = trim($_POST['date_of_birth']);
			$_SESSION['date_of_birth'] = $date_of_birth;
			if (strlen($date_of_birth) != 10)
			{
				$_SESSION['errors'] = '<li>Date was entered as: ' . $date_of_birth . '. Date of birth must be entered like so : MM/DD/YYYY , 11/17/1988.</li>';
				$_SESSION['error_log'] .= $_SESSION['errors'];
				$_SESSION['date_of_birth_flag'] = 'bad';
				$error_flags++;
				//header('location: index.php');
			} else 
			{
				$dob = explode('/', $date_of_birth);
				if (count($dob)!=3) 
				{
					$_SESSION['errors'] = '<li>Date was entered as: ' . $date_of_birth . '. Date of birth must be entered like so : MM/DD/YYYY , 11/17/1988.</li>';
					$_SESSION['error_log'] .= $_SESSION['errors'];
					$_SESSION['date_of_birth_flag'] = 'bad';
					$error_flags++;
					//header('location: index.php');
				} else 
				{
					if ((strlen($dob[0])) == 2 && (strlen($dob[1]) == 2) && (strlen($dob[2])== 4) )
					{
						//$_SESSION['errors'] = '<li>Date of birth is successfully entered as: ' . $date_of_birth . '</li>';
						//header('location: index.php');	
						$_SESSION['date_of_birth_flag'] = 'good';
					} else 
					{
						$_SESSION['errors'] = '<li>Date was entered as: ' . $date_of_birth . '. Date of birth must be entered like so : MM/DD/YYYY , 11/17/1988.</li>';
						$_SESSION['error_log'] .= $_SESSION['errors'];
						$_SESSION['date_of_birth_flag'] = 'bad';
						$error_flags++;
						//header('location: index.php');
					}
				}
			}
		} else 
		{
			//do nothing because date of birth is optional	
		}
		if ($error_flags>0)
		{
			$error_flags = 0;
			header('location:index.php');
		} else 
		{
			//$_SESSION['OK'] = '<li>All fields are okay!</li>';
			//header('location:index.php');
		}
		header('location: index.php');
	} else 
	{
		//at least one field is posted on
		//set error flags, trim post answers
		$error_flags = 0;
		$email = trim($_POST['email']);
		$first_name = trim($_POST['first_name']);
		$last_name = trim($_POST['last_name']);
		$password = trim($_POST['password']);
		$confirm_password = trim($_POST['confirm_password']);
		
		//email errors
		if(!empty($_POST['email']))
		{
			$_SESSION['email'] = $email;
			if(filter_var($email, FILTER_VALIDATE_EMAIL) == false)
			{
				$_SESSION['errors'] = '<li>Email :'. $email . ' is invalid. Please provide a valid email, like speros@codindojo.com or chris@yahoo.com.</li>';
				$_SESSION['error_log'] .= $_SESSION['errors'];
				$_SESSION['email_flag'] = 'bad';
				$error_flags++;
				//header('location: index.php');
			} else 
			{
				$_SESSION['email_flag'] = 'good';
			}
		} else 
		{
			$_SESSION['errors'] = '<li>Email field is empty. Please enter a valid email, like speros@codindojo.com or chris@yahoo.com.</li>';
			$_SESSION['error_log'] .= $_SESSION['errors'];
			$_SESSION['email_flag'] = 'bad';
			$error_flags++;
		}

		//first_name errors
		if(!empty($_POST['first_name']))
		{
			$_SESSION['first_name'] = $first_name;
			//*updated first name regex match to validate if name is only characters and white space, instead of it just has numbers JM 4.17
			if( preg_match( "/^[a-zA-Z ]*$/", $first_name)) 
			{
				$_SESSION['first_name_flag'] = 'good';
				//header('location: index.php');
			} else
			{
				$_SESSION['errors'] = '<li>First Name :'. $first_name . ' is invalid. First name cannot contain any numerical values or special characters, please enter a valid name using only alphanumeric characters.</li>';
				$_SESSION['error_log'] .= $_SESSION['errors'];
				$_SESSION['first_name_flag'] = 'bad';
				$error_flags++;
			}
			/*
			if( preg_match( "/\d/", $first_name)) 
			{
				$_SESSION['errors'] = '<li>First Name :'. $first_name . ' is invalid. First name cannot contain any numerical values, please enter a valid name using only alphanumeric characters.</li>';
				$_SESSION['error_log'] .= $_SESSION['errors'];
				$_SESSION['first_name_flag'] = 'bad';
				$error_flags++;
				//header('location: index.php');
			} else
			{
				$_SESSION['first_name_flag'] = 'good';
			}*/

		} else
		{
			$_SESSION['errors'] = '<li>First name field is empty. Please enter your first name.</li>';
			$_SESSION['error_log'] .= $_SESSION['errors'];	
			$_SESSION['first_name_flag'] = 'bad';
			$error_flags++;
		}

		//last_name errors
		if(!empty($_POST['last_name']))
		{
			$_SESSION['last_name'] = $last_name;
			//*updated last name regex match to validate if name is only characters and white space, instead of it just has numbers JM 4.17
			if( preg_match( "/^[a-zA-Z ]*$/", $last_name)) 
			{
				$_SESSION['last_name_flag'] = 'good';
				//header('location: index.php');
			} else
			{
				$_SESSION['errors'] = '<li>Last Name :'. $last_name . ' is invalid. Last name cannot contain any numerical values or special characters, please enter a valid name using only alphanumeric characters.</li>';
				$_SESSION['error_log'] .= $_SESSION['errors'];
				$_SESSION['last_name_flag'] = 'bad';
				$error_flags++;
			}
			/*
			if( preg_match( "/\d/", $last_name)) 
			{
				$_SESSION['errors'] = '<li>Last Name :'. $last_name . ' is invalid. Last name cannot contain any numerical values, please enter a valid name using only alphanumeric characters.</li>';
				$_SESSION['error_log'] .= $_SESSION['errors'];
				$_SESSION['last_name_flag'] = 'bad';
				$error_flags++;
				//header('location: index.php');
			} else 
			{
				$_SESSION['last_name_flag'] = 'good';
			}
			*/
		} else
		{
			$_SESSION['errors'] = '<li>Last name field is empty. Please enter your last name.</li>';
			$_SESSION['error_log'] .= $_SESSION['errors'];
			$_SESSION['last_name_flag'] = 'bad';
			$error_flags++;		
		}

		//password errors
		if (!empty($_POST['password']))
		{
			$_SESSION['password'] = $password;
			if (strlen($password) < 6) 
			{
				$_SESSION['errors'] = '<li>Password must be at least 6 characters.</li>';
				$_SESSION['error_log'] .= $_SESSION['errors'];
				$_SESSION['password_flag'] = 'bad';
				$error_flags++;
				//header('location: index.php');
			} else 
			{
				$_SESSION['password_flag'] = 'good';
			}
		} else 
		{
			$_SESSION['errors'] = '<li>Please enter a password</li>';
			$_SESSION['error_log'] .= $_SESSION['errors'];
			$_SESSION['password_flag'] = 'bad';
			$error_flags++;
		}

		//confirm password errors
		if (!empty($_POST['confirm_password']))
		{
			$_SESSION['confirm_password'] = $confirm_password;
			if (strlen($confirm_password) < 6) 
			{
				$_SESSION['errors'] = '<li>Password must be at least 6 characters.</li>';
				$_SESSION['error_log'] .= $_SESSION['errors'];
				$_SESSION['confirm_password_flag'] = 'bad';
				$error_flags++;
				//header('location: index.php');
			} else 
			{
				if($confirm_password == $password)
				{
					$_SESSION['confirm_password_flag'] = 'good';
				} else 
				{
					$_SESSION['errors'] = '<li>Passwords did not match, please re-enter passwords.</li>';
					$_SESSION['error_log'] .= $_SESSION['errors'];
					$_SESSION['confirm_password_flag'] = 'bad';
					$error_flags++;
				}
			}
		} else 
		{
			$_SESSION['errors'] = '<li>Confirm password not entered. Please enter your password again to confirm.</li>';
			$_SESSION['error_log'] .= $_SESSION['errors'];
			$_SESSION['confirm_password_flag'] = 'bad';
			$error_flags++;
		}

		//date_of_birth errors
		if (!empty($_POST['date_of_birth'])) 
		{
			$date_of_birth = trim($_POST['date_of_birth']);
			$_SESSION['date_of_birth'] = $date_of_birth;
			if (strlen($date_of_birth) != 10)
			{
				$_SESSION['errors'] = '<li>Date was entered as: ' . $date_of_birth . '. Date of birth must be entered like so : MM/DD/YYYY , 11/17/1988.</li>';
				$_SESSION['error_log'] .= $_SESSION['errors'];
				$_SESSION['date_of_birth_flag'] = 'bad';
				$error_flags++;
				//header('location: index.php');
			} else 
			{
				$dob = explode('/', $date_of_birth);
				if (count($dob)!=3) 
				{
					$_SESSION['errors'] = '<li>Date was entered as: ' . $date_of_birth . '. Date of birth must be entered like so : MM/DD/YYYY , 11/17/1988.</li>';
					$_SESSION['error_log'] .= $_SESSION['errors'];
					$_SESSION['date_of_birth_flag'] = 'bad';
					$error_flags++;
					//header('location: index.php');
				} else 
				{
					if ((strlen($dob[0])) == 2 && (strlen($dob[1]) == 2) && (strlen($dob[2])== 4) )
					{
						//$_SESSION['errors'] = '<li>Date of birth is successfully entered as: ' . $date_of_birth . '</li>';
						$_SESSION['date_of_birth_flag'] = 'good';
						//header('location: index.php');	
					} else 
					{
						$_SESSION['errors'] = '<li>Date was entered as: ' . $date_of_birth . '. Date of birth must be entered like so : MM/DD/YYYY , 11/17/1988.</li>';
						$_SESSION['error_log'] .= $_SESSION['errors'];
						$_SESSION['date_of_birth_flag'] = 'bad';
						$error_flags++;
						//header('location: index.php');
					}
				}
			}
		}

		if(!empty($_FILES['profile_picture']['tmp_name']))
		{
			$uploads_dir = 'uploads/';
			$uploadfile = $uploads_dir . basename($_FILES['profile_picture']['name']);
			$_SESSION['profile_picture'] = basename($_FILES['profile_picture']['name']) ;
			$uploadOk = 1;
			$imageFileType = pathinfo($uploadfile, PATHINFO_EXTENSION);
			$check = getimagesize($_FILES['profile_picture']['tmp_name']);
			$getfilesize = $_FILES['profile_picture']['tmp_name'];
			$filesize = filesize($getfilesize);
			if($check !== false) 
			{
		        $_SESSION['OK'] = "File is an image - " . $check["mime"] . ".";
		        $_SESSION['OK_log'] .= $_SESSION['OK'];
		        $_SESSION['profile_picture_flag'] = 'green';
		        $uploadOk = 1;
		    } else 
		    {
		        $_SESSION['errors'] = "File is not an image.";
		        $_SESSION['error_log'] .= $_SESSION['errors'];
		        $_SESSION['profile_picture_flag'] = 'bad';
		        $error_flags++;
		        $uploadOk = 0;
		    }
		    // Check file size
			if ($filesize > 10000000) 
			{
			    $_SESSION['errors'] = "Sorry, your file is too large.";
			    $_SESSION['error_log'] .= $_SESSION['errors'];
			    $_SESSION['profile_picture_flag'] = 'bad';
			    $error_flags++;
			    $uploadOk = 0;
			}
			// Allow certain file formats
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
			&& $imageFileType != "gif" ) 
			{
			    $_SESSION['errors'] =  "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
				$_SESSION['error_log'] .= $_SESSION['errors'];
				$_SESSION['profile_picture_flag'] = 'bad';
				$error_flags++;
			    $uploadOk = 0;
			}
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0) 
			{
			    $_SESSION['errors'] = "Sorry, your file was not uploaded.";
			    $_SESSION['error_log'] .= $_SESSION['errors'];
			    $_SESSION['profile_picture_flag'] = 'bad';
			    $error_flags++;
			    //header('location: index.php');
			} else 
			{
				// if everything is ok, try to upload file
			    if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $uploadfile)) 
			    {
			        $_SESSION['OK'] = "The file ". basename( $_FILES["profile_picture"]["name"]). " has been uploaded.";
			        $_SESSION['OK_log'] .= $_SESSION['OK'];
			        $_SESSION['profile_picture_flag'] = 'good';
			        //header('location: index.php');
			    } else 
			    {
			        $_SESSION['errors'] = "Sorry, there was an error uploading your file.";
			        $_SESSION['error_log'] .= $_SESSION['errors'];
			        $_SESSION['profile_picture_flag'] = 'bad';
			        $error_flags++;
			        //header('location: index.php');
			    }
			}

	}
		//check if there are any errors written, if so redirect
		if ($error_flags>0)
		{
			$error_flags = 0;
			header('location:index.php');
		} else 
		{
			//all fields are okay, in this case we will just redirect back to homepage
			$_SESSION['OK'] = '<li>All fields are okay!</li>';
			header('location:process.php');
		}

	}
} else 
{
	//$_SESSION['form_error'] = 'Something happened. Please try again.';
	header('location:index.php');
}
?>