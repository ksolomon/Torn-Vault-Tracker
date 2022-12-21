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

			<?php vaultTxns($csv); ?>
		</div>

		<div class="gridRght">
			<h2>Balances</h2>

			<?php
			$zarVault = [];
			$symVault = [];

			
			?>
		</div>
	</div>
</body>

</html>