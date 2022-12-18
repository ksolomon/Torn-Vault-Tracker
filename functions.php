<?php
setlocale(LC_MONETARY, "en_US");

// Open CSV file in read & append mode ('a+')
$fp = fopen('vault.csv', 'a+');

// Read transactions from CSV
$csvVault = [];
$csv      = [];

$lines = file('vault.csv', FILE_IGNORE_NEW_LINES);

foreach ($lines as $key => $value) {
  $csv[$key] = str_getcsv($value);
}

// Get user log entries
$zarTxn = getLog('zarathos', $csv);
$symTxn = getLog('symos', $csv);

// Merge user arrays into transaction array
$txnVault = array_merge($zarTxn, $symTxn);

// Build array of entries not already in the CSV
foreach ($txnVault as $key => $value) {
  $csvComp = searchForId($value['timestamp'], $csv);

  if (!$csvComp) {
    $csvVault[] = $value;
  }
}

// Store new trasnactions in CSV
foreach ($csvVault as $fields) {
	fputcsv($fp, $fields);
}

fclose($fp);

// Sort the CSV array
usort($csv, "custom_sort");

// Define the custom sort function
function custom_sort($a, $b) {
  return $a[1] > $b[1];
}

// searchForID(): Serach CSV array for timestamp ID
function searchForId($id, $array) {
  foreach ($array as $key => $val) {
    if ($val[1] == $id) {
      return true;
    }
  }

  return false;
}

// formatMoney(): Format raw transaction vaule to display as money
// cents: 0=never, 1=if needed, 2=always
function formatMoney($number, $cents = 1) {
  if (is_numeric($number)) {
    if (!$number) {
      $money = ($cents == 2 ? '0.00' : '0');
    } else {
      if (floor($number) == $number) {
        $money = number_format($number, ($cents == 2 ? 2 : 0));
      } else {
        $money = number_format(round($number, 2), ($cents == 0 ? 0 : 2));
      }
    }

    if (substr($number,0,1) == '-') {
      $money = ltrim($money, '-');
      $sign = '-$';
    } else {
      $sign = '$';
    }

    return $sign.$money;
  }
}

// getLog(): Get log entries for user. Returns array.
function getLog($user, $csv) {
  $tornURL = 'https://api.torn.com/user/?selections=log&key=';
  $i = 0;

  if ($user == 'zarathos') {
    $usrKey = 'LDtibInYMdgVnM9n';
    $user = 'Zarathos';
  } else {
    $usrKey = 'lne9TZLuqvFXtK3V';
    $user = 'Symos';
  }

  $usrURL = $tornURL . $usrKey;
  $usrLog  = json_decode(file_get_contents($usrURL));
  $usrData = $usrLog->log;
  $usrVault = [];

  foreach ($usrData as $entry) {
    if ($entry->log == 5851) {
      $usrVault[$i]['user']      = $user;
      $usrVault[$i]['timestamp'] = $entry->timestamp;
      $usrVault[$i]['operation'] = $entry->title;
      $usrVault[$i]['amount']    = '-'.$entry->data->withdrawn;
    } elseif ($entry->log == 5850) {
      $usrVault[$i]['user']      = $user;
      $usrVault[$i]['timestamp'] = $entry->timestamp;
      $usrVault[$i]['operation'] = $entry->title;
      $usrVault[$i]['amount']    = $entry->data->deposited;
    } else {
      continue;
    }

    $i++;
  }

  return $usrVault;
}

?>