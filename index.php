<?php
include('functions.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Torn Vault Tracker</title>

	<link rel="stylesheet" href="style.css">
</head>

<body>
	<h1>Torn Vault Tracker</h1>

	<table>
		<thead>
			<td>User</td>
			<td>Timestamp</td>
			<td>Operation</td>
			<td>Amount</td>
		</thead>

		<?php
		// Sort the multidimensional array
		usort($csv, "custom_sort");

		// Define the custom sort function
		function custom_sort($a, $b) {
			return $a[1] > $b[1];
		}

		foreach ($csv as $entry) {
			echo '<tr>';
			echo '<td>';
			echo $entry[0];
			echo '</td>';
			echo '<td>';
			echo date("d/m/Y", $entry[1]);
			echo '</td>';
			echo '<td>';
			echo $entry[2];
			echo '</td>';
			echo '<td>';
			echo formatMoney($entry[3]);
			echo '</td>';
			echo '</tr>';
		}
		?>
	</table>
</body>

</html>