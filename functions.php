<?php
setlocale(LC_MONETARY, "en_US");

// Open CSV file in read & append mode ('a+')
$fp = fopen('vault.csv', 'a+');

$csvVault = [];
$csv      = [];

$lines = file('vault.csv', FILE_IGNORE_NEW_LINES);

foreach ($lines as $key => $value) {
  $csv[$key] = str_getcsv($value);
}

$zarTxn = getLog('zarathos', $csv);
$symTxn = getLog('symos', $csv);

$txnVault = array_merge($zarTxn, $symTxn);

foreach ($txnVault as $key => $value) {
  $csvComp = searchForId($value['timestamp'], $csv);

  if (!$csvComp) {
    $csvVault[] = $value;
  }
}

// Loop through file pointer and add a line
foreach ($csvVault as $fields) {
	fputcsv($fp, $fields);
}

fclose($fp);

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

    return '$' . $money;
  }
}

// getLog(): Get log entries for user. Uses CSV array for dupe checking.  Returns array.
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
      $usrVault[$i]['amount']    = $entry->data->withdrawn;

      $csvComp = searchForId($entry->timestamp, $csv);

      if ($csvComp) {
        //echo 'Withdrawal (ID: ' . $entry->timestamp . ') already in vault';
      }

      // fputcsv($fp, $usrVault[i]);
    } elseif ($entry->log == 5850) {
      $usrVault[$i]['user']      = $user;
      $usrVault[$i]['timestamp'] = $entry->timestamp;
      $usrVault[$i]['operation'] = $entry->title;
      $usrVault[$i]['amount']    = $entry->data->deposited;

      $csvComp = searchForId($entry->timestamp, $csv);

      if ($csvComp) {
        //echo 'Deposit (ID: ' . $entry->timestamp . ') already in vault';
      }
    } else {
      continue;
    }

    $i++;
  }

  return $usrVault;
}

?>