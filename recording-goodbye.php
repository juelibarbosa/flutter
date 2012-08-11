<?php  
header("Content-type: text/xml");
echo "<?xml version='1.0' encoding='ISO-8859-1'?>";
?>
<Response>
	<Say>Thanks for your recording.  Here is what I heard</Say>
	<Play><?php echo $_REQUEST['RecordingUrl']; ?></Play>
	<Say>Goodbye</Say>
</Response>