<!DOCTYPE html>
<html lang="en">
	<head>
		<title>
			My Flutters
		</title>
	    <link href="http://twitter.github.com/bootstrap/assets/css/bootstrap.css" rel="stylesheet">

	<body>
		<h1>My Flutters</h1>
		
		<p><a href = "record.php" class="btn">New Flutter</a></p>

	<?php
    // Include the PHP TwilioRest library 
	include 'twilio/Services/Twilio.php';
    
    // Twilio REST API version 
    $ApiVersion = "2010-04-01";
    
    // Set our AccountSid and AuthToken 
	$accountSid = 'AC37440124450021dbf4a74d71a1d9c467';
	$authToken  = 'e157f60d0c4a744e238dfa0aec525d58';
	$baseAPIUrl = 'https://api.twilio.com';

	// @start snippet
    // Instantiate a new Twilio Rest Client 
	$client = new Services_Twilio($accountSid, $authToken);
	echo ('<table class="table">');
	foreach($client->account->recordings as $recording) {
		//echo '<pre>' . var_dump($recording) . '</pre>'; exit;
		$audio = $baseAPIUrl . $recording->uri;

  		echo "<tr><td>{$recording->duration} seconds</td> ";
  		echo "<td><audio src=\"$audio\" controls preload=\"auto\" autobuffer></audio></td>";
  		echo "<td>{$recording->date_created}</td>";
  		echo "<td>{$recording->sid}</td></tr>";
	}
	echo ("<table>");
    ?>

	</body>
</html>