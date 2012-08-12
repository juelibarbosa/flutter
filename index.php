<?php 
session_start();
$A = isset($_SESSION['auth']) ? $_SESSION['auth']:null;

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Flutterly</title>
	    <link href="http://twitter.github.com/bootstrap/assets/css/bootstrap.css" rel="stylesheet" />
	    <link href="/css/app.css" rel="stylesheet" />

		<script type="text/javascript" src="http://static.twilio.com/libs/twiliojs/1.0/twilio.js"></script>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
		<script src="//connect.facebook.net/en_US/all.js"></script>

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
          	<img src = "/img/logo.png" title="Flutterly" width="110px" />
          </a>
          <div class="btn-group pull-right">
          	<?php if ($A): ?>
            <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
              <i class="icon-user"></i> <?php echo $A['user']->name; ?>
              <span class="caret"></span>
            </a>

            <ul class="dropdown-menu">
              <li><a href="#">Profile</a></li>
              <li class="divider"></li>
              <li><a href="logout.php">Sign Out</a></li>
            </ul>

          	<?php else: ?>
          	<p id="login">
          		<a href = "https://www.facebook.com/dialog/oauth/?client_id=354211487989261&redirect_uri=http://flutter.phpfogapp.com/fb.php">
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
     			<h2><?php echo $A['user']->name; ?></h2>
     			<p id="bio"><?php echo $A['user']->bio; ?></p>

     		<?php else: ?>
     			<h2>Anonymous Flutter</h2>
     			<hr />
     			<p id="bio">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin in velit tincidunt nulla pulvinar placerat. Nullam molestie luctus eros, eleifend fringilla nunc rutrum vel.</p>
 			<?php endif; ?>
			<p><img id="mic" src = "/img/mic.png" alt="Flutter Now!"/></p>

          </div><!--/.well -->
        </div><!--/span-->
        <div class="span9">
          <div class="hero-unit" id="stream">
            
	<?php
    // Include the PHP TwilioRest library 
	include 'twilio/Services/Twilio.php';
    
	// facebook:
	$fbAPIKey = '354211487989261';
	$fbSecret = '3f71e62611254ff4fe09210955295686';

    // Twilio REST API version 
    $ApiVersion = "2010-04-01";
    
    // Set our AccountSid and AuthToken 
	$accountSid = 'AC701ddef9bce91404467507cd870034db';
	$authToken  = '1bd60ff00f687b84eea8408be49f6a71';
	$baseAPIUrl = 'https://api.twilio.com';

	$token = new Services_Twilio_Capability($accountSid, $authToken);
	$token->allowClientOutgoing('AP6ea1770e590447ce5081b6c8ab18d818');

	// @start snippet
    // Instantiate a new Twilio Rest Client 
	$client = new Services_Twilio($accountSid, $authToken);
	?>

	<?php foreach($client->account->recordings as $recording): ?>
	<?php $audio = $baseAPIUrl . $recording->uri; ?>
		<div class="row">
			<div class="span2 flutter_pic">
				<img src = "/img/flutter.jpg" />
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