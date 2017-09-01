
<?php
function getFees()
{
	$host="blitz.cs.niu.edu";
	$db="ugradseven";
	$un="group7";
	$pw="groupSeven";
	$port="3306";

	$connection=mysqli_connect($host, $un, $pw, $db, $port);

	$sql="SELECT Tax FROM feeStore";

	$result=mysqli_query($connection, $sql);

	while($row=mysqli_fetch_array($result))
	{
		$_SESSION["tax"]=$row['Tax'];
	}

	$sql2="SELECT Shipping FROM feeStore";

	$result2=mysqli_query($connection, $sql2);

	while($row=mysqli_fetch_array($result2))
	{
		$_SESSION["shipping"]=$row['Shipping'];
	}
}

function computeCost()
{
	$_SESSION["total"]=$_SESSION["total"]+$_SESSION["shipping"]+$_SESSION["tax"];
}

function createOrder($orderlist,$name, $address, $city, $zip, $fulfilled)
{

	if (mysqli_query($connection, $sql))
	{
		$status=True;
	}

	return $status;
}
?>
