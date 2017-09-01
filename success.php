<?php
	session_start();

	$name=$_POST["name"];
	$address=$_POST["address"];
	$city=$_POST["city"];
	$zip=$_POST["zip"];
	$ccnum=$_POST["ccnum"];
	$exp=$_POST["exp"];

	$amount=$_SESSION["total"];

	include 'ccauthorize.php';

	//Check to ensure checkout button was clicked
	if(isset($_POST['checkout']))
	{
		//Ensure no empty fields were submitted
		if (empty($name) || empty($address) || empty($city) || empty($zip) || empty($ccnum) || empty($exp))
		{
			$_SESSION["errormessage"]="All fields must be filled!";
			header("location:javascript://history.go(-1)");
			exit();
		}

		//Ensure there are items in the cart
		elseif ($_SESSION["total"]==0)
		{
			$_SESSION["errormessage"]="Must select items before checking out!";
			header("location:javascript://history.go(-1)");
			exit();
		}

		//Ensure fields are formatted correctly
		elseif (strlen($exp)!=7 || strlen($ccnum)!=19 || strlen($zip)!=5)
		{
			$_SESSION["errormessage"]="Ensure all fields are filled correctly.";
			header("location:javascript://history.go(-1)");
			exit();
		}

		//Connection and updating of order may now proceed...
		else
		{
			//create connection
			$host="blitz.cs.niu.edu";
			$un="group7";
			$pw="groupSeven";
			$db="ugradseven";
			$port="3306";

			$connection=mysqli_connect($host, $un, $pw, $db, $port);

			$partlist="";

			$partbuffer=array();

			$partbuffer=$_SESSION["parts"];

			//Send updated parts list to string for sending
			$partlist=implode($partbuffer, ", ");

			$sql="INSERT INTO `ugradseven`.`orders` (`orderlist`, `name`, `address`, `city`, `zip`) VALUES ('$partlist', '$name', '$address', '$city', '$zip')";

			//Confirmation message sent if successful
			if (mysqli_query($connection, $sql))
			{
				echo "Order placed successfully!";
			}

			mysqli_close($connection);

			$authorization=sendDatagram($ccnum, $exp, $amount, $name);
			?>
			Transaction Details:<br>
			 <?php echo $authorization; ?><br>
			<?php
			//end session, clear all variables for new ordering session upon return
			session_destroy();
			?>

			<a href="index.php">Return to main page.</a>

			<?php

		}
	}
?>
