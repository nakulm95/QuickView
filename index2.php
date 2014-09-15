<?php

$useragent=$_SERVER['HTTP_USER_AGENT'];
if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
header('Location: http://fathomless-spire-1079.herokuapp.com/mobile.php');

	$since = "";
	$name = "";
	$limit = 1000000;
	if (isset($_POST['noLimit'])) {
		setcookie('limit', 1000000, time()-3600);
		header("Location: http://fathomless-spire-1079.herokuapp.com/index2.php");
	}else if (isset($_POST['limit']) && is_numeric($_POST['limit'])) {
		$limit = $_POST['limit'];
		setcookie('limit', $_POST['limit'], time() + 100000000);
	} else if (isset($_COOKIE['limit']))
		$limit = $_COOKIE['limit'];
	if (isset($_POST['name']))
		$name = $_POST['name'];
	if (isset($_POST['since']))
		$since = $_POST['since'];
?>

<!DOCTYPE html>
<html>
<head>
	<title>QuickView</title>
    <link href="css/index2.css" rel="stylesheet" type="text/css" />
    <!-- <link href="css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="css/bootstrap-responsive.css" rel="stylesheet" type="text/css"> -->
    <link href="icon.png" rel="icon" type="image/png" />
    <!--<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />-->
	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>  
    <script src="css/toggle.js" type="text/javascript"></script> 
    <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">

</head>

<body>

<div id="fb-root"></div>

