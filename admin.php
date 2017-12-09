<HTML XMLns="http://www.w3.org/1999/xHTML"> 
<!--
	Functonality : This page is provided to admin to login into the CabsOnlie application.
			 This page will display all the bookings which are unassigned and pickup time less than 2 hours from now.
			Admin can assign the cabs based on the reference number displayed in the table.
--> 
  <head> 
    <title>Admin Page</title> 
		<link rel="stylesheet" type="text/css" href="cabsonline.css">
  </head> 
  <body>
  <H1>CabsOnline</H1>
	<H2>Admin page of CabsOnline</H2>
	<H3>1. Click below button to search for all unassigned booking requests with a pick-up time within 2 hours.</H3>

  <form>
		<input type="submit" value="List All" name="submit"/>
  </form>
	
  </body> 

<?php 
	//Check if the reference id is provided for updating the booking status
	if(isset( $_GET['submit']) || isset($_GET['update']))
	{
		if(isset($_GET['reference_id']))
		{	
			//If reference number is provided update the status of the reference number
			$DBConnect = @mysqli_connect("mysql.ict.swin.edu.au", "s1784765","200385", "s1784765_db")
			Or die ("<p>Unable to connect to the database server.</p>". "<p>Error code ".
			mysqli_connect_errno().": ". mysqli_connect_error()). "</p>";
			$SQLstring = "SELECT COUNT(*) FROM Booking where Booking_Number='".$_GET['reference_id']."'";
			$queryResult = @mysqli_query($DBConnect, $SQLstring)
			Or die ("<p>Unable to query the Booking table.</p>"."<p>Error code ".
			mysqli_errno($DBConnect). ": ".mysqli_error($DBConnect)). "</p>";
			$row = mysqli_fetch_row($queryResult);
			if($row[0] > 0)
			{
				$SQLstring = "UPDATE Booking SET Booking_Status='assigned' where Booking_Number=".$_GET['reference_id'];
				$queryResult = @mysqli_query($DBConnect, $SQLstring)
				Or die ("<p>Unable to update the Booking table.</p>"."<p>Error code ".
				mysqli_errno($DBConnect). ": ".mysqli_error($DBConnect)). "</p>";
				echo "Reference number: <b>" . $_GET['reference_id'] . "</b> is assigned successfully";
				ListAllBookings();
			}
			else
			{
				echo "Please provide valid reference number<br><br><a href=login.php>Sign out</a>";
				exit();
			}
		}
		else
		{
			ListAllBookings();
		}
	}
	function ListAllBookings()
	{
		//Build the where clause since it requires date formating
		$TodayDate = date('Y-n-j');
		$StartTime = date('H:i:s');
		$EndTime = date('H:i:s',strtotime('+120 minute'));
		$dateClause = " AND B.Pickup_Date = '$TodayDate' AND B.Pickup_Time < '$EndTime' AND B.Pickup_Time > '$StartTime'"; 
		$TableName = "Booking or customer";
		$DBConnect = @mysqli_connect("mysql.ict.swin.edu.au", "s1784765","200385", "s1784765_db")
		Or die ("<p>Unable to connect to the database server.</p>". "<p>Error code ".
		mysqli_connect_errno().": ". mysqli_connect_error()). "</p>";
		$SQLstring = "SELECT B.Booking_Number,C.Customer_Name,B.Passenger_Name,B.Passenger_Phone,B.Unit_Number,B.Street_Number,
				B.Street_Name,B.Suburb,B.Destination_Suburb,B.Pickup_Date,B.Pickup_Time 
				FROM Booking B,customer C WHERE B.Customer_ID = C.Customer_No AND B.Booking_Status = 'unassigned'".$dateClause;
		//echo $SQLstring; 
		$queryResult = @mysqli_query($DBConnect, $SQLstring)
		Or die ("<p>Unable to query the $TableName table.</p>"."<p>Error code ".
		mysqli_errno($DBConnect). ": ".mysqli_error($DBConnect)). "</p>";
		$row = mysqli_fetch_row($queryResult);
		//Check if there are any bookings 
		if(count($row) > 0)
		{
			echo "<table width='100%' border='1'>";
			echo "<th>Reference #</th><th>Customer name</th><th>Passenger name</th><th>Passenger contact phone</th><th>Pick-up address</th>
			<th>Destination suburb</th><th>Pick-up time</th>";
			while ($row) 
			{	 
			
				echo "<tr><td>{$row[0]}</td>"; 
				echo "<td>{$row[1]}</td>"; 
				echo "<td>{$row[2]}</td>";
				echo "<td>{$row[3]}</td>";
				if(empty($row[4]))
					$address = $row[5]." ".$row[6].",".$row[7];
				else
					$address = $row[4]."/".$row[5]." ".$row[6].",".$row[7];
				echo "<td>$address</td>";
				echo "<td>{$row[8]}</td>";
				$dt = $row[9].":".$row[10];
				$dt = date_create_from_format('Y-n-j:H:i:s',$dt);
				$dt = date_format($dt,'d M H:i');
				echo "<td> $dt </td></tr>"; 
				$row = mysqli_fetch_row($queryResult);
			}
			echo "</table><br/><br/>";
			echo "<form><H3>2. Input a reference number below and click \"update\" button to assign a taxi to that request.</H3><br/>";
			echo "Reference number:<input type=\"text\" name=\"reference_id\"/> <input type=\"submit\" value=\"Update\" name=\"update\"/><br><br><a href=login.php>Sign out</a></form>";
		
		}
		else
		{
			echo "<H3> There are no pickups within 2 hours from now </H3><br><br><a href=login.php>Sign out</a>";
		}
		mysqli_close($DBConnect);
	}
?>

</HTML>