<?php
	$host="blitz.cs.niu.edu";
	$port="3306";
	$un="student";
	$pw="student";
	$db="csci467";

	//Create connection
	$connection = mysqli_connect($host, $un, $pw, $db, $port);

	//Check if item was added on sidebar
	if (isset($_GET['action']) && $_GET['action']=="add")
	{
		$id=intval($_GET['id']);

		if (isset($_SESSION['cart'][$id]))
		{
			//Update quantity
			$_SESSION['cart'][$id]['quantity']++;
		}

		//If not previously selected, update cart with new item and quantity of 1
		else
		{
			$cartsql="SELECT * FROM parts WHERE number={$id}";
			$result=mysqli_query($connection, $cartsql);

			if (mysqli_num_rows($result) != 0)
			{
				$cartrow=mysqli_fetch_array($result);

				$_SESSION['cart'][$cartrow['number']]=array("quantity"=>1, "price" => $cartrow['price']);
			}

			//Do not allow user to add a product with an id not in the database
			else
			{
				$error="Incorrect product ID.";
			}
		}
	}
?>
			<h1>Parts Listing</h1>


			<?php
				if (isset($error))
				{
					echo"<h2>$error</h2>";
				}

			?>

			<table>
				<tr>
				<th> Preview </th>
				<th> Part Number</th>
				<th> Details </th>
				<th> Price </th>
				<th> Add </th>
				</tr>

				<?php

					//query database and retrieve
					$sql="SELECT * FROM parts";
					$query=mysqli_query($connection, $sql, MYSQLI_USE_RESULT);

					while ($row=mysqli_fetch_array($query, MYSQLI_BOTH))
					{
						?>

						<tr>
							<td><?php echo "<img src='images/default.png'" ?> height="75" width="75" </td>
							<td><?php echo $row['number'] ?> </td>
							<td><?php echo $row['description'] ?></td>
							<td>$<?php echo $row['price'] ?> </td>
							<td><a href="index.php?page=parts&action=add&id=<?php echo $row['number'] ?>">Add to Cart</a></td>
						</tr>

						<?php
					}
					?>
            </table>
