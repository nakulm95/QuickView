<?php

$useragent=$_SERVER['HTTP_USER_AGENT'];
if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
header('Location: http://fathomless-spire-1079.herokuapp.com/mobile.php');

?>

<!DOCTYPE html>
<html>
<head>
	<title>Quick View</title>
    <!-- CSS / JS imports  -->
    <link href="css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="css/bootstrap-responsive.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
    <link href="css/index.css" rel="stylesheet" type="text/css">
    <link href="icon.png" rel="icon" type="image/png" />

    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
    <script src="css/toggle.js" type="text/javascript"></script>
	<script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script> <!-- Map Script -->
    <script>
		function initialize() {
			/*
			var map_canvas = document.getElementById('map_canvas');
			var mapOptions = {
				center: new google.maps.LatLng(44.5403, -78.5463),
				zoom: 8,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			}
			var map = new google.maps.Map(map_canvas, mapOptions);
		}
		google.maps.event.addDomListener(window, 'load', initialize);
		*/
		}
	</script>
    <style>
		#loggedIn{display:none;}
		#noParam{display:none;}
		#login{display:block;}
		#loginAfter{display:none;margin-bottom:10px;position:relative;bottom:0;left:0}
		#processing{display:none;}
		#map_canvas {
			width: 300px;
			height: 300px;
		}
	</style>
    <?php
	$since = "";
	$name = "";
		if (isset($_POST['name'])) {
			$name = $_POST['name'];
		}
		if (isset($_POST['since']))
			$since = $_POST['since'];
	?>
</head>

<body>
<div id="fb-root"></div>



<script>
var loggedIn = false;

var pictures = new Array();
var picIndex = 4;
var totalLikes = new Array();
var totalComments = new Array();

function sendFinalData(pics, likes, comments) {
	pictures = pics;
	totalLikes = likes;
	totalComments = comments;
}

function removeUploaded() {
	document.getElementById('removeUploaded').innerHTML="Removing...";
	var string = document.getElementById('uploadedPics').innerHTML;
	if (picIndex > 4) {
		string = string.substr(0, string.lastIndexOf('<div class="row">'));
		document.getElementById('uploadedPics').innerHTML=string;
		picIndex -= 3;
	}
	if (picIndex > 4)
		document.getElementById('removeUploaded').innerHTML="Show Less";
	else 
		document.getElementById('removeUploaded').innerHTML="";
}

