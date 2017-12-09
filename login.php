<HTML XMLns="http://www.w3.org/1999/xHTML">
<!--
	Functonality : This page is provided to customers and admin to login into the CabsOnlie application.
			 This page will take the email address and password from the user and varifies against the values stored in the database.
			 If the values match, Page is redirected to the Booking page for customers and admin page for Admin
			else, an error message is displayed.
--> 
  <head> 
    <title>Login Page</title> 
	<link rel="stylesheet" type="text/css" href="cabsonline.css">
  </head> 
  <body>
  <H1>CabsOnline</H1>
	
	<H2>Login to CabsOnline</H2>
  <form>
		<table> <tr> <td>		
		Email:</td><td><input type="text" name="emailfield"></td></tr>
		<tr><td>
		Password:</td><td><input type="password" name="passwordfield"> </label></td></tr>
		<tr><td>
		<input type="submit" value="Login" /></br></td><td></td></tr></table>
		<H2>New member? <a href="register.php">Register now</a></H2>
  </form>
  </body> 

<?php 
	//Check if email and pwd fields are sent
	if(isset($_GET['emailfield']) && isset($_GET['passwordfield']))
	{
		//check if email and passwords are provided as spaces, if yes trim them and store them in variables
		$email = trim($_GET['emailfield']);
		$password = trim($_GET['passwordfield']);
		//after triming check if they are empty
		if(empty($email) || empty($password))
		{
			echo "Please enter email address and password";
			exit();
		}
		else
		{
			//Call function to get the customerid, if customer is present in the database
			$Customer_No = ValidateUser($email,$password);
			//Check if database returned empty, that means customer is not registered
			if(!(empty($Customer_No)))
			{
				//if customer id is 1 then, he is treated as admin else normal customer
				if($Customer_No == 1)
				{
					
					//Redirect admin to admin page
					$host  = $_SERVER['HTTP_HOST'];
					$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
					$query_string = "admin.php";
					header("Location: http://$host$uri/$query_string");
				}
				else
				{
					//Redirect customers to Booking page
					$host  = $_SERVER['HTTP_HOST'];
					$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
					$query_string = 'booking.php?Customer_Id=' . $Customer_No;
					header("Location: http://$host$uri/$query_string");
				}
			}
		}
	}
	//function takes email address and password as input then retrieves the customer no from database and returns customer no
	function ValidateUser($email,$password)
	{
		$DBConnect = @mysqli_connect("mysql.ict.swin.edu.au", "s1784765","200385", "s1784765_db")
		Or die ("<p>Unable to connect to the database server.</p>". "<p>Error code ".
		mysqli_connect_errno().": ". mysqli_connect_error()). "</p>";
		//Construct the query based on passed email address and password
		$SQLstring = "select Customer_No from customer where Email = '".$email."' and Password = '".$password."'";
		//Execute the query at database
		$queryResult = @mysqli_query($DBConnect, $SQLstring)
		Or die ("<p>Unable to query the customer table.</p>"."<p>Error code ".
		mysqli_errno($DBConnect). ": ".mysqli_error($DBConnect)). "</p>";
		//Fetch the rows returned
		$row = mysqli_fetch_row($queryResult);
		//Check if the returned array has data i.e count
		if(count($row) > 0)
		{
			return $row[0];
		}
		else
		{
			echo "<br>Please provide registered email address and password";
			exit();
		}
	}
?>

</HTML>