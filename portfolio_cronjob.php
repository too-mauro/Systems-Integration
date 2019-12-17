#!/usr/bin/php
<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
include('db.php');

function errorCheck($db) {
	if ($db->errno != 0) {
		echo "Failed to execute query:".PHP_EOL;
		echo __FILE__.':'.__LINE__.":error: ".$db->error.PHP_EOL;
		exit(0);
	}
}
//Get list of Users
global $db;
$ShowUsers = mysqli_query($db, "SELECT username FROM students");
$fetchUsers = array();
while ($row_user = mysqli_fetch_assoc($ShowUsers))
	$fetchUsers[] = $row_user["username"];
echo "User array compiled.";
echo"\n";
//For each User, collect array of stocks and send to DMZ
foreach ($fetchUsers as $User) {
	$ShowStocks = mysqli_query($db, "SELECT symbol FROM ".$User."_stocks");
	$fetchStocks = array();
	while ($row_stock = mysqli_fetch_assoc($ShowStocks))
		$fetchStocks[] = $row_stock["symbol"];
	//Start DMZ connection and return array of 
	$client = new rabbitMQClient("DMZRabbitMQ.ini","testServer");
	if (isset($argv[1]))
	{
	$msg = $argv[1];
	}
	else
	{
	$msg = "portfolioCron";
	}
	$request = array();
	$request['type'] = "portfolioCron";
	//send stock array to DMZ end
	$request['stockArray'] = $fetchStocks;
	$request['message'] = $msg;
	//recieve stock prices from DMZ as associative array
	$response = $client->send_request($request);
	//PHP_EOL should echo in from backend 
	//May induce unintended effects
	echo "".PHP_EOL;
	echo "Sent and recieved stock info.";
	echo "\n";
	//Here you deconstruct the array and assign the values to each stock in the user's tables.
}
errorCheck($db);
?>