function loadMoreUploaded() {
	document.getElementById('moreUploaded').innerHTML="Loading...";
	document.getElementById('uploadedPics').innerHTML += '<div class="row">';
	for (var i = 0; i < 3; i++) {
		if (picIndex <= pictures.length) {
			document.getElementById('uploadedPics').innerHTML+='<div class="col-sm-4 col-xs-6">' +
																	'<div class="panel panel-default">' + 
																		'<div class="panel-thumbnail">' + 
																			'<img src="' + pictures[picIndex - 1][0] + '" class="img-responsive"></div>' + 
																		'<div class="panel-body">' + 
																			 '<p class="lead" id="pic_name_3">' + pictures[picIndex - 1][1] + ' likes</p>' + 
																		 '</div>' + 
																	 '</div>' + 
																'</div>';
			picIndex++;
		} else {
			document.getElementById('moreUploaded').innerHTML="End of Pictures";
		}
	}
	document.getElementById('uploadedPics').innerHTML+='</div>';
	document.getElementById('removeUploaded').innerHTML="Show Less";
	if (picIndex <= pictures.length)
		document.getElementById('moreUploaded').innerHTML="Show More";
	else
		document.getElementById('moreUploaded').innerHTML="End of Pictures";

}
window.fbAsyncInit = function() {
	console.log("fbAsyncInit");
	FB.init({
		appId      : '835981043095946',
		status     : true, // check login status
		cookie     : true, // enable cookies to allow the server to access the session
		xfbml      : true  // parse XFBML
	});
	
	FB.Event.subscribe('auth.authResponseChange', function(response) {
		if (response.status === 'connected') {
			console.log('Logged in');
			document.getElementById("login").style.display="none";
			document.getElementById("loginAfter").style.display="block";
			// drawUI();
			if (!loggedIn) {
				loggedIn = false;
				main();
			}
			testAPI();
			loggedIn = true;
		} else if (response.status === 'not_authorized') {
			console.log('Not Authorized');
			document.getElementById("login").style.display="block";
			FB.login();
		} else {
			console.log('Else');
			document.getElementById("login").style.display="block";
			FB.login();
		}
	});
	
	// If logged in, run the main script
	function main() {
		// Get name and time period from the $_POST variables
		var since = "<?= $since ?>";
		var name = "<?= $name ?>";
		// Only execute if there is a name parameter
		if (name.length != 0)
			getData(since, name);
		else {
			// If no name passed, show the welcome screen
			document.getElementById('noParam').style.display="block";
			var ids = new Array();
			$(function() {
				FB.api('/me/friends', function(response) {
					var names = new Array();
					for (var i = 0; i < response.data.length; i++)
						names[i] = response.data[i]['name'];
					addOwnName(names);
				});
			});
			console.log("no parameter passed");
		}
	}
	
	function getData(since, name) {
		if (since == "All")
			since = 0;
		else {
			selectCurrentTimeframe(since);
			since = (Math.floor(Date.now() / 1000)  - since);
		}
		//Decode the HTML-version of the name
		var name = "<?= html_entity_decode($name) ?>";
		// The previous line doesn't get all characters for some reason,
		// but printing the name in a hidden div and then grabbing it does
		// the trick.
		document.getElementById('nameParse').style.display="none";
		document.getElementById('nameParse').innerHTML = name;
		name = document.getElementById('nameParse').innerHTML;
		console.log(name);
		// Write searched for name in the header
		document.getElementById("current_name").innerHTML = name;
		// Get the name and id of yourself
		FB.api('me?fields=name,id', function(response) {
			var me = response.name;
			var meId = response.id;
			startSearch(name, since, me, meId);
		});
	}
	
	// Keep the dropdown item that was chosen by the user selected after
	// the name is passed
	function selectCurrentTimeframe(since) {
		document.getElementById('all').selected='not selected';
		if (since == '604800')
			document.getElementById('week').selected='selected';
		else if (since == '2629743')
			document.getElementById('month').selected='selected';
		else
			document.getElementById('year').selected='selected';
		// subtract the timeframe from the current time, which has to be converted from miliseconds to seconds
	}
	
	// Start collecting data by first getting all of the user's friends
	function startSearch(name, since, me, meId) {
		FB.api('me/friends?limit=6000&fields=name,id', function(response) {
			var names = new Array();
			var ids = new Array();
			for (var i = 0; i < response.data.length; i++) {
				names[i] = response.data[i].name;
				ids[i] = response.data[i].id;
			}
			// Add yourself to the array
			names[names.length] = me;
			ids[ids.length] = meId;
			if (name == me)
				id = meId;
			else
				var id = ids[names.indexOf(name)];
			// Create a copy of the names to sort and then become part of the search function
			var availableTags = names.slice();
			availableTags.sort();
			$( "#tags" ).autocomplete({
				source: availableTags
			});
			// Print an error if the name is not found and stop execution
			if (names.indexOf(name) == -1) {
				document.getElementById("invalid").innerHTML='Sorry, it looks like ' + name + ' is not a Facebook friend';
				return;
			}
			// Display the processing screen until everything has been calculated.
			// The response from the api call is what takes the most time
			document.getElementById('processing').style.display="block";
			var processingTime = Date.now();
			var timer = true;
			
			// Update the processing message after 5 seconds.
			// Runs no matter what, but for most of the cases it will be display:none
			// setTimeout(function() {moreTime(timer)}, 5000);
			//function moreTime(timer) {
			//	document.getElementById('processing').innerHTML="<h1>Still Processing...</h1><h2>(Lots of Pictures!)</h2>";
			//}
			
			// Keep the searched for name in the search box
			document.getElementById("tags").value = name;
			var first = name.substring(0, name.indexOf(" "));
			// Get the relationship status of the friend
			getLikes(id, since);
			getRelationshipStatus(id, name);
			getLocation(id);
			var pictures = new Array();
			var totalLikes = new Array();
			var totalComments = new Array();
			if (since == 0) {
				var call = id + "/albums?limit=1&fields=name,photos.limit(100).since(" + since + ").fields(source,likes.limit(1000),comments.limit(1000)),count";
				getPictures(id, call, 0, 0, since, pictures, totalLikes, totalComments, name);
			} else
				getPicturesOld(id, 0, since, pictures, totalLikes, totalComments, name, 0, 0);
		});
	}
	
	// Get a list of the liked pages of the user
	function getLikes(id, since) {
		FB.api((id + '/likes?since=' + since), function(response) {
			// If no likes print that out
			if (response.data.length == 0)
				document.getElementById('interest_1').innerHTML="No likes found";
			// Get 1-3 likes
			var index = Math.min(response.data.length, 3);
			for (var i = 0; i < index; i++) {
				document.getElementById('interest_' + (i + 1)).innerHTML=response.data[i].name;
			}
			// If there are less than 3 liked pages, print out a message in each slot
			if (index < 3)
				for (var i = 3; i > index; i--)
					document.getElementById('interest_' + i).innerHTML='-';
		});
	}
	
	function getRelationshipStatus(id, name) {
		FB.api((id + '?fields=relationship_status,significant_other'), function(response) {
			if (response.relationship_status != null) {
				var status = response.relationship_status;
				var so;
				if (response.significant_other != null)
					so = response.significant_other.name;
				var connector;
				var to;
				if (so != null) {
					if (status == "Married" || status == "Widowed" || status == "Separated" || status == "Divorced")
						connector = "to";
					else
						connector = "with";
				}
				var string = name + ' is ' + status.toLowerCase();
				if (connector != null)
					string += ' ' + connector + ' ' + so;
				string += '<img src="http://www.iconarchive.com/download/i66644/designbolts/free-valentine-heart/Heart-Shadow.ico" style="width: 40px" class="img-circle pull-right" />';
			} else
				var string = name + ' has no relationship status' + '<img src="http://www.iconarchive.com/download/i66644/designbolts/free-valentine-heart/Heart-Shadow.ico" style="width: 40px" class="img-circle pull-right" />';

			// Write relationship status to HTML
			console.log(string);
			document.getElementById("relationship_status").innerHTML = string;
		});
	}
	
	// Get most recent location of user
	function getLocation(id) {
		FB.api((id + '?fields=location'), function(response) {
			if (response['location'] != null)
				document.getElementById('location').innerHTML='<p style="margin:0;padding:0">' + response.location.name + '</p>';
			else
				document.getElementById('location').innerHTML="No Location Data";
		});
	}
	
	function getPictures(id, call, index, offset, since, pictures, totalLikes, totalComments, name) {
		if (index <=5000)
			document.getElementById('processing').innerHTML="<h1>Processing...</h1><h2>(" + (index) + " Pictures Processed)</h2>";
		else if (index > 5000 && index <= 10000)
			document.getElementById('processing').innerHTML="<h1>Processing...</h1><h2>(" + (index) + " Pictures Processed)</h2><h3>Really? Over 5000?</h3>";
		else
			document.getElementById('processing').innerHTML="<h1>Processing...</h1><h2>(" + (index) + " Pictures Processed)</h2><h3>Over 10,000???</h3>";
		FB.api(call, function(response) {
			var album;
			var dat = response.data[0];
			if (dat != null) {
				var photos;
				
				if ((dat['name']) != null) {
					console.log(dat.name + ": " + dat.count);
					if (dat.count > 0)
						if (dat.photos != null)
							photos = dat.photos.data;
						else {
							var nextCall = id + "/albums?offset=" + (offset + 1) + "&limit=1&fields=name,photos.limit(100).since(" + since + ").fields(source,likes.limit(1000),comments.limit(1000)),count";
						getPictures(id, nextCall, index, offset + 1, since, pictures, totalLikes, totalComments, name);
						return;
						}
					else {
						var nextCall = id + "/albums?offset=" + (offset + 1) + "&limit=1&fields=name,photos.limit(100).since(" + since + ").fields(source,likes.limit(1000),comments.limit(1000)),count";
						getPictures(id, nextCall, index, offset + 1, since, pictures, totalLikes, totalComments, name);
						return;
					}
				} else
					photos = response.data;
					
				if (photos != null) {
					for (var i = 0; i < photos.length; i++) {
						var likes = 0;
						var dat2 = photos[i];
						if (dat2.likes != null)
							likes = dat2.likes.data.length;
						pictures.push(new Array(dat2.source, likes));
						// For every like, record the name
						for (var j = 0; j < likes; j++) {
							var liker = dat2.likes.data[j].name;
							var found = false;
							for (var k = 0; k < totalLikes.length; k++) {
								if (totalLikes[k][0] == liker) {
									totalLikes[k][1]++;
									found = true;
									break;
								}
							}
							if (!found)
								totalLikes.push(new Array(liker, 1));
						}
						var comments = 0;
						if (dat2.comments != null) {
							comments = (dat2.comments.data).length;
						}
						// For every comment, record the name (if it isn't the person being viewed)
						for (var j = 0; j < comments; j++) {
							var commentor = dat2.comments.data[j].from.name;
							if (commentor != name) {
								var found = false;
								for (var k = 0; k < totalComments.length; k++) {
									if (totalComments[k][0] == commentor) {
										totalComments[k][1]++;
										found = true;
										break;
									}
								}
								if (!found)
									totalComments.push(new Array(commentor, 1));
							}
						}
					}
					index += photos.length;
				}
				console.log(index + " total pictures so far");
				
				if (dat.name != null) {
					if (dat.photos != null)
						page = dat.photos.paging;
					else {
						var nextCall = id + "/albums?offset=" + (offset + 1) + "&limit=1&fields=name,photos.limit(100).since(" + since + ").fields(source,likes.limit(1000),comments.limit(1000)),count";
						getPictures(id, nextCall, index, offset + 1, since, pictures, totalLikes, totalComments, name);
						return;
					}
				} else
					page = response.paging;
					
				if (page.next != null) {
					console.log("next: " + page.next);
					getPictures(id, page.next, index, offset, since, pictures, totalLikes, totalComments, name);
					return;
				} else {
					document.getElementById('processing').innerHTML="<h1>Processing...</h1><h2>(" + index + " Pictures Processed)";
					var nextCall = id + "/albums?offset=" + (offset + 1) + "&limit=1&fields=name,photos.limit(100).since(" + since + ").fields(source,likes.limit(1000),comments.limit(1000)),count";
					getPictures(id, nextCall, index, offset + 1, since, pictures, totalLikes, totalComments, name);
					return;
					// console.log("final count: " + index);
				}
			} else {
				console.log("Final picture count: " + index);
				processPictureData(id, since, pictures, totalLikes, totalComments, name);
			}
		});
	}
	
	function updateTicker(index) {
		console.log(index);
		if (index <=5000)
			document.getElementById('processing').innerHTML="<h1>Processing...</h1><h2>(" + (index) + " Pictures Processed)</h2>";
		else if (index > 5000 && index <= 10000)
			document.getElementById('processing').innerHTML="<h1>Processing...</h1><h2>(" + (index) + " Pictures Processed)</h2><h3>Really? Over 5000?</h3>";
		else
			document.getElementById('processing').innerHTML="<h1>Processing...</h1><h2>(" + (index) + " Pictures Processed)</h2><h3>Over 10,000???</h3>";
	}
	
	function getPicturesOld(id, offset, since, pictures, totalLikes, totalComments, name, index, lastIndex) {
		var lastIndex;
		if (index == 0)
				document.getElementById('processing').innerHTML="<h1>Processing...</h1><h2>(Waiting for response from server)</h2>";
		FB.api((id + "/photos/uploaded?limit=2500&since=" + since + "&offset=" + offset + "&fields=likes.limit(1000),comments.limit(1000),source"), function(response) {
			for (var i = 0; i < response.data.length; i++) {
				updateTicker(index);
				index++;
				var likes = 0;
				var dat = response.data[i];
				if (dat.likes != null)
					likes = dat.likes.data.length; //EDITED, CHECK
				pictures.push(new Array(dat.source, likes));
				for (var j = 0; j < likes; j++) {
					var liker = dat.likes.data[j].name;
					var found = false;
					for (var k = 0; k < totalLikes.length; k++) {
						if (totalLikes[k][0] == liker) {
							totalLikes[k][1]++;
							found = true;
							break;
						}
					}
					if (!found)
						totalLikes.push(new Array(liker, 1));
				}
				var comments = 0;
				if (dat.comments != null) {
					comments = (dat.comments.data).length;
				}
				for (var j = 0; j < comments; j++) {
					var commentor = dat.comments.data[j].from.name;
					if (commentor != name) {
						var found = false;
						for (var k = 0; k < totalComments.length; k++) {
							if (totalComments[k][0] == commentor) {
								totalComments[k][1]++;
								found = true;
								break;
							}
						}
						if (!found)
							totalComments.push(new Array(commentor, 1));
					}
				}
			}
			if (response.paging != null && response.paging['next'] != null)
				getPicturesOld(id, offset + 2500, since, pictures, totalLikes, totalComments, name, index, lastIndex);
			else
				processPictureData(id, since, pictures, totalLikes, totalComments, name);
		});
	}
	
	function processPictureData(id, since, pictures, totalLikes, totalComments, name) {
		pictures.sort((function(index){
				return function(a, b) {
					return (a[index] === b[index] ? 0 : (a[index] > b[index] ? -1 : 1));
				};
		})(1));
			
		// Display most popular uploaded pictures
		document.getElementById('uploaded').innerHTML=name.substring(0, name.indexOf(" ")) + "'s Most Popular Uploaded Pictures";
		index = Math.min(3, pictures.length);
		for (var i = 1; i <= index; i++) {
			var current_picture = document.getElementById(("pic_" + i));
			var current_name = document.getElementById(("pic_name_" + i));
			current_picture.src = pictures[i - 1][0];
			current_name.innerHTML = pictures[i - 1][1] + " likes";
		}
		if (index < 3) {
			for (var i = 3; i > index; i--) {
				document.getElementById('pic1' + i).innerHTML = '<p style="text-align:center;maring-top:20%;">No tagged picture in timeframe given</p>';
			}
		}
		sendFinalData(pictures, totalLikes, totalComments);
		getTagged(id, since, name);
		getStatuses(id, since, totalLikes, totalComments, name);
	}
	
	// Display the most popular pictures the person is tagged in (limited by FB to 400)
	function getTagged(id, since, name) {
		FB.api((id + "/photos/tagged?limit=400&fields=likes.limit(10000),source&since=" + since), function(response) {
			var taggedPictures = new Array();
			for (var i = 0; i < response.data.length; i++) {
				var likes;
				var dat = response.data[i];
				if (dat.likes != null)
					likes = dat.likes.data.length;
				else
					likes = 0;
				taggedPictures[i] = new Array(dat.source, likes);
			}
			
			// Sort the pictures based on the number of likes (index 1)
			taggedPictures.sort((function(index){
				return function(a, b) {
					return (a[index] === b[index] ? 0 : (a[index] > b[index] ? -1 : 1));
				};
			})(1));
			
			// Display most popular tagged photos
			document.getElementById('tagged').innerHTML=("Most Popular Pictures " + name.substring(0, name.indexOf(" ")) + "'s Tagged In");
			index = Math.min(3, taggedPictures.length);
			for (var i = 1; i <= index; i++) {
					var current_picture = document.getElementById(("pic2_" + i));
					var current_name = document.getElementById(("pic2_name_" + i));
				
					current_picture.src = taggedPictures[i - 1][0];
					current_name.innerHTML = taggedPictures[i - 1][1] + " likes";
			}
			if (index < 3) {
				for (var i = 3; i > index; i--) {
					document.getElementById('pic2' + i).innerHTML = '<p style="text-align:center">No tagged picture in timeframe given</p>';
				}
			}
		});
	}
	
	// Log likes and comments from user statuses
	function getStatuses(id, since, totalLikes, totalComments, name) {
		// Get the comment  data of statuses
		FB.api((id + "/statuses?limit=10000&fields=comments.limit(10000),from,likes.limit(10000),message&since=" + since), function(response) {
			var comment = new Array();
			var statuses = new Array();
			likecount = new Array();
			for (var i = 0; i < response.data.length; i++) {
				
				 /////////////////////////////
				////Most Popular Statuses////
				var message = response.data[i].message;
				if (message == null)
					message = "(Hidden/Deleted Status)"
				// convert new line characters into break tags
				message = message.replace(/\n/g, '<br />');
				var likes = 0;
				if (response.data[i].likes != null)
					likes = response.data[i].likes.data.length;
				statuses[i] = new Array(message, likes);
				
				 ///////////////////////////
				////Names of Commenters////
				if (response.data[i].comments != null) {
					comment[i] = response.data[i].comments.data; // returns the data array containing comments
					// For every comment on that status...
					for (var j = 0; j < comment[i].length; j++) {
						var commenter = comment[i][j].from.name;
						if (commenter != name) {
							var found = false;
							for (var k = 0; k < totalComments.length; k++) {
								if (totalComments[k][0] == commenter) {
									totalComments[k][1]++;
									found = true;
									break;
								}
							}
							if (!found)
								totalComments.push(new Array(commenter, 1));
						}
					}
				}
				 ///////////////////////
				////Names of likers////
				if (response.data[i].likes != null) {
					comment[i] = response.data[i].likes.data; // returns the data array containing likes
					for (var j = 0; j < comment[i].length; j++) {
						var liker = comment[i][j].name;
						var found = false;
						for (var k = 0; k < totalLikes.length; k++) {
							if (totalLikes[k][0] == liker) {
								totalLikes[k][1]++;
								found=true;
								break;
							}
						}
						if (!found) 
							totalLikes.push(new Array(liker, 1));
					}
				}
			}
			
			// Sort the statuses based on the number of likes
			statuses.sort((function(index){
				return function(a, b) {
					return (a[index] === b[index] ? 0 : (a[index] > b[index] ? -1 : 1));
				};
			})(1));
			
			index = Math.min(3, statuses.length);
			
			for (var i = 0; i < index; i++) {
				document.getElementById("status_likes_" + (i+1)).innerHTML = statuses[i][1] + " likes";
				document.getElementById("status_" + (i+1)).innerHTML = statuses[i][0];
			}
			if (index < 3) {
				for (var i = 3; i > index; i--) {
					document.getElementById("status_likes_" + i).innerHTML = '<h2>-</h2>';
					document.getElementById("status_" + i).innerHTML = 'No status in the timeframe given';
				}
			}
			
			// Sort total commenters based on number of comments
			totalComments.sort((function(index){
				return function(a, b) {
					return (a[index] === b[index] ? 0 : (a[index] > b[index] ? -1 : 1));
				};
			})(1));
			// Draw popularity graphs
			index = Math.min(4, totalComments.length);
			for (var i = 0; i < index; i++) {
				var num = parseInt(100.0 * totalComments[i][1] / totalComments[0][1], 10) + "%";
				document.getElementById("comments_graph_name_" + (i + 1)).innerHTML =  totalComments[i][0]+ " / " + totalComments[i][1];
				document.getElementById("comments_graph_" + (i + 1)).style.width = num;
			}
			
			// Sort the total likes
			totalLikes.sort((function(index){
				return function(a, b) {
					return (a[index] === b[index] ? 0 : (a[index] > b[index] ? -1 : 1));
				};
			})(1));
			// Most likes graph
			index = Math.min(4, totalLikes.length);
			for (var i = 1; i <= index; i++) {
				var num = parseInt(100.0 * totalLikes[i - 1][1] / totalLikes[0][1], 10) + "%";
				document.getElementById("likes_graph_name_" + i).innerHTML =  totalLikes[i - 1][0]+ " / " + totalLikes[i - 1][1];
				document.getElementById("likes_graph_" + i).style.width = num;
			}
			// Basically repeat the process with likes
			getFinalInfo(id, totalLikes, totalComments, since);
		});
	}
	
	function getFinalInfo(id, totalLikes, totalComments, since) {
		var total = new Array();
		for (var i = 0; i < totalLikes.length; i++) {
			total[i] = new Array(totalLikes[i][0], totalLikes[i][1], 0, 0);
		}
		for (var i = 0; i < totalComments.length; i++) {
			var found = false;
			var currentName = totalComments[i][0];
			var currentComments = totalComments[i][1];
			for (var j = 0; j < totalLikes.length; j++) {
				if (total[j][0] == currentName) {
					found = true;
					total[j][2] = currentComments;
					break;
				}
			}
			if (!found)
				total.push(new Array(currentName, 0, currentComments, 0));
		}
		for (var i = 0; i < total.length; i++) {
			total[i][3] = (total[i][1] + (total[i][2] * 1.5));
		}
		// Sorted list of "best" friends where 1 comment is equal to 1.5 likes
		total.sort((function(index){
			return function(a, b) {
				return (a[index] === b[index] ? 0 : (a[index] > b[index] ? -1 : 1));
			};
		})(3));
		// Write top friends
		index = Math.min(total.length, 3);
		for (var i = 0; i < index; i++) {
			document.getElementById("best_friend_" + (i + 1)).innerHTML = total[i][0];
			document.getElementById("friend_" + (i + 1)).innerHTML = total[i][2] + ' comments and ' + total[i][1] + ' likes';
		}
		if (index < 3) {
			for (var i = 3; i > index; i--) {
				document.getElementById("best_friend_" + i).innerHTML = '-';
				document.getElementById("friend_" + i).innerHTML = 'No friend for the timeframe given';
			}
		}
		getFriendUsers(id, since);
	}
	
	// Get the names of all the user's friends who use this app
	function getFriendUsers(id, since) {
		FB.api('/fql', { q:{"query1":"SELECT uid FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1=me())AND is_app_user=1"}}, function(response) {
			document.getElementById('users').innerHTML+='You are the only one out of all your friends that uses QuickView';
			var users = new Array();
			var links = new Array();
			var dat = response.data[0].fql_result_set;
			var len = 0;
			for (var i = 0; i < dat.length; i++) {
				var path = dat[i]['uid'];
				FB.api(path + '/', function(response) {
					links[len] = response['link'];
					users[len] = response.name;
					if (len == dat.length - 1) {
						document.getElementById('users').innerHTML="";
						for (var i = 0; i < len + 1; i++) {
							document.getElementById('users').innerHTML+='<a href="' + links[i] + '" target="_blank">' + users[i] + '</a>, ';
							timer = false;
						}
						document.getElementById('users').innerHTML+='and You use QuickView';
						document.getElementById("loggedIn").style.display="block";
						document.getElementById("processing").style.display="none";
					}
					len++;
				});
			}
			/*
			var call = id + "/albums?limit=1&fields=name,photos.limit(100).since(" + since + ").fields(source),count";
			getPictures(id, call, 0, 0, since);
			*/
			// Processing is over once this is done, so display the results
			if (dat.length == 0) {
				document.getElementById("loggedIn").style.display="block";
				document.getElementById("processing").style.display="none";
			}
		});
	}
	
	function addOwnName(names) {
		FB.api('/me?fields=name', function(response) {
			names.push(response.name);
			names.sort();
			$( "#tags" ).autocomplete({
				source: names
			});
		});
	}
};

