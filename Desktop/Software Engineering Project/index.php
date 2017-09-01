<?php
	error_reporting(E_ALL ^ E_WARNING);
	session_start();

	$_SESSION["parts"]=array();

	if (isset($_GET['page']))
	{
		$pages=array("parts", "cart");

		if (in_array($_GET['page'], $pages))
		{
			$_page=$_GET['page'];
		}

		else
		{
			$_page="parts";
		}
	}

	else
	{
		$_page="parts";
	}
?>

<!DOCTYPE html>
<html>
<head>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="style.css" />

	<title> Loads 'o Parts</title>

</head>

<body>
	<div id="container">
		<div id="main">
			<?php require($_page.".php")?>
			</div><!--end of main-->

		<div id="sidebar">
			<h1>Cart</h1>
			<?php

				$host="blitz.cs.niu.edu";
				$db="csci467";
				$un="student";
				$pw="student";
				$port="3306";

				$conn=mysqli_connect($host, $un, $pw, $db, $port);

				if (isset($_SESSION['cart']))
				{
					$sql="SELECT * FROM parts WHERE number IN (";

					foreach ($_SESSION['cart'] as $id => $value)
					{
						$sql.=$id.",";
					}

					$sql=substr($sql, 0, -1).") ORDER BY description ASC";
					$newquery=mysqli_query($conn, $sql);

					while ($row=mysqli_fetch_array($newquery))
					{
						?>

						<p><?php echo $row['description'] ?> x <?php echo $_SESSION['cart'][$row['number']]['quantity'] ?></p>

						<?php
						array_push($_SESSION["parts"], $row['description'], $_SESSION['cart'][$row['number']]['quantity']);

					}
				?>

				<hr />
				<a href="index.php?page=cart">View My Cart</a>

				<?php
				}


				else
				{
					echo "<p>The cart is empty! Add some items.</p>";
				}

			?>

			</div><!--end of sidebar-->

		</div><!--end of container-->
</body>
</html>
