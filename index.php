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

	<link rel="stylesheet" href="style.css?v=<?php echo filemtime('style.css'); ?>">
</head>

<body>
	<h1>Torn Vault Tracker</h1>

	<div class="grid">
		<div class="gridLeft">
			<h2>Transactions</h2>

			<table>
				<thead>
					<td>User</td>
					<td>Timestamp</td>
					<td>Operation</td>
					<td>Amount</td>
				</thead>

				<?php
				foreach ($csv as $entry) {
					if ($entry[2] == 'Vault withdraw') {
						$class = 'debit';
					} else {
						$class = 'credit';
					}

					echo '<tr class="' . $class . '">';
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
		</div>

		<div class="gridRght">
			<h2>Balances</h2>
		</div>
	</div>
</body>

</html>