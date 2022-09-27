<?php
$json = file_get_contents('https://api.torn.com/user/?selections=money&key=LDtibInYMdgVnM9n');
$objMoney = json_decode($json);

echo '<h1>User API - Money</h1>';
echo '<pre>';
print_r($objMoney);
echo '</pre>';

$json = file_get_contents('https://api.torn.com/user/?selections=money&key=LDtibInYMdgVnM9n');
$objLog = json_decode($json);

echo '<h1>User API - Log</h1>';
echo '<pre>';
print_r($objLog);
echo '</pre>';
?>