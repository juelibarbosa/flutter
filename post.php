<?php  
header("Content-type: text/xml");
$response = '<?xml version="1.0" encoding="UTF-8"?>
<Response>
    <Say voice="woman">Flutter, say what you want to say!</Say>
    <Record
        action="goodbye.php"
        method="GET"
		finishOnKey="#"
        maxLength="30" />
    <Say>I did not hear a recording. Goodbye.</Say>
</Response>';

echo $response;

// save post to file:
file_put_contents(print_r($_POST, true), 'log.txt');