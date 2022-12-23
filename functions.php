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

// vaultTxns(): Show all vault transactions
function vaultTxns($csv) {
  $output = '';

  $output .= '<table>';
  $output .= '  <thead>';
  $output .= '    <td><h4>User</h4></td>';
  $output .= '    <td><h4>Timestamp</h4></td>';
  $output .= '    <td><h4>Operation</h4></td>';
  $output .= '    <td><h4>Amount</h4></td>';
  $output .= '  </thead>';

  $output .= '  <tbody>';

    foreach ($csv as $entry) {
      if ($entry[2] == 'Vault withdraw') {
        $class = 'debit';
      } else {
        $class = 'credit';
      }

      $output .= '<tr class="' . $class . '">';
      $output .= '<td>';
      $output .= $entry[0]; // User
      $output .= '</td>';
      $output .= '<td>';
      $output .= date("d/m/Y", $entry[1]); // Transaction Date
      $output .= '</td>';
      $output .= '<td>';
      $output .= $entry[2]; // Transaction Type
      $output .= '</td>';
      $output .= '<td>';
      $output .= formatMoney($entry[3]); // Transaction Amount
      $output .= '</td>';
      $output .= '</tr>';
    }

  $output .= '  </tbody>';

  $output .= '</table>';

  echo $output;
}

// splitVault(): split CSV array into two separate arrays based on the user
function splitVault($csv, $field, $user): array {
  $usrVault = [];
  $usr2Vault = [];

  foreach ($csv as $entry) {
    if (isset($entry[$field]) && $entry[$field] == $user) {
      $usr1Vault[] = $entry;
    } else {
      continue;
    }
  }

  return $usr1Vault;
}

// Calculate balance of arrays
function arrBalance($array, $field) {
  $total = 0;

  foreach ($array as $item) {
    $total += $item[$field];
  }

  return $total;
}

// Calculate user balances
function vaultBalances($csv) {
  $usr1        = 'Zarathos';
  $usr1Vault   = splitVault($csv, '0', $usr1);
  $usr1Balance = arrBalance($usr1Vault,3);
  $usr1Share   = formatMoney(500000000-$usr1Balance);

  $usr2        = 'Symos';
  $usr2Vault   = splitVault($csv, '0', $usr2);
  $usr2Balance = arrBalance($usr2Vault,3);
  $usr2Share   = formatMoney(500000000-$usr1Balance);

  echo '<section class="user1">';
  echo '  <h3>'.$usr1.' balance: '.formatMoney($usr1Balance).'</h3>';
  echo '  <h4>'.$usr1.' share left: '.$usr1Share.'<h4>';
  echo '</section>';

  echo '<section class="user2">';
  echo '  <h3>'.$usr2.' balance: '.formatMoney($usr2Balance).'</h3>';
  echo '  <h4>'.$usr2.' share left: '.$usr2Share.'<h4>';
  echo '</section>';
}
?>