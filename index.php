<?php include('functions.php'); ?>

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

			<div class="grid">
				<?php
				$usr1Vault = splitVault($csv, '0', 'Zarathos');
				$usr2Vault = splitVault($csv, '0', 'Symos');

				$usr1Balance = userBalance($usr1Vault,3);
				$usr2Balance = userBalance($usr2Vault,3);

				echo '<h3>Zarathos balance: '.formatMoney($usr1Balance).'</h3>';
				echo '<h3>Symos balance: '.formatMoney($usr2Balance).'</h3>';
				?>
			</div>
		</div>
	</div>
</body>

</html>