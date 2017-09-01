<h1>View Cart</h1>

<?php
	include 'customercontroller.php';

	//error with fetch array
	error_reporting(E_ALL ^ E_WARNING);

	//Ensure submit button is pressed and quantity is valid
	if(isset($_POST['submit']) && isset($_POST['quantity']))
	{
		foreach($_POST['quantity'] as $key => $val)
		{
			//Remove from cart if quantity is updated to zero
			if($val==0)
			{
				unset($_SESSION['cart'][$key]);
			}

			//Otherwise update with new amount
			else
			{
				$_SESSION['cart'][$key]['quantity']=$val;
			}
		}
	}

	//Display error message if anything is wrong on the cart page
	if(isset($_SESSION["errormessage"]))
	{
		echo $_SESSION["errormessage"];
	}

?>
<br/>
<a href="index.php?page=products">Return to All Parts.</a>
<form method="post" action="index.php?page=cart">

	<table>
		<tr>
			<th>Part</th>
			<th>Quantity</th>
			<th>Price</th>
			<th>Item Total</th>
		<tr>

		<?php

		//Make connection
		$host="blitz.cs.niu.edu";
            	$db="csci467";
		$un="student";
		$pw="student";
		$port="3306";

            	$conn=mysqli_connect($host, $un, $pw, $db, $port);

		$sql="SELECT * FROM parts WHERE number IN (";

		//Put items in cart sidebar
		foreach ($_SESSION['cart'] as $id => $value)
		{
			$sql.=$id.",";
		}

		//display in order
		$sql=substr($sql, 0, -1).") ORDER BY description ASC";
		$newquery=mysqli_query($conn, $sql);

		//Initiate total variables
                $total=0;
		$_SESSION["total"]=0;

		//Dynamically update total based on items in cart
		while ($row=mysqli_fetch_array($newquery))
		{
                	$sub=$_SESSION['cart'][$row['number']]['quantity']*$row['price'];
                        $total+=$sub;

			//Retrieve fees in database from feeStore, function in customercontroller.php
			$_SESSION["total"]=$total;
			getFees();

			//Compute cost of all items
			computeCost();
			?>

			<tr>
    	            		<td><?php echo $row['description'] ?></td>
                    		<td><input type="text" name="quantity[<?php echo $row['number'] ?>]" size="7" value=<?php echo $_SESSION['cart'][$row['number']]['quantity']?> /></td>
                    		<td>$<?php echo $row['price'] ?></td>
                    		<td>$<?php echo number_format((float)$_SESSION['cart'][$row['number']]['quantity']*$row['price'], 2, '.', ''); ?></td>

			</tr>
		<?php

		}

		//Display tax and shipping only if items are selected, format correctly with dollar sign and two decimal places
		    if(isset($_SESSION["tax"]) && isset($_SESSION["shipping"]) && $_SESSION["total"]!=0)
		    {
			?>

			<td>Shipping: $<?php echo number_format((double)$_SESSION["shipping"], 2, '.', ''); ?></td><br/>
			<td>Tax: $<?php echo number_format((double)$_SESSION["tax"], 2, '.', ''); ?></td><br/>

			<?php
		    }

		?>
                    <td><b>Amount Due: $<?php echo number_format((float)$_SESSION["total"], 2, '.', ''); ?></b></td>
	</table>

    <br/>

    <button type="submit" name="submit">Update Cart</button>
</form>
<br/>
<p> To remove an item, set its quantity to 0. </p>

<h1>Checkout Order</h1>

<form method="post" action="success.php">

	Name:<br>
	<input type="text" name="name"/><br>
	Address:<br>
	<input type="text" name="address"/><br>
	City:<br>
	<input type="text" name="city"/><br>
	ZIP Code:<br>
	<input type="text" name="zip"/><br>

<h1>Payment Info</h1>
	Credit Card Number:<br>
	<input type="text" name="ccnum"/><br>
	Expiration (MM/YYYY):<br>
	<input type="text" name="exp"/><br>
<button type="submit" name="checkout">Complete Order</button>
</form>
