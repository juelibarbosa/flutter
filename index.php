<?php 
include_once('config.php');
// Include the PHP TwilioRest library 
include 'twilio/Services/Twilio.php';

$A = isset($_SESSION['auth']) ? $_SESSION['auth']:null;

$listenings = array();
if ($A)
{
	$listenings = getFriendsPicture($A['user']->id, $A['token'], 20);
}

function getFriends($fb_id, $token)
{
	$url = "https://graph.facebook.com/$fb_id/friends?access_token=$token";

	// Set query data here with the URL
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, '3');
	$response = json_decode(trim(curl_exec($ch)));
	curl_close($ch);

	return $response->data;
}

function getFriendsPicture($fb_id, $token, $limit = null)
{
	$friends = getFriends($fb_id, $token);
	shuffle($friends);

	$payload = array();
	$counter = 1;
	foreach ($friends as $friend)
	{
		$payload[$friend->id] = $friend;
		$payload[$friend->id]->picture = "https://graph.facebook.com/$friend->id/picture";

		if ($counter == $limit)
		{
			break;
		}

		$counter++;
	}

	return $payload;
}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Flutterly</title>
		<meta charset="utf-8"/>
		<meta name="language" content="en"/>
	    <link href="http://twitter.github.com/bootstrap/assets/css/bootstrap.css" rel="stylesheet" />
	    <link href="/css/app.css" rel="stylesheet" />

		<script type="text/javascript" src="http://static.twilio.com/libs/twiliojs/1.0/twilio.js"></script>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
		<script src="//connect.facebook.net/en_US/all.js"></script>
	</head>
	<body>
	<div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="#" title="Flutterly">
          	Flutterly
          </a>
          <div class="btn-group pull-right">

          	<?php if ($A): ?>         	

            <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
              <i class="icon-user"></i> <?php echo $A['user']->name; ?>
              <span class="caret"></span>
            </a>

            <ul class="dropdown-menu">
              <li class="divider"></li>
              <li><a href="logout.php">Sign Out</a></li>
            </ul>

          	<?php else: ?>
          	<p id="login">
          		<a href = "https://www.facebook.com/dialog/oauth/?client_id=354211487989261&redirect_uri=<?php echo $fb_redirect_url; ?>/fb.php">
          		<img src = "/img/fb.png" /> <img src = "/img/twitter.png" />
          		</a>
          	</p>
          	<?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row-fluid">
        <div class="span3">
          <div class="well sidebar-nav" id="profile">
     		<?php if ($A): ?>
     			<img id="fb_profile" src = "https://graph.facebook.com/<?php echo $A['user']->id; ?>/picture" />
     			<h2><?php echo $A['user']->name; ?></h2>
     			<p id="bio"><?php echo $A['user']->bio; ?></p>

     		<?php else: ?>
     			<h2>Anonymous Flutter</h2>
     			<hr />
     			<p id="bio">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin in velit tincidunt nulla pulvinar placerat. Nullam molestie luctus eros, eleifend fringilla nunc rutrum vel.</p>
 			<?php endif; ?>
			<p><img id="mic" src = "/img/mic.png" alt="Flutter Now!"/></p>

			<?php if($A): ?>
			<h2>Listening To...</h2>
			
			<?php foreach ($listenings as $listening): ?>
				<p>
					<img src = "<?php echo $listening->picture; ?>" />
					<?php echo $listening->name; ?>
				</p>
			<?php endforeach; ?>
			<?php endif; ?>

          </div><!--/.well -->
        </div><!--/span-->
        <div class="span9">
          <div class="hero-unit" id="stream">
            
	<?php
	$token = new Services_Twilio_Capability($accountSid, $authToken);
	$token->allowClientOutgoing($twilioAppId);
	$client = new Services_Twilio($accountSid, $authToken);
	?>

	<?php foreach($client->account->recordings as $key => $recording): ?>
	<?php $audio = $baseAPIUrl . $recording->uri; ?>
		<div class="row">
			<div class="span2 flutter_pic">
				<?php if ($A): ?>
    	 			<?php $flutters = $listenings; shuffle($flutters); ?>

    	 			<?php $img_url = $flutters[$key]->picture; ?>
					<img src = "<?php echo $img_url; ?>" />
				<?php else: ?>
					<img src = "/img/flutter.jpg" />
				<?php endif; ?>
				
			</div>

			<div class="span6">
			<p>
			<audio src="<?php echo $audio; ?>" controls preload="auto" autobuffer width="50px"></audio>
			<span class="date_created">
Curabitur in odio urna. Nam vel nisi magna, non adipiscing mi. Maecenas semper viverra purus vitae vestibulum.				
				<?php echo date('m/d/Y H:i a', strtotime($recording->date_created)); ?></span>
		</p>
			</div>
		</div>
		<hr />
	<?php endforeach; ?>            
    
    </div>
    <hr />

      <footer>
        <p>&copy; Flutterly <?php echo date('Y'); ?></p>
      </footer>

    </div><!--/.fluid-container-->

    <script src="http://twitter.github.com/bootstrap/assets/js/bootstrap-transition.js"></script>
    <script src="http://twitter.github.com/bootstrap/assets/js/bootstrap-alert.js"></script>
    <script src="http://twitter.github.com/bootstrap/assets/js/bootstrap-modal.js"></script>
    <script src="http://twitter.github.com/bootstrap/assets/js/bootstrap-dropdown.js"></script>
    <script src="http://twitter.github.com/bootstrap/assets/js/bootstrap-scrollspy.js"></script>
    <script src="http://twitter.github.com/bootstrap/assets/js/bootstrap-tab.js"></script>
    <script src="http://twitter.github.com/bootstrap/assets/js/bootstrap-tooltip.js"></script>
    <script src="http://twitter.github.com/bootstrap/assets/js/bootstrap-popover.js"></script>
    <script src="http://twitter.github.com/bootstrap/assets/js/bootstrap-button.js"></script>
    <script src="http://twitter.github.com/bootstrap/assets/js/bootstrap-collapse.js"></script>
    <script src="http://twitter.github.com/bootstrap/assets/js/bootstrap-carousel.js"></script>
    <script src="http://twitter.github.com/bootstrap/assets/js/bootstrap-typeahead.js"></script>
	</body>

	<script>
		$(function(){
			var connection=null;
			Twilio.Device.setup("<?php echo $token->generateToken();?>",{"debug":true});
			
			$("#call").click(function() {  
				Twilio.Device.connect();
			});
			$("#hangup").click(function() {  
  				connection.sendDigits("#");
			});

			Twilio.Device.ready(function (device) {
				$('#status').text('Ready to start recording');
			});

			Twilio.Device.offline(function (device) {
				$('#status').text('Offline');
			});

			Twilio.Device.error(function (error) {
				$('#status').text(error);
			});

			Twilio.Device.connect(function (conn) {
				connection=conn;
	  			$('#mic').attr("src", "/img/mic-live.png");
	  			toggleCallStatus();
			});

			Twilio.Device.disconnect(function (conn) {
	  			$('#mic').attr("src", "/img/mic.png");
			});
			
			function toggleCallStatus(){
				$('#call').toggle();
				$('#hangup').toggle();
			}

			$("#mic").click(function(){
				var src = $(this).attr("src");
				if (src == "/img/mic.png")
				{
					src = '/img/mic-live.png';
					Twilio.Device.connect();
				}
				else
				{
					connection.sendDigits("#");
					src = '/img/mic.png';
				}

				$(this).attr("src", src);
		  	});
		});
	</script>
</html>