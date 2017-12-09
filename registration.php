<html>
<head>
<title>welcome to registration page</title></head>
<body> 
<center>
<h1>registration</h1>
</center>
<center>

<form method="post">
		<table> <tr> <td>		
		Name*:</td><td><input type="text" name="namefield" placeholder="name"> </label></td></tr>
		<tr><td>
		Password*:</td><td><input type="password" name="passwordfield" placeholder="password"> </label></td></tr>
		<tr><td>
		Confirm Password*:</td><td><input type="password" name="confirmpasswordfield"  placeholder="confirm password"> </label></td></tr>
		<tr><td>
		Email*:</td><td><input type="text" name="emailfield" placeholder="email"> </label></td></tr>
		<tr><td>
		Phone*:</td><td><input type="text" name="phonefield" placeholder="number"> </label></td></tr>
		<tr><td>
		<input type="submit" value="Register" /><a href="register1.php"></br></td><td></td></tr></table>
		
  </form>
  </center>
  </body>  
<?php 
	
	if(isset($_GET['namefield']) && isset($_GET['passwordfield']) && isset($_GET['confirmpasswordfield']) && isset($_GET['emailfield']) && isset($_GET['phonefield']))
	{
		
	
		$name = trim($_GET['namefield']);
		$password = trim($_GET['passwordfield']);
		$confirm_password = trim($_GET['confirmpasswordfield']);
		$email = trim($_GET['emailfield']);
		$phone = trim($_GET['phonefield']);
		
		if(empty($name) || empty($password) || empty($confirm_password) || empty($email) || empty($phone))
		{
			echo "ALL FIELDS ARE MANDATORY";
			exit();
		}
		else
		{
		
			if($password === $confirm_password)
			{
			     return true;
				}
				else
				{
					echo "Password and confirm passwords do not match";
					exit();
				}
			}
	}
	?>
	</html>