// Load the SDK asynchronously
(function(d){
	var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
	if (d.getElementById(id)) {return;}
	js = d.createElement('script'); js.id = id; js.async = true;
	js.src = "https://connect.facebook.net/en_US/all.js";
	ref.parentNode.insertBefore(js, ref);
}(document));

// Here we run a very simple test of the Graph API after login is successful.
// This testAPI() function is only called in those cases.
function testAPI() {
	console.log('Welcome!  Fetching your information.... ');
	FB.api('/me', function(response) {
		console.log('Good to see you, ' + response.name + '.');
	});
}
</script>



<!-- WHERE THE HTML STARTS OMGGG -->
<div id="nameParse"></div>
<div id="banner">
	<img src="banner_binoc.png" alt="" />
    <p style="float:right;margin-right:10px;color:#23365d">Created by Aaron Gupta, Daniel Rahn, Eden<br />
    									Ghirmai, Nakul Malhotra, and Yezen Rashid</p>
</div>
<div id="wrapper">
    <div class="jumbotron hero-spacer" style="overflow:hidden">
        
        <div class="ui-widget" id="test">
            <form style="float: right; clear: right;" action="" method="post">
                <label for="tags">Search Friends: </label>
                <input id="tags" name="name" /> <br /> from
                <select name="since">
                    <option id="week" value="604800">The Past Week</option>
                    <option id="month" value="2629743">The Past Month</option>
                    <option id="year" value="31556926">The Past Year</option>
                    <option id="all" value="All" selected="selected">Profile Creation</option>
                </select>
                <input type="submit" />
            </form>
        <p id="invalid"></p>
        <!--
        <p class="muted credit"><fb:login-button show-faces="false" scope="basic_info, friends_photos, friends_status, friends_online_presence, friends_relationships, user_photos, user_status, user_relationships, user_likes, friends_likes, user_location, friends_location" width="300px"></fb:login-button></p> 
        -->
        
        
    	</div>
    	<h1 id="current_name" style="margin-top:-20px;font-family:'Myriad Pro','Lucidia Grande','Helvetica',sans-serif;font-weight:bold;"></h1>
    </div>
    
      
    <!--main-->
    <div class="container" id="main">
    	<div id="loggedIn">
      
      <div class="row">
       <div class="col-md-4 col-sm-6">
            <div class="panel panel-default">
              <div class="panel-heading"><a href="" class="pull-right"></a> <h4>Likes</h4></div>
            <div class="panel-body">
                  <div class="list-group" id="list-group-items"> 
                    <a href="#" class="list-group-item" id="interest_1"></a>
                    <a href="#" class="list-group-item" id="interest_2"></a>
                    <a href="#" class="list-group-item" id="interest_3"></a>
                  </div>
                </div>
          </div>
    
         
     
    
      </div>
        <div class="col-md-4 col-sm-6">
             
    
             <div class="panel panel-default">
               <div class="panel-heading"><a href="" class="pull-right"></a> <h4>Relationship Status: </h4></div>
            <div class="panel-body">
                  <p id="relationship_status"></p>
                  <div class="clearfix"></div>
                  <hr>
                 
                </div>
             </div>
          
    
        </div>
        <div class="col-md-4 col-sm-6">
             <div class="panel panel-default">
               <div class="panel-heading"><a href="" class="pull-right"></a> <h4>Most Recent Location</h4></div>
            <div class="panel-body">
                  <ul class="list-group">
                  <li class="list-group-item" id="location"></li>
                  </ul>
                 <!-- <div id="map_canvas"></div> -->
                </div>
          </div>
          
        </div>
      </div><!--/row-->
          <hr>
          
        <div class="row">
          <h2 id="tagged"></h2>
      
         <div class="col-sm-4 col-xs-6">
          
            <div class="panel panel-default">
              <div class="panel-thumbnail" id="pic21"><img id="pic2_1" src="" class="img-responsive"></div>
              <div class="panel-body">
                <p class="lead" id="pic2_name_1"></p>
    
              </div>
            </div>
    
            
          </div><!--/col-->
          
          <div class="col-sm-4 col-xs-6">
          
            <div class="panel panel-default">
              <div class="panel-thumbnail" id="pic22"><img id="pic2_2" src="" class="img-responsive"></div>
              <div class="panel-body">
                <p class="lead" id="pic2_name_2"></p>
              </div>
            </div>
    
            
          </div><!--/col-->
          
          <div class="col-sm-4 col-xs-6">
          
            <div class="panel panel-default">
              <div class="panel-thumbnail" id="pic23"><img id="pic2_3" src="" class="img-responsive"></div>
              <div class="panel-body">
                <p class="lead" id="pic2_name_3"></p>
              </div>
            </div>
    
          
          </div>
      </div>
    <hr>  
      
    <div id="uploadedPics">
      <div class="row">
          <h2 id="uploaded"></h2>
      
         <div class="col-sm-4 col-xs-6">
          
            <div class="panel panel-default">
              <div class="panel-thumbnail" id="pic11"><img id="pic_1" src="" class="img-responsive"></div>
              <div class="panel-body">
                <p class="lead" id="pic_name_1"></p>
    
              </div>
            </div>
    
            
          </div><!--/col-->
          
          <div class="col-sm-4 col-xs-6">
          
            <div class="panel panel-default">
              <div class="panel-thumbnail" id="pic12"><img id="pic_2" src="" class="img-responsive"></div>
              <div class="panel-body">
                <p class="lead" id="pic_name_2"></p>
              </div>
            </div>
    
            
          </div><!--/col-->
          
          <div class="col-sm-4 col-xs-6">
          
            <div class="panel panel-default">
              <div class="panel-thumbnail" id="pic13"><img id="pic_3" src="" class="img-responsive"></div>
              <div class="panel-body">
                <p class="lead" id="pic_name_3"></p>
              </div>
            </div>
          
          </div>
      </div>
      </div>
      <div class="more" id="moreUploaded" style="clear:both;text-align:center;" onclick="loadMoreUploaded()">Show More</div>
      <div class="less" id="removeUploaded" style="clear:both;text-align:center;" onclick="removeUploaded()"></div>
    <hr>
      
      
      <div class="row">
        <div class="col-md-12"><h2>Most Liked Posts</h2></div>
        <div class="col-md-4 col-sm-6">
          <div class="panel panel-default">
               <div class="panel-heading"> <h4 id="status_likes_1"></h4></div>
            <div class="panel-body">
                  <div class="clearfix"></div>
                  <p id="status_1"></p>
                  
                </div>
             </div> 
        </div>
        
            <div class="col-md-4 col-sm-6">
          <div class="panel panel-default">
               <div class="panel-heading"> <h4 id="status_likes_2"></h4></div>
            <div class="panel-body">
                  <div class="clearfix"></div>
                  <p id="status_2"></p>
                  
                </div>
             </div> 
        </div>
        
            <div class="col-md-4 col-sm-6">
          <div class="panel panel-default">
               <div class="panel-heading"> <h4 id="status_likes_3"></h4></div>
            <div class="panel-body">
                  <div class="clearfix"></div>
                  <p id="status_3"></p>
                  
                </div>
             </div> 
        </div>
      </div>
    
    <hr>   
    
    
          <div class="row">
        <div class="col-md-12"><h2>Best Friends</h2></div>
        <div class="col-md-4 col-sm-6">
          <div class="panel panel-default">
               <div class="panel-heading"> <h4 id="best_friend_1"></h4></div>
            <div class="panel-body">
                  <div class="clearfix"></div>
                  <p id="friend_1"></p>
                  
                </div>
             </div> 
        </div>
        
            <div class="col-md-4 col-sm-6">
          <div class="panel panel-default">
               <div class="panel-heading"> <h4 id="best_friend_2"></h4></div>
            <div class="panel-body">
                  <div class="clearfix"></div>
                  <p id="friend_2"></p>
                  
                </div>
             </div> 
        </div>
        
            <div class="col-md-4 col-sm-6">
          <div class="panel panel-default">
               <div class="panel-heading"> <h4 id="best_friend_3"></h4></div>
            <div class="panel-body">
                  <div class="clearfix"></div>
                  <p id="friend_3"></p>
                  
                </div>
             </div> 
        </div>
      </div>
    
    <hr>  
     
        
    <!--/col-->
    
    
    <hr>  
        <div class="row">
          <!--<div class="col-md-12"><h2>Graphs</h2></div> -->
            <div class="col-md-12">
    
            </div>
    
                   <div class="col-md-6 col-sm-6">
          <div class="panel panel-default">
               <div class="panel-heading"><a href="" class="pull-right"></a> <h4 id="mostComments">Biggest Commenters</h4></div>
            <div class="panel-body">
                  <span class = "bar_names" id="comments_graph_name_1">  </span>
                  <div class="progress">
                    <div class="progress-bar progress-bar-info" id="comments_graph_1" style="" title="Stuff"></div>
                  </div>
                  <span class = "bar_names" id="comments_graph_name_2">  </span>
                  <div class="progress">
                    <div class="progress-bar progress-bar-success" id="comments_graph_2" style="" title="stuff 2"></div>
                  </div>
                  <span class = "bar_names" id="comments_graph_name_3">  </span>
                  <div class="progress">
                    <div class="progress-bar progress-bar-warning" id="comments_graph_3" style="" title="stuff 3"></div>
                  </div>
                  <span class = "bar_names" id="comments_graph_name_4">  </span>
                  <div class="progress">
                    <div class="progress-bar progress-bar-danger" id="comments_graph_4" style="" stuff="stuff4"></div>
                  </div>
                  
                </div>
             </div> 
        </div>         
                            
                            
                            
     <div class="col-md-6 col-sm-6">
          <div class="panel panel-default">
               <div class="panel-heading"><a href="" class="pull-right"></a> <h4 id="mostLikes">Most Likes</h4></div>
            <div class="panel-body">
                  <span class = "bar_names" id="likes_graph_name_1">  </span>
                  <div class="progress">
                    <div id="likes_graph_1" class="progress-bar progress-bar-info" style="width: 0" title="Stuff"></div>
                  </div>
                  <span class = "bar_names" id="likes_graph_name_2">  </span>
                  <div class="progress">
                    <div id="likes_graph_2" class="progress-bar progress-bar-success" style="width: 0" title="stuff 2"></div>
                  </div>
                  <span class = "bar_names" id="likes_graph_name_3">  </span>
                  <div class="progress">
                    <div id="likes_graph_3" class="progress-bar progress-bar-warning" style="width: 0" title="stuff 3"></div>
                  </div>
                  <span class = "bar_names" id="likes_graph_name_4">  </span>
                  <div class="progress">
                    <div id="likes_graph_4" class="progress-bar progress-bar-danger" style="width: 0" stuff="stuff4"></div>
                  </div>
                  
                </div>
             </div> 
        </div>
        </div>
      </div>
      <div id="login">
      	<div class="fb-login-button" scope="basic_info, friends_photos, friends_status, friends_relationships, 
        			user_photos, user_status, user_relationships, user_likes, friends_likes, user_location, friends_location" 
                    data-size="xlarge" data-show-faces="false" data-auto-logout-link="true"></div>
      </div>
        
        <div id="noParam" style="width: 100%;text-align:center;font-family:'Myriad Pro','Lucidia Grande', Helvetica, sans-serif;font-size:24pt;margin-top: 10%">
        	<h1>Welcome to QuickView! Enter a name in the search box to the right to get started.</h1>
        </div>
        
        <div id="processing">
        	<h1>Processing...</h1>
        </div>
      
      <div id="loginAfter">
      <p id="users" style="font-size:10pt;width:35%"></p>
      </div>
        <!--playground-->
        
        
        <div class="clearfix"></div>
          
      </div>
     </div>
</body>
</html>
