<?php

require 'src/facebook.php';

$facebook = new Facebook(array(
  'appId'  => '484009128393436',
  'secret' => '723bae2fcb1a77078cfd032230982546',
));

// See if there is a user from a cookie
$user = $facebook->getUser();

if ($user) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $facebook->api('/me');
  } catch (FacebookApiException $e) {
    echo '<pre>'.htmlspecialchars(print_r($e, true)).'</pre>';
    $user = null;
  }
}

?>
<!DOCTYPE html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
<body onload="startstop();">

<head>
<title>Memory Game</title>
<link rel="image_src" href="prjkt2.jpg" />
<meta property=og:image content="prjkt2.jpg" /> 
 <meta name=description content="Compete with the FGC and see how well your memory and see if you can place the top 30"/>
<style type="text/css">
body{
	background-color: #000;
}
h1,h2,div#timer,div#tries{
	color:white;
	font-family:arial;}
	
div#memory_board{
	background:#ccc;
	border:#999 1px solid;
	width:800px;
	height:540px;
	padding:24px;
	margin:0px auto;
}
div#memory_board > div{
	background: url(tile_bg.jpg) no-repeat;
	border:#fff 1px solid;
	width:71px;
	height:71px;
	float:left;
	margin:10px;
	padding:20px;
	font-size:64px;
	cursor:pointer;
	text-align:center;
}
</style>
<script>
var name = prompt("Enter your name to play");

var sec = 0;
function startstop(){
	clock = setInterval("stopWatch()", 1000);
}
	function stopWatch() {
	sec++;
	document.getElementById("timer").innerHTML = sec;
}

var memory_array = ['A','A','B','B','C','C','D','D','E','E','F','F','G','G','H','H','I','I','J','J','K','K','L','L'];
var memory_values = [];
var memory_tile_ids = [];
var tiles_flipped = 0;
var number_tries = 0;
Array.prototype.memory_tile_shuffle = function(){
    var i = this.length, j, temp;
    while(--i > 0){
        j = Math.floor(Math.random() * (i+1));
        temp = this[j];
        this[j] = this[i];
        this[i] = temp;
    }
}
function newBoard(){
	tiles_flipped = 0;
	var output = '';
    memory_array.memory_tile_shuffle();
	for(var i = 0; i < memory_array.length; i++){
		output += '<div id="tile_'+i+'" onclick="memoryFlipTile(this,\''+memory_array[i]+'\')"></div>';
	}
	document.getElementById('memory_board').innerHTML = output;
}
function memoryFlipTile(tile,val){


	if(tile.innerHTML == "" && memory_values.length < 2){
		tile.style.background = '#FFF';
		tile.innerHTML = val;
		if(memory_values.length == 0){
			memory_values.push(val);
			memory_tile_ids.push(tile.id);
		} else if(memory_values.length == 1){
			memory_values.push(val);
			memory_tile_ids.push(tile.id);
			number_tries++;
			document.getElementById("tries").innerHTML = number_tries;
			if(memory_values[0] == memory_values[1]){
				tiles_flipped += 2;
				// Clear both arrays
				memory_values = [];
            	memory_tile_ids = [];
				// Check to see if the whole board is cleared
				if(tiles_flipped == memory_array.length){
				var score = (200 - sec) + (100 - number_tries);
					url = 'write.php' + 
							'?moves=' + number_tries +
							'&time=' + sec +
							'&score=' + score +
							'&name=' + encodeURIComponent(name);
					window.location.href = url;
				}
			} else {
				function flip2Back(){
				    // Flip the 2 tiles back over
				    var tile_1 = document.getElementById(memory_tile_ids[0]);
				    var tile_2 = document.getElementById(memory_tile_ids[1]);
				    tile_1.style.background = 'url(tile_bg.jpg) no-repeat';
            	    tile_1.innerHTML = "";
				    tile_2.style.background = 'url(tile_bg.jpg) no-repeat';
            	    tile_2.innerHTML = "";
				    // Clear both arrays
				    memory_values = [];
            	    memory_tile_ids = [];
				}
				setTimeout(flip2Back, 500);
			}
		}
	}
}



</script>
</head>



    <?php if ($user) { ?>

		<center><h1>TEST YOUR MEMORY</h1>
<h2><?php echo $user_profile['name']; ?></h2></center>
<div id="memory_board"></div>
<script>newBoard();</script>
<br><center>
<input type="button" value="Reload Board" onClick="history.go(0)">
<br><br>
	<h1>Time</h1><div id="timer">0</div>
	<h1>Tries</h1><div id="tries">0</div>
	</center>
		
		
		
		
		

    <?php } else { ?>
      <fb:login-button></fb:login-button>
    <?php } ?>
    <div id="fb-root"></div>
    <script>
      window.fbAsyncInit = function() {
        FB.init({
          appId: '<?php echo $facebook->getAppID() ?>',
          cookie: true,
          xfbml: true,
          oauth: true
        });
        FB.Event.subscribe('auth.login', function(response) {
          window.location.reload();
        });
        FB.Event.subscribe('auth.logout', function(response) {
          window.location.reload();
        });
      };
      (function() {
        var e = document.createElement('script'); e.async = true;
        e.src = document.location.protocol +
          '//connect.facebook.net/en_US/all.js';
        document.getElementById('fb-root').appendChild(e);
      }());
    </script>
  </body>
</html>
