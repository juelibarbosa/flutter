<?php

$response = '<?xml version="1.0" encoding="UTF-8"?>
<Response>
    <Say voice="woman">Say what you want to say</Say>
    <Record maxLength="20" />
</Response>';

echo $response;

// save post to file:
file_put_contents(print_r($_POST, true), 'log.txt');