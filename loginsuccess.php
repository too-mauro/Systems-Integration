<!DOCTYPE html>
<html>
<body>
<head>
<div class="topnav">

<a class="active" href="loginsuccess.php">HOME</a> 
<a class="active" href="profile.php">PROFILE</a> 
<a class="active" href="buy.php">BUY</a> 
<a class="active" href="sell.php">SELL</a> 
<a class="active" href="graph.php">GRAPH</a> 
<a class="active" href="index.html">LOGOUT<a>
</div>

<h1> Welcome to the Stocks Page! </h1>
<p> Brought to you by: Potato Situation </p>
 <?php 
 session_start(); 
if ($_SESSION['logged'] == true) 
{
   echo "Welcome: ";
   echo $_SESSION["username"];
   echo "<br>";
   echo "News feed: (Powered by RSS)";   
//echo "USER NAME DISPLAYED";
//$user = $_SESSION["user"];
// echo $user;
}

//echo "USER NOT DISPLAYED";
 ?>
 
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
$client = new rabbitMQClient("testRabbitMQ.ini","testServer");
if (isset($argv[1]))
{
  $msg = $argv[1];
}
else
{
  $msg = "RSS";
}
//Send search request over
$request['type'] = "RSS";

$request['message'] = $msg;
$response = $client->send_request($request);
//PHP_EOL should echo in from backend
echo "".PHP_EOL;

//print_r($response['bal']);
echo"\n";
//Doing RSS from google news source
$xml=$response;

$xmlDoc = new DOMDocument();

$xmlDoc->load($xml);

//get elements from "<channel>"
$channel=$xmlDoc->getElementsByTagName('channel')->item(0);
$channel_title = $channel->getElementsByTagName('title')
->item(0)->childNodes->item(0)->nodeValue;
$channel_link = $channel->getElementsByTagName('link')
->item(0)->childNodes->item(0)->nodeValue;
$channel_desc = $channel->getElementsByTagName('description')
->item(0)->childNodes->item(0)->nodeValue;
//output elements from "<channel>"
$ee = ("<p><a href='" . $channel_link
  . "'>" . $channel_title . "</a>");
echo("<br>");
echo($channel_desc . "</p>");

//Output the 
$x=$xmlDoc->getElementsByTagName('item');
for ($i=0; $i<=10; $i++) {
  $item_title=$x->item($i)->getElementsByTagName('title')
  ->item(0)->childNodes->item(0)->nodeValue;
  $item_link=$x->item($i)->getElementsByTagName('link')
  ->item(0)->childNodes->item(0)->nodeValue;
  $item_desc=$x->item($i)->getElementsByTagName('description')
  ->item(0)->childNodes->item(0)->nodeValue;
  echo ("<p><a href='" . $item_link
  . "'>" . $item_title . "</a>");
  echo ("<br>");
  //echo ($item_desc . "</p>");
}
//END RSS FEATURE


?>


</head>
<body>
<h2> ___________________________ </h2>

<form method="POST">
<h2>Search a Stock:</h2>
	<input type="text" name="search1S" placeholder="Enter a stock symbol:" required>
	<button type="submit">Search</button><br>

</form>
<table class ="table table-bordered">


<form method="POST">
<h2>Stock Rate(NBBO):</h2>
	<input type="text" name="search1N" placeholder="Enter a stock symbol:" required>
	<button type="submit">Search</button>

</form>
    
<table class ="table table-bordered">
<thead>
<tr>
<th> Stock Info </th>
</tr>
</thead>
<tbody>

<?php
if(isset($_POST['search1S'])){
//here goes the function call
//Going through rabbitMQ
	//Grab required files 
	session_start();
	require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
$client = new rabbitMQClient("testRabbitMQ.ini","testServer");
if (isset($argv[1]))
{
  $msg = $argv[1];
}
else
{
  $msg = "search1S";
}
//Send search request over
$request['type'] = "search1S";
$request['search1S'] = $_POST['search1S'];
$request['message'] = $msg;
$response = $client->send_request($request);
//PHP_EOL should echo in from backend
echo "".PHP_EOL;
print_r($response);
echo"\n";
}
//Request Balance automatically
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
$client = new rabbitMQClient("testRabbitMQ.ini","testServer");
if (isset($argv[1]))
{
  $msg = $argv[1];
}
else
{
  $msg = "requestBalance";
}
//Send search request over
$request['type'] = "requestBalance";
$request['username'] = $_SESSION["username"];
$request['message'] = $msg;
$response = $client->send_request($request);
//PHP_EOL should echo in from backend
echo "".PHP_EOL;
echo "Balance is: ";
print_r($response['bal']);
echo"\n";
?>

<?php
if(isset($_POST['search1N'])){
//here goes the function call
//Going through rabbitMQ
	//Grab required files 
	session_start();
	require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
$client = new rabbitMQClient("testRabbitMQ.ini","testServer");
if (isset($argv[1]))
{
  $msg = $argv[1];
}
else
{
  $msg = "search1N";
}
//Send search request over
$request['type'] = "search1N";
$request['search1N'] = $_POST['search1N'];
$request['message'] = $msg;
$response = $client->send_request($request);
//PHP_EOL should echo in from backend
echo "".PHP_EOL;
print_r($response);
echo"\n";
}
?>


</tbody>
</table>
</body>
</html>