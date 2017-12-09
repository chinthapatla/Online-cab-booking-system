<HTML XMLns="http://www.w3.org/1999/xHTML"> 
<!--
	Functonality : This page is provided to customers to register for CabsOnline application.
			 This page will take user details like name, email address, password and phone and register in the database if valid and send a confirmation email to the customer
--> 
  <head> 
    <title>Register Page</title> 
		<link rel="stylesheet" type="text/css" href="Cabsonline_Style.css">
  </head> 
  <body>
  <H1>CabsOnline</H1>
	<H2>Register to CabsOnline</H2>

  <form>
		<table> <tr> <td>		
		Name:</td><td><input type="text" name="namefield"> </label></td></tr>
		<tr><td>
		Password:</td><td><input type="password" name="passwordfield"> </label></td></tr>
		<tr><td>
		Confirm Password:</td><td><input type="password" name="confirmpasswordfield"> </label></td></tr>
		<tr><td>
		Email:</td><td><input type="text" name="emailfield"> </label></td></tr>
		<tr><td>
		Phone:</td><td><input type="text" name="phonefield"> </label></td></tr>
		<tr><td>
		<input type="submit" value="Register" /></br></td><td></td></tr></table>
		<H2>Already registered? <a href="login.php">Login here</a></H2>
  </form>
  </body> 

<?php 
	//Validate if all the input field values are provided
	if(isset($_GET['namefield']) && isset($_GET['passwordfield']) && isset($_GET['confirmpasswordfield']) && isset($_GET['emailfield']) && isset($_GET['phonefield']))
	{
		
		//Assigning the entered input values to the variables 
		//$string = $_GET['stringfield'];
		$name = trim($_GET['namefield']);
		$password = trim($_GET['passwordfield']);
		$confirm_password = trim($_GET['confirmpasswordfield']);
		$email = trim($_GET['emailfield']);
		$phone = trim($_GET['phonefield']);
		//Check if any input values is empty
		if(empty($name) || empty($password) || empty($confirm_password) || empty($email) || empty($phone))
		{
			echo "Please provide inputs to all the fields";
			exit();
		}
		else
		{
			//Check if password and confirm passwords are same
			if($password === $confirm_password)
			{
				//Call function to validate email address which returns boolean value
				if(ValidateEmail($email,$name))
				{
					//Call function to register user
					RegisterUser($name,$password,$email,$phone);
				}
				else
				{
					echo "Please enter valid email address";
					exit();
				}
			}
			else
			{
				echo "Password and Confirm password should be same";
					exit();
			}
		}
	}
	//This function takes email address and name as input paramenters and return boolen value true if the email format and address are correct else returns false.
	function ValidateEmail($emailid,$name)
	{
		//Check for correct email format
		if(strpos($emailid, '@') > 0 && strpos($emailid, '.') > strpos($emailid, '@')+1 && strpos(strrev($emailid), '.') > 0)
		{
			$email_to = $emailid;
			$subject = "Welcome to CabsOnline!";
			$message = "Dear ". $name . ", welcome to use CabsOnline!";
			$header = "From registration@cabsonline.com.au";
			//Send welcome mail to the email address provided
			if(mail($email_to,$subject,$message,$header,"-r 1784765@student.swin.edu.au"))
			{
				//return true if email is sent
				return true;
			}
			else
			{
				echo "Registration failed, email could not be sent!";
				exit();
			}
		}
		return false;
	}
	//This function takes name, password,email address and phone number as input parameters and stores the values in database if validations are passed else error message is displayed
	function RegisterUser($name,$password,$email,$phone)
	{
		$DBConnect = @mysqli_connect("mysql.ict.swin.edu.au", "s1784765","200385", "s1784765_db")
		Or die ("<p>Unable to connect to the database server.</p>". "<p>Error code ".
		mysqli_connect_errno().": ". mysqli_connect_error()). "</p>";
		// get language names from db
		$SQLstring = "select count(*) from customer where Email = '".$email."'";
		$queryResult = @mysqli_query($DBConnect, $SQLstring)
		Or die ("<p>Unable to query the table.</p>"."<p>Error code ".
		mysqli_errno($DBConnect). ": ".mysqli_error($DBConnect)). "</p>";
		$row = mysqli_fetch_row($queryResult);
		if($row[0] == 1)
		{
			echo "This email is already registered!";
			exit();
		}
		else
		{
			$SQLstring = "Insert into customer values(null,'".$name."','".$password."','".$email."','".$phone."')";	
			$queryResult = @mysqli_query($DBConnect, $SQLstring)
			Or die ("<p>Unable to insert into the table.</p>"."<p>Error code ".
			mysqli_errno($DBConnect). ": ".mysqli_error($DBConnect)). "</p>";
			echo "Thank you for registration, Please check your email addess for a new mail";
		}
	}
?>

</HTML>