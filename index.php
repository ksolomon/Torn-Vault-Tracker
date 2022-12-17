<?php
setlocale(LC_MONETARY, "en_US");
include('functions.php');

// Open CSV file in read & append mode ('a+')
$fp = fopen('vault.csv', 'a+');

$csvVault = [];
$csv = array();

$lines = file('vault.csv', FILE_IGNORE_NEW_LINES);

foreach ($lines as $key => $value) {
		$csv[$key] = str_getcsv($value);
}

function getLog($user) {
	$tornURL = 'https://api.torn.com/user/?selections=log&key=';
	$i = 0;

	if ($user == 'zarathos') {
		$usrKey = 'LDtibInYMdgVnM9n';
	} else {
		$usrKey = 'lne9TZLuqvFXtK3V';
	}

	$usrURL = $tornURL.$usrKey;
	$usrLog  = json_decode(file_get_contents($usrURL));
	$usrData = $usrLog->log;
	$usrVault = [];

	foreach ($usrData as $entry) {
		if ($entry->log == 5851) {
			$usrVault[$i]['user']      = $user;
			$usrVault[$i]['timestamp'] = $entry->timestamp;
			$usrVault[$i]['operation'] = $entry->title;
			$usrVault[$i]['amount']    = $entry->data->withdrawn;

			$csvComp = searchForId($entry->timestamp, $csv);

			if ($csvComp) {
				echo 'Withdrawal (ID: ' . $entry->timestamp . ') already in vault';
			}

			// fputcsv($fp, $usrVault[i]);
		} elseif ($entry->log == 5850) {
			$usrVault[$i]['user']      = $user;
			$usrVault[$i]['timestamp'] = $entry->timestamp;
			$usrVault[$i]['operation'] = $entry->title;
			$usrVault[$i]['amount']    = $entry->data->deposited;

			$csvComp = searchForId($entry->timestamp, $csv);

			if ($csvComp) {
				echo 'Deposit (ID: ' . $entry->timestamp . ') already in vault';
			}
		} else {
			continue;
		}

		$i++;
	}

	return $usrVault;
}

// Loop through file pointer and a line
// foreach ($zarVault as $fields) {
// 	fputcsv($fp, $fields);
// }

fclose($fp);

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
		setlocale(LC_MONETARY, "en_US");

		foreach ($zarVault as $entry) {
			echo '<tr>';
			echo '<td>';
			echo $entry['user'];
			echo '</td>';
			echo '<td>';
			echo date("d/m/Y", $entry['timestamp']);
			echo '</td>';
			echo '<td>';
			echo $entry['operation'];
			echo '</td>';
			echo '<td>';
			echo formatMoney($entry['amount']);
			echo '</td>';
			echo '</tr>';
		}
		?>
	</table>
</body>

</html>