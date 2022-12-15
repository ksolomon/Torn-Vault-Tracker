<?php
setlocale(LC_MONETARY, "en_US");

$zarURL  = 'https://api.torn.com/user/?selections=log&key=LDtibInYMdgVnM9n';
$zarLog  = json_decode(file_get_contents($zarURL));
$zarData = $zarLog->log;

$symURL  = 'https://api.torn.com/user/?selections=log&key=lne9TZLuqvFXtK3V';
$symLog  = json_decode(file_get_contents($symURL));
$symData = $symLog->log;

$zarVault = [];
$symVault = [];
$csvVault = [];

// Open CSV file in read & append mode ('a+')
$fp = fopen('vault.csv', 'a+');

$csv = array();
$lines = file('vault.csv', FILE_IGNORE_NEW_LINES);

foreach ($lines as $key => $value) {
		$csv[$key] = str_getcsv($value);
}

$i = 0;

foreach ($zarData as $entry) {
	if ($entry->log == 5851) {
		$zarVault[$i]['user']      = 'Zarathos';
		$zarVault[$i]['timestamp'] = $entry->timestamp;
		$zarVault[$i]['operation'] = $entry->title;
		$zarVault[$i]['amount']    = $entry->data->withdrawn;

		$csvComp = searchForId($entry->timestamp, $csv);

		if ($csvComp) {
			echo "Entry ".$entry->timestamp.' already in vault';
		}
		// fputcsv($fp, $zarVault[i]);
	} elseif ($entry->log == 5850) {
		$zarVault[$i]['user']      = 'Zarathos';
		$zarVault[$i]['timestamp'] = $entry->timestamp;
		$zarVault[$i]['operation'] = $entry->title;
		$zarVault[$i]['amount']    = $entry->data->deposited;

		$csvComp = searchForId($entry->timestamp, $csv);

		if ($csvComp) {
			echo "Entry " . $entry->timestamp . ' already in vault';
		}

	} else {
		continue;
	}

	$i++;
}

// Loop through file pointer and a line
// foreach ($zarVault as $fields) {
// 	fputcsv($fp, $fields);
// }

fclose($fp);

function searchForId($id, $array) {
	foreach ($array as $key => $val) {
		if ($val[1] == $id) {
			return $key;
		}
	}

	return null;
}

function formatMoney($number, $cents = 1) { // cents: 0=never, 1=if needed, 2=always
	if (is_numeric($number)) { // a number
		if (!$number) { // zero
			$money = ($cents == 2 ? '0.00' : '0'); // output zero
		} else { // value
			if (floor($number) == $number) { // whole number
				$money = number_format($number, ($cents == 2 ? 2 : 0)); // format
			} else { // cents
				$money = number_format(round($number, 2), ($cents == 0 ? 0 : 2)); // format
			} // integer or decimal
		} // value
		return '$' . $money;
	} // numeric
} // formatMoney

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Torn Vault Tracker</title>

	<style>
		table,
		tr,
		td {
			border: 1px solid #333;
			border-collapse: collapse;
		}

		thead td {
			font-weight: bold;
			padding: .15em 1em;
			text-align: center;
			text-transform: uppercase;
		}

		td {
			padding: .15em 1em;
		}
	</style>
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