<script>
var loggedIn = false;
var current = 0;
// document.getElementById('right').style.backgroundImage="url('../bgDark.png')";

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
			document.getElementById('login').style.display="none";
			if (!loggedIn) {
				loggedIn = true;
				main();
			}
			testAPI();
		} else if (response.status === 'not_authorized') {
			console.log('Not Authorized');
			document.getElementById('login').style.display="block";
			FB.login();
		} else {
			FB.login();
			document.getElementById('login').style.display="block";

		}
	});
	
	// If logged in, run the main script
	function main() {
		// Get name and time period from the $_POST variables
		var since = "<?= $since ?>";
		var name = "<?= $name ?>";
		// Only execute if there is a name parameter
		if (name.length != 0) {
			getData(since, name);
			document.getElementById('header').style.display="block";
		}
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
		// document.getElementById("current_name").innerHTML = name;
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
				console.log("not found");
				document.getElementById("header").innerHTML='<p>Sorry, it looks like ' + name + ' is not a Facebook friend</p>';
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
			// document.getElementById("tags").value = name;
			var first = name.substring(0, name.indexOf(" "));
			// Get the relationship status of the friend
			// getLikes(id, since);
			// getRelationshipStatus(id, name);
			// getLocation(id);
			var pictures = new Array();
			var totalLikes = new Array();
			var totalComments = new Array();
			if (since == 0) {
				var call = id + "/albums?limit=1&fields=name,photos.limit(100).since(" + since + ").fields(source,likes.limit(1000),comments.limit(1000),name,link),count";
				var limit = "<?= $limit ?>";
				getPictures(id, call, 0, 0, since, pictures, totalLikes, totalComments, name, limit);
			} else
				getPicturesOld(id, 0, since, pictures, totalLikes, totalComments, name, 0, 0, limit);
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
	
	function getPictures(id, call, index, offset, since, pictures, totalLikes, totalComments, name, limit) {
		if (index <=5000)
			document.getElementById('processing').innerHTML="<h1>Processing</h1><h2>(" + (index) + " Pictures Processed)</h2>";
		else if (index > 5000 && index <= 10000)
			document.getElementById('processing').innerHTML="<h1>Processing</h1><h2>(" + (index) + " Pictures Processed)</h2><h3>Really? Over 5000?</h3>";
		else
			document.getElementById('processing').innerHTML="<h1>Processing</h1><h2>(" + (index) + " Pictures Processed)</h2><h3>Over 10,000???</h3>";
		FB.api(call, function(response) {
			var album;
			var dat = response.data[0];
			if (dat != null) {
				var photos;
				
				if ((dat['name']) != null) {
					// console.log(dat.name + ": " + dat.count);
					if (dat.count > 0)
						if (dat.photos != null)
							photos = dat.photos.data;
						else {
							var nextCall = id + "/albums?offset=" + (offset + 1) + "&limit=1&fields=name,photos.limit(100).since(" + since + ").fields(source,likes.limit(1000),comments.limit(1000),name,link),count";
						getPictures(id, nextCall, index, offset + 1, since, pictures, totalLikes, totalComments, name, limit);
						return;
						}
					else {
						var nextCall = id + "/albums?offset=" + (offset + 1) + "&limit=1&fields=name,photos.limit(100).since(" + since + ").fields(source,likes.limit(1000),comments.limit(1000),name,link),count";
						getPictures(id, nextCall, index, offset + 1, since, pictures, totalLikes, totalComments, name, limit);
						return;
					}
				} else
					photos = response.data;
					
				if (photos != null) {
					for (var i = 0; i < photos.length; i++) {
						if (index > limit) {
							console.log("Final picture count: " + index);
							processPictureData(id, since, pictures, totalLikes, totalComments, name, limit);
							return;
						}
						var likes = 0;
						var title="";
						var lnk = photos[i]['link'];
						if (photos[i].name != null)
							title = photos[i].name;
						var dat2 = photos[i];
						if (dat2.likes != null)
							likes = dat2.likes.data.length;
						pictures.push(new Array(dat2.source, likes, title, lnk));
						// For every like, record the name
						for (var j = 0; j < likes; j++) {
							var liker = dat2.likes.data[j].name;
							var liker_id = dat2.likes.data[j].id;
							var found = false;
							for (var k = 0; k < totalLikes.length; k++) {
								if (totalLikes[k][0] == liker) {
									totalLikes[k][1]++;
									found = true;
									break;
								}
							}
							if (!found)
								totalLikes.push(new Array(liker, 1, liker_id));
						}
						var comments = 0;
						if (dat2.comments != null) {
							comments = (dat2.comments.data).length;
						}
						// For every comment, record the name (if it isn't the person being viewed)
						for (var j = 0; j < comments; j++) {
							var commentor = dat2.comments.data[j].from.name;
							var commentor_id = dat2.comments.data[j].from.id;
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
									totalComments.push(new Array(commentor, 1, commentor_id));
							}
						}
						index++;
					}
				}
				// console.log(index + " total pictures so far");
				
				if (dat.name != null) {
					if (dat.photos != null)
						page = dat.photos.paging;
					else {
						var nextCall = id + "/albums?offset=" + (offset + 1) + "&limit=1&fields=name,photos.limit(100).since(" + since + ").fields(source,likes.limit(1000),comments.limit(1000),name,link),count";
						getPictures(id, nextCall, index, offset + 1, since, pictures, totalLikes, totalComments, name, limit);
						return;
					}
				} else
					page = response.paging;
					
				if (page.next != null) {
					console.log("next: " + page.next);
					getPictures(id, page.next, index, offset, since, pictures, totalLikes, totalComments, name, limit);
					return;
				} else {
					document.getElementById('processing').innerHTML="<h1>Processing</h1><h2>(" + index + " Pictures Processed)";
					var nextCall = id + "/albums?offset=" + (offset + 1) + "&limit=1&fields=name,photos.limit(100).since(" + since + ").fields(source,likes.limit(1000),comments.limit(1000),name,link),count";
					getPictures(id, nextCall, index, offset + 1, since, pictures, totalLikes, totalComments, name, limit);
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
		// console.log(index);
		if (index <=5000)
			document.getElementById('processing').innerHTML="<h1>Processing</h1><h2>(" + (index) + " Pictures Processed)</h2>";
		else if (index > 5000 && index <= 10000)
			document.getElementById('processing').innerHTML="<h1>Processing</h1><h2>(" + (index) + " Pictures Processed)</h2><h3>Really? Over 5000?</h3>";
		else
			document.getElementById('processing').innerHTML="<h1>Processing</h1><h2>(" + (index) + " Pictures Processed)</h2><h3>Over 10,000???</h3>";
	}
	
	function getPicturesOld(id, offset, since, pictures, totalLikes, totalComments, name, index, lastIndex) {
		var lastIndex;
		if (index == 0)
				document.getElementById('processing').innerHTML="<h1>Processing</h1><h2>(Waiting for response from server)</h2>";
		FB.api((id + "/photos/uploaded?limit=2500&since=" + since + "&offset=" + offset + "&fields=likes.limit(1000),comments.limit(1000),source,name,link"), function(response) {
			for (var i = 0; i < response.data.length; i++) {
				if (index >= limit) {
					processPictureData(id, since, pictures, totalLikes, totalComments, name);
					return;
				}
				var title="";
				updateTicker(index);
				index++;
				var likes = 0;
				var lnk = response.data[i]['link'];
				var dat = response.data[i];
				if (dat.likes != null)
					likes = dat.likes.data.length; //EDITED, CHECK
				if (dat.name != null)
					title = dat.name;
				pictures.push(new Array(dat.source, likes, title, lnk));
				for (var j = 0; j < likes; j++) {
					var liker = dat.likes.data[j].name;
					var liker_id = dat.likes.data[j].id;
					var found = false;
					for (var k = 0; k < totalLikes.length; k++) {
						if (totalLikes[k][0] == liker) {
							totalLikes[k][1]++;
							found = true;
							break;
						}
					}
					if (!found)
						totalLikes.push(new Array(liker, 1, liker_id));
				}
				var comments = 0;
				if (dat.comments != null) {
					comments = (dat.comments.data).length;
				}
				for (var j = 0; j < comments; j++) {
					var commentor = dat.comments.data[j].from.name;
					var commentor_id = dat.comments.data[j].from.id;
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
							totalComments.push(new Array(commentor, 1, commentor_id));
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
		// document.getElementById('uploadedPics').innerHTML=name.substring(0, name.indexOf(" ")) + "'s Most Popular Pictures";
		index = Math.min(3, pictures.length);
		var row = document.createElement('div');
		row.className = "row";
		for (var i = 0; i < index; i++) {
			var quote = pictures[i][2];
			var likes = " likes";
			var height = new Array();
			if (pictures[i][1] == 1)
				likes = " like";
			if (quote.length > 0)
				quote = '<div class="caption">' + quote + "</div>";
			row.innerHTML += '<div class="container">' + 
								'<img id="picture_' + i + '" src="' + pictures[i][0] + '" alt="Popular Picture" />' + quote +
								'<div class="caption">' + pictures[i][1] + likes + 
							  '</div>' + 
							  '<span class="fb_link" style="margin-top:-14pt;"><a href="' + 
									pictures[i][3] + '" target="_blank"><img class="fb_small" style="width:16pt;height:16pt;" src="fb_small.png" alt="Facebook" /></a></span></div>';
			// document.getElementById('uploadedPics').innerHTML += '<div class="container">' + 
			//														'<img id="picture_' + i + '" src="' + pictures[i][0] + '" alt="Popular Picture" />' + quote +
			//														'<div class="caption">' + pictures[i][1] + likes + 
			//													  '</div>' + 
			//													  '<span class="fb_link" style="margin-top:-14pt;"><a href="' + 
			//															pictures[i][3] + '" target="_blank"><img class="fb_small" style="width:16pt;height:16pt;" src="fb_small.png" alt="Facebook" /></a></span></div>';
			// height.push(document.getElementById('picture_' + i).clientHeight);
			// var current_picture = document.getElementById(("pic_" + i));
			// var current_name = document.getElementById(("pic_name_" + i));
			// current_picture.src = pictures[i - 1][0];
			// current_name.innerHTML = pictures[i - 1][1] + " likes";
		}
		document.getElementById('uploadedPics').appendChild(row);
		document.getElementById('uploadedPics').innerHTML += '<hr />';
		// console.log('height: ' + document.getElementById('picture_0').clientHeight);
		var newHeight;
		// if (index == 3)
		// 	newHeight = Math.max(Math.max(height[1],height[2]),height[0]);
		// else if (index == 2)
		// 	newHeight = Math.max(height[0],height[1]);
		// else
		// 	newHeight = height[0];
		// console.log(height);
		// console.log(newHeight);
		for (var i = 0; i < index; i++)
			document.getElementById('picture_' + i).style.height=newHeight + 'px';
		// getTagged(id, since, name);
		getStatuses(id, since, totalLikes, totalComments, name, pictures);
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
	function getStatuses(id, since, totalLikes, totalComments, name, pictures) {
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
						var commenter_id = comment[i][j].from.id;
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
								totalComments.push(new Array(commenter, 1, commenter_id));
						}
					}
				}
				 ///////////////////////
				////Names of likers////
				if (response.data[i].likes != null) {
					comment[i] = response.data[i].likes.data; // returns the data array containing likes
					for (var j = 0; j < comment[i].length; j++) {
						var liker = comment[i][j].name;
						var liker_id = comment[i][j].id;
						var found = false;
						for (var k = 0; k < totalLikes.length; k++) {
							if (totalLikes[k][0] == liker) {
								totalLikes[k][1]++;
								found=true;
								break;
							}
						}
						if (!found) 
							totalLikes.push(new Array(liker, 1, liker_id));
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
			
			var more = false;
			var row = document.createElement('div');
			row.className = "row";
			for (var i = 0; i < index; i++) {
				var like = "likes";
				if (statuses[i][1] == 1)
					like = "like";
				row.innerHTML += '<div class="container"><div class="textBox"><q>' + statuses[i][0] + 
																	'</q></div><div class="caption">' + statuses[i][1] + ' ' + like + '</div><div>';
				// document.getElementById("status_" + (i+1)).innerHTML = statuses[i][0];
			}
			document.getElementById('statuses').appendChild(row);
			document.getElementById('statuses').innerHTML += '<hr />';
			// Sort total commenters based on number of comments
			totalComments.sort((function(index){
				return function(a, b) {
					return (a[index] === b[index] ? 0 : (a[index] > b[index] ? -1 : 1));
				};
			})(1));
			// Draw popularity graphs
			index = Math.min(4, totalComments.length);
			var type = new Array("success", "info", "warning", "danger"); 
			for (var i = 0; i < index; i++) {
				var num = parseInt(100.0 * totalComments[i][1] / totalComments[0][1], 10);
				// console.log(num);
				document.getElementById('commentGraph').innerHTML+='<span class="bar_names">' + totalComments[i][0] + ' / ' + totalComments[i][1] + '</span>' + 
                  													'<div class="progress">' + 
                    													'<div class="progress-bar progress-bar-' + type[i] + '" style="width:' + num + '%;" title="Stuff"></div>' + 
                  													'</div>';
				// document.getElementById('commentGraph').innerHTML+='<p>' + totalComments[i][0] + ' / ' + totalComments[i][1] + '</p><progress value="' + 
				// 													totalComments[i][1] + '" max="' + totalComments[0][1] + '"></progress>';
				// document.getElementById("comments_graph_name_" + (i + 1)).innerHTML =  totalComments[i][0]+ " / " + totalComments[i][1];
				// document.getElementById("comments_graph_" + (i + 1)).style.width = num;
			}
			
			// Sort the total likes
			totalLikes.sort((function(index){
				return function(a, b) {
					return (a[index] === b[index] ? 0 : (a[index] > b[index] ? -1 : 1));
				};
			})(1));
			// Most likes graph
			index = Math.min(4, totalLikes.length);
			var type = new Array("success", "info", "warning", "danger"); 
			for (var i = 0; i < index; i++) {
				var num = parseInt(100.0 * totalLikes[i][1] / totalLikes[0][1], 10);
				document.getElementById('likeGraph').innerHTML+='<span class="bar_names">' + totalLikes[i][0] + ' / ' + totalLikes[i][1] + '</span>' + 
													'<div class="progress">' + 
														'<div class="progress-bar progress-bar-' + type[i] + '" style="width:' + num + '%;" title="Stuff"></div>' + 
													'</div>';
				// document.getElementById('likeGraph').innerHTML+='<p>' + totalLikes[i][0] + ' / ' + totalLikes[i][1] + '</p><progress value="' + 
				// 									totalLikes[i][1] + '" max="' + totalLikes[0][1] + '"></progress>';
				// document.getElementById("likes_graph_name_" + i).innerHTML =  totalLikes[i - 1][0]+ " / " + totalLikes[i - 1][1];
				// document.getElementById("likes_graph_" + i).style.width = num;
			}
			// Basically repeat the process with likes
			getFinalInfo(id, totalLikes, totalComments, since, pictures, statuses);
		});
	}
	
	function getFinalInfo(id, totalLikes, totalComments, since, pictures, statuses) {
		var total = new Array();
		for (var i = 0; i < totalLikes.length; i++) {
			total[i] = new Array(totalLikes[i][0], totalLikes[i][1], 0, 0, totalLikes[i][2]);
		}
		for (var i = 0; i < totalComments.length; i++) {
			var found = false;
			var currentName = totalComments[i][0];
			var currentComments = totalComments[i][1];
			var currentId = totalComments[i][2];
			for (var j = 0; j < totalLikes.length; j++) {
				if (total[j][0] == currentName) {
					found = true;
					total[j][2] = currentComments;
					break;
				}
			}
			if (!found)
				total.push(new Array(currentName, 0, currentComments, 0, currentId));
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
		var row = document.createElement('div');
		row.className = "row";
		for (var i = 0; i < index; i++) {
			var com = "comments";
			var like = "likes";
			if (total[i][2] == 1)
				com = "comment";
			if (total[i][1] == 1)
				like = "like";
			var width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
			row.innerHTML += '<div class="container"><div class="heading"><h2><img class="friendPic" id="friend_' + i + '" style="display: none" src="http://graph.facebook.com/' + total[i][4] + '/picture?width=' + width + '" /><a href="http://facebook.com/' + total[i][4] + '/" target="_blank">' + total[i][0] + '</a>' +  
																		'</h2></div><div class="caption">' + total[i][2] + ' ' + com + ' and ' + total[i][1] + ' ' + like + '</div></div>';
			// document.getElementById("friend_" + (i + 1)).innerHTML = total[i][2] + ' comments and ' + total[i][1] + ' likes';
		}
		document.getElementById('overall').appendChild(row);
		document.getElementById('overall').innerHTML += "<hr />";
		sendFinalData(pictures, totalLikes, totalComments, total, statuses);
		setTimeout(function(){console.log('Height Function: ' + document.getElementById('picture_0').clientHeight);}, 2000);
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
						document.getElementById("picPage").style.display="block";
						document.getElementById('left').style.backgroundImage="url('bgDark.png')";
						document.getElementById('list').style.display="block";
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
				document.getElementById("picPage").style.display="block";
				document.getElementById("processing").style.display="none";
				document.getElementById('left').style.backgroundImage="url('bgDark.png')";
				document.getElementById('list').style.display="block";
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

var pictures = new Array();
var picIndex = 3;
var statusIndex = 3;
var totFIndex = 3;
var comIndex = 4;
var likeIndex = 4;
var totalLikes = new Array();
var totalComments = new Array();
var total = new Array();
var statuses = new Array();
var type = new Array("success", "info", "warning", "danger"); 

function sendFinalData(pics, likes, comments, likesAndComments, topStatuses) {
	pictures = pics;
	totalLikes = likes;
	totalComments = comments;
	total = likesAndComments;
	statuses = topStatuses;
}

function morePics() {
	document.getElementById('morePicsButton').innerHTML="Loading...";
	// document.getElementById('uploadedPics').innerHTML += '<div class="row">';
	var more = false;
	var row = document.createElement('div');
	row.className = "row";
	for (var i = 0; i < 3; i++) {
		if (picIndex < pictures.length) {
			more = true;
			var quote = pictures[picIndex][2];
			var likes = " likes";
			if (pictures[picIndex][1] == 1)
				likes = " like";
			if (quote.length > 0)
				quote = '<div class="caption">' + quote + "</div>";
			row.innerHTML+='<div class="container">' +
							'<img src="' + pictures[picIndex][0] + '" alt="Popular Picture" />' + quote +
							'<div class="caption">'  + pictures[picIndex][1] + likes +
						'</div>' + 
						'<span class="fb_link" style="margin-top:-14pt;"><a href="' + 
								pictures[picIndex][3] + '" target="_blank"><img class="fb_small" style="width:16pt;height:16pt;" src="fb_small.png" alt="Facebook" /></a></span></div>';
			picIndex++;
		} else {
			document.getElementById('morePicsButton').innerHTML="End of Pictures";
		}
	}
	document.getElementById('uploadedPics').appendChild(row);
	if (more) 
		document.getElementById('uploadedPics').innerHTML += '<hr />';
	document.getElementById('lessPics').style.display="block";
	if (picIndex < pictures.length)
		document.getElementById('morePicsButton').innerHTML="Show More";
	else {
		document.getElementById('morePicsButton').innerHTML="End of Pictures";
		document.getElementById('morePicsButton').disabled="disabled";
	}

}

function lessPics() {
	var string = document.getElementById('uploadedPics').innerHTML;
	if (picIndex > 3) {
		string = string.substr(0, string.lastIndexOf('<div class="row"'));
		// string = string.substr(0, string.lastIndexOf('<div class="container"'));
		// string = string.substr(0, string.lastIndexOf('<div class="container"'));
		document.getElementById('uploadedPics').innerHTML=string;
		picIndex -= 3;
	}
	if (picIndex > 3) {
		document.getElementById('lessPicsButton').innerHTML="Show Less";
		document.getElementById('morePicsButton').disabled="";
		document.getElementById('morePicsButton').innerHTML="Show More";
	}
	else 
		document.getElementById('lessPics').style.display="none";
}

function moreStatuses() {
	document.getElementById('moreStatusesButton').innerHTML="Loading...";
	// document.getElementById('uploadedPics').innerHTML += '<div class="row">';
	var more = false;
	var row = document.createElement('div');
	row.className = "row";
	for (var i = 0; i < 3; i++) {
		if (statusIndex < statuses.length) {
			var like = "likes";
			if (statuses[statusIndex][1] == 1)
				like = "like";
			more = true;
			row.innerHTML+='<div class="container"><div class="textBox"><q>' + statuses[statusIndex][0] + 
							'</q></div><div class="caption">' + statuses[statusIndex][1] + ' ' + like + '</div><div>';
			statusIndex++;
		} else {
			document.getElementById('moreStatusesButton').innerHTML="End of Status Updates";
		}
	}	
	document.getElementById('statuses').appendChild(row);
	if (more) 
		document.getElementById('statuses').innerHTML += '<hr />';
	document.getElementById('lessStatuses').style.display="block";
	if (statusIndex < statuses.length)
		document.getElementById('moreStatusesButton').innerHTML="Show More";
	else {
		document.getElementById('moreStatusesButton').innerHTML="End of Status Updates";
		document.getElementById('moreStatusesButton').disabled="disabled";
	}
}

function lessStatuses() {
	var string = document.getElementById('statuses').innerHTML;
	if (statusIndex > 3) {
		string = string.substr(0, string.lastIndexOf('<div class="row">'));
		// string = string.substr(0, string.lastIndexOf('<div class="container">'));
		// string = string.substr(0, string.lastIndexOf('<div class="container">'));
		document.getElementById('statuses').innerHTML=string;
		statusIndex -= 3;
	}
	if (statusIndex >= 3) {
		document.getElementById('lessStatusesButton').innerHTML="Show Less";
		document.getElementById('moreStatusesButton').disabled="";
		document.getElementById('moreStatusesButton').innerHTML="Show More";
	} else 
		document.getElementById('lessStatuses').style.display="none";
}

function moreFriends() {
	document.getElementById('moreFriendsButton').innerHTML="Loading...";
	// document.getElementById('uploadedPics').innerHTML += '<div class="row">';
	var more = false;
	var row = document.createElement('div');
	row.className = "row";
	var display = document.getElementById('friend_1').style.display;
	for (var i = 0; i < 3; i++) {
		var width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
		if (totFIndex < total.length) {
			more = true;
			var com = "comments";
			var like = "likes";
			if (total[totFIndex][2] == 1)
				com = "comment";
			if (total[totFIndex][1] == 1)
				like = "like";
			row.innerHTML += '<div class="container"><div class="heading"><h2><img class="friendPic" id="friend_' + totFIndex + '" src="http://graph.facebook.com/' + total[totFIndex][4] + '/picture?width=' + width + '" style="display:' + display + '" /><a href="http://facebook.com/' + total[i][4] + '/" target="_blank">' + total[i][0] + '</a>' + 
															'</h2></div><div class="caption">' + total[totFIndex][2] + ' ' + com + ' and ' + total[totFIndex][1] + ' ' + like + '</div></div>';
			totFIndex++;
		} else {
			document.getElementById('moreFriendsButton').innerHTML="End of Top Friends";
		}
	}	
	document.getElementById('overall').appendChild(row);
	if (more) 
		document.getElementById('overall').innerHTML += '<hr />';
	document.getElementById('lessFriends').style.display="block";
	if (totFIndex < total.length)
		document.getElementById('moreFriendsButton').innerHTML="Show More";
	else {
		document.getElementById('moreFriendsButton').innerHTML="End of Friends";
		document.getElementById('moreFriendsButton').disabled="disabled";
	}
}

function lessFriends() {
	var string = document.getElementById('overall').innerHTML;
	if (totFIndex > 4) {
		string = string.substr(0, string.lastIndexOf('<div class="row">'));
		// string = string.substr(0, string.lastIndexOf('<div class="container">'));
		// string = string.substr(0, string.lastIndexOf('<div class="container">'));
		document.getElementById('overall').innerHTML=string;
		totFIndex -= 3;
	}
	if (totFIndex > 3) {
		document.getElementById('lessFriendsButton').innerHTML="Show Less";		
		document.getElementById('moreFriendsButton').disabled="";
		document.getElementById('moreFriendsButton').innerHTML="Show More";
	} else 
		document.getElementById('lessFriends').style.display="none";
}

function moreComments() {
	document.getElementById('moreCommentsButton').innerHTML="Loading...";
	// document.getElementById('uploadedPics').innerHTML += '<div class="row">';
	for (var i = 0; i < 4; i++) {
		if (comIndex < totalComments.length) {
			var num = parseInt(100.0 * totalComments[comIndex][1] / totalComments[0][1], 10);
			// console.log(num);
			document.getElementById('commentGraph').innerHTML+='<span class="bar_names">' + totalComments[comIndex][0] + ' / ' + totalComments[comIndex][1] + '</span>' + 
																'<div class="progress">' + 
																	'<div class="progress-bar progress-bar-' + type[i] + '" style="width:' + num + '%;" title="Stuff"></div>' + 
																'</div>';
			comIndex++;
		} else {
			document.getElementById('moreCommentsButton').innerHTML="End of Top Comments";
		}
	}
	document.getElementById('lessComments').style.display="block";
	if (comIndex < totalComments.length)
		document.getElementById('moreCommentsButton').innerHTML="Show More";
	else {
		document.getElementById('moreCommentsButton').innerHTML="End of Top Comments";
		document.getElementById('moreCommentsButton').disabled="disabled";
	}
	moreLikes();
}

function lessComments() {
	var string = document.getElementById('comments').innerHTML;
	for (var i = 0; i < 4; i++) {
		if ( comIndex > 4) {
			string = string.substr(0, string.lastIndexOf('<span class="bar_names'));
			comIndex--;
		}
	}		
	document.getElementById('comments').innerHTML=string;
	if (comIndex > 4)
		document.getElementById('lessCommentsButton').innerHTML="Show Less";
	else 
		document.getElementById('lessComments').style.display="none";
	document.getElementById('moreCommentsButton').innerHTML="Show More";
	document.getElementById('moreCommentsButton').disabled="";
	lessLikes();
}

function moreLikes() {
	document.getElementById('moreLikesButton').innerHTML="Loading...";
	// document.getElementById('uploadedPics').innerHTML += '<div class="row">';
	for (var i = 0; i < 4; i++) {
		if (likeIndex < totalLikes.length) {
			var num = parseInt(100.0 * totalLikes[likeIndex][1] / totalLikes[0][1], 10);
			// console.log(num);
			document.getElementById('likeGraph').innerHTML+='<span class="bar_names">' + totalLikes[likeIndex][0] + ' / ' + totalLikes[likeIndex][1] + '</span>' + 
																'<div class="progress">' + 
																	'<div class="progress-bar progress-bar-' + type[i] + '" style="width:' + num + '%;" title="Stuff"></div>' + 
																'</div>';
			likeIndex++;
		} else {
			document.getElementById('moreLikesButton').innerHTML="End of Top Likes";
		}
	}
	document.getElementById('lessLikes').style.display="block";
	if (likeIndex < totalLikes.length)
		document.getElementById('moreLikesButton').innerHTML="Show More";
	else {
		document.getElementById('moreLikesButton').innerHTML="End of Top Likes";
		document.getElementById('moreLikesButton').disabled="disabled";
	}
}

function lessLikes() {
	var string = document.getElementById('likes').innerHTML;
	for (var i = 0; i < 4; i++) {
		if (likeIndex > 4) {
			string = string.substr(0, string.lastIndexOf('<span class="bar_names'));
			likeIndex--;
		}
	}
	document.getElementById('likes').innerHTML=string;
	if (likeIndex > 4)
		document.getElementById('lessLikesButton').innerHTML="Show Less";
	else 
		document.getElementById('lessLikes').style.display="none";
	document.getElementById('moreLikesButton').innerHTML="Show More";
	document.getElementById('moreLikesButton').disabled="";
}

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
function setWidth() {
	var width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
	document.getElementById('users').innerHTML=width;
	var twoP = width / 50;
	document.getElementById('center').style.borderRight=twoP + 'px solid black';
	document.getElementById('center').style.borderLeft=twoP + 'px solid black';
}

function showAll() {
	document.getElementById('submit').style.display="block";
}

function changeTab(tab) {
	document.getElementById('picPage').style.display="none";
	document.getElementById('friends').style.display="none";
	document.getElementById('statusPage').style.display="none";
	document.getElementById('settings').style.display="none";	
	document.getElementById('center').style.backgroundImage="none";
	document.getElementById('right').style.backgroundImage="none";
	document.getElementById('left').style.backgroundImage="none";
	document.getElementById(tab).style.backgroundImage="url('bgDark.png')";
	
	if (tab == 'right') 
		document.getElementById('friends').style.display="block";
	else if (tab == 'center')
		document.getElementById('statusPage').style.display="block";
	else
		document.getElementById('picPage').style.display="block";
}

function settingsTab() {
	if (document.getElementById('settings').style.display == "block") {
		if (document.getElementById('noParam').style.display != "block")
			document.getElementById('picPage').style.display="block";
		document.getElementById('settings').style.display="none";
	} else {
		document.getElementById('picPage').style.display="none";
		document.getElementById('friends').style.display="none";
		document.getElementById('statusPage').style.display="none";
		document.getElementById('settings').style.display="block";
	}
}

function showHideFriends() {
	for (var i = 0; i < totFIndex; i++) {
		if (document.getElementById('friend_' + i).style.display=="none")
			document.getElementById('friend_' + i).style.display="block";
		else
			document.getElementById('friend_' + i).style.display="none";
	}
}

function height() {
	console.log('height function: ' + document.getElementById('picture_0').clientHeight);
}

</script>

<!-- WHERE THE HTML STARTS OMGGG -->
<div id="nameParse"></div>
<div id="all">
    <div id="banner">
        <img id="binoc" src="banner_binoc.png" alt="QuickView"/><img id="settingsImg" src="settings.png" onclick="settingsTab()"><br />
    </div>
    <div id="wrapper">
        <div id="list" style="display:none;">
            <ul id="tabs">
                <li><div id="left" onclick="changeTab('left')">Pics</div></li>
                <li><div id="center" onclick="changeTab('center')">Statuses</div></li>
                <li><div id="right" onclick="changeTab('right')">Friends</div></li>
            </ul>
        </div>
        <div id="search" onclick="showAll()", onTouchStart="showAll()">
            <form action="" method="post">
                <input id="tags" type="text" placeholder="Search for friends" name="name" />
                <div id="submit">
                    Timeframe:
                    <select name="since">
                        <option id="week" value="604800">The Past Week</option>
                        <option id="month" value="2629743">The Past Month</option>
                        <option id="year" value="31556926">The Past Year</option>
                        <option id="all" value="All" selected="selected">Profile Creation</option>
                    </select>
                    <input type="submit" value="search" />
                </div>
            </form>
        </div>
        <div id="header" onclick="showAll()" onTouchStart="showAll()">
            <h1><?= $name ?></h1>
            <!--<img src="search.png" alt="search" /> -->
        </div>
        <div id="main">
            <div id="picPage">
                <div id="uploadedPics">
                    <h2>Most Liked Pictures</h2>
                    <hr />
                </div>
                <div id="morePics">
                    <button onclick="morePics()" id="morePicsButton">Show More</button>
                </div>
                <div id="lessPics">
                    <button onclick="lessPics()" id="lessPicsButton">Show Less</button>
                </div>
            </div>
            <div id="statusPage">
                <div id="statuses">
                    <h2>Most Liked Statuses</h2>
                    <hr />
                </div>
                <div id="moreStatuses">
                     <button onclick="moreStatuses()" id="moreStatusesButton">Show More</button>
                </div>
                <div id="lessStatuses">
                    <button onclick="lessStatuses()" id="lessStatusesButton">Show Less</button>
                </div>
            </div>
            <div id="friends">
                <h2>Top Facebook Friends</h2>
                    <hr />
                <div class="container">
                    <div id="overall">
                        <h3>Overall</h3>
                        <button onclick="showHideFriends()">Show/Hide Pictures</button>
                        <hr />
                    </div>
                    
                    <div id="moreFriends">
                         <button onclick="moreFriends()" id="moreFriendsButton">Show More</button>
                    </div>
                    <div id="lessFriends">
                        <button onclick="lessFriends()" id="lessFriendsButton">Show Less</button>
                    </div>
                </div>
                <hr />
                <div class="container" id="commentContainer" style="float: left;">
                    <div id="comments">
                        <h3>Comments</h3>
                        <hr />
                        <div id="commentGraph"></div>
                    </div>
                    <div id="moreComments">
                         <button onclick="moreComments()" id="moreCommentsButton">Show More</button>
                    </div>
                    <div id="lessComments">
                        <button onclick="lessComments()" id="lessCommentsButton">Show Less</button>
                    </div>
                </div>
                <div class="container" id="likeContainer" style="float: left">
                    <div id="likes">
                        <h3>Likes</h3>
                        <hr />
                        <div id="likeGraph"></div>
                    </div>
                    <div id="moreLikes">
                         <button onclick="moreComments()" id="moreLikesButton">Show More</button>
                    </div>
                    <div id="lessLikes">
                        <button onclick="lessComments()" id="lessLikesButton">Show Less</button>
                    </div>
                </div>
                <hr />
            </div>
            <div id="settings">
                <h2>Settings</h2>
                <div class="container">
                    <h3>Max number of images to load: <?php if (isset($_COOKIE['limit'])) print('(Current Limit ' . $_COOKIE['limit'] . ')'); ?></h3>
                    <form action="" id="maxPics" method="post">
                        <input type="text" name="limit" pattern="\d+"/>
                        <input type="submit" />
                    </form>
                    <form action="" id="noLimit" method="post">
                        <button name="noLimit" value="button">No Limit</button>
                    </form>
                </div>
            </div>
            <div id="processing"><h1>Processing</h1></div>
            <div id="noParam">
            	<h2>
                Welcome to QuickView! Search for a friend (or yourself) to begin!
                </h2>
            </div>
        </div>
        <div id="users" style="font-size: 10pt;"></div>
        <div id="login">
            <div class="fb-login-button" style="margin-bottom: 20px" scope="basic_info, friends_photos, friends_status, 
                    user_photos, user_status, friends_likes" data-size="xlarge" data-show-faces="false" data-auto-logout-link="true"></div>
        </div>
    </div>
</div>
</body>
</html>