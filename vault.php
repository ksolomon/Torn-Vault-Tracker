<?php
$jsonMoney = file_get_contents('https://api.torn.com/user/?selections=money&key=LDtibInYMdgVnM9n');
$objMoney = json_decode($jsonMoney);

echo '<h1>User API - Money</h1>';
echo '<pre>';
print_r($objMoney);
echo '</pre>';

$jsonLog = file_get_contents('https://api.torn.com/user/?selections=log&key=LDtibInYMdgVnM9n');
$objLog = json_decode($jsonLog);

echo '<h1>User API - Log</h1>';
echo '<pre>';
print_r($objLog);
echo '</pre>';
?>