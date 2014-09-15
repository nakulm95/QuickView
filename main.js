/* main.js provides all the functionality for QuickView.
 * Using Facebook's API, it grabs the uploaded photos and 
 * statuses of the person searched for by the user, and 
 * displays that information by most popular photos/statuses
 */
"use strict";


// Module pattern
(function () {
	
	// This site does not work in IE, so check if the user is using IE.
	// If they are, display a message telling them to switch browsers
	function msieversion() {
	
			var ua = window.navigator.userAgent;
			var msie = ua.indexOf("MSIE ");
	
			if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) {     // If Internet Explorer, return version number
				$('#search').css('display', 'none');
				$('#login').html('<h2>Sorry, this website does not support Internet Explorer. Please use Firefox or Chrome</h2>');
				// alert("Sorry, this website does not support Internet Explorer. Please use Firefox or Chrome");
			}              // If another browser, return 0
				// alert('otherbrowser');
	
	   return false;
	}
	
	msieversion();
	
	// FB checks to see if the user is logged in periodically,
	// which will make the main program run again. This variable
	// becomes true once the main program runs once, which will
	// ensure it does not get run again.
	var loggedIn = false;
	
	window.onload = setup;
	
	function setup() {
		// Attach onclick handlers
		e('search').onclick = showAll;
		e('header').onclick = showAll;
		// e('settingsImg').onclick= settingsTab;
		e('morePicsButton').onclick = morePics;
		e('lessPicsButton').onclick = lessPics;
		e('moreStatusesButton').onclick = moreStatuses;
		e('lessStatusesButton').onclick = lessStatuses;
		e('friendImgs').onclick = showHideFriends;
		e('moreFriendsButton').onclick = moreFriends;
		e('lessFriendsButton').onclick = lessFriends;
		e('moreCommentsButton').onclick = moreComments;
		e('lessCommentsButton').onclick = lessComments;
		e('moreLikesButton').onclick = moreComments;
		e('lessLikesButton').onclick = lessComments;
		// Wrap functions that take parameters in anonymous
		// functions so it does not execute immediately
		e('left').onclick = function() { changeTab('left'); };
		e('center').onclick = function() { changeTab('center'); };
		e('right').onclick = function() { changeTab('right'); };
		e('search').onmouseover = showAll;
		// e('search').onmouseleave = function() { $('#submit').hide() };
		
		// Connect to FB API
		window.fbAsyncInit = function() {
			FB.init({
				appId      : '498051066990191',
				status     : true, // check login status
				cookie     : true, // enable cookies to allow the server to access the session
				xfbml      : true  // parse XFBML
			});
			
			// Check login status
			FB.Event.subscribe('auth.authResponseChange', function(response) {
				if (response.status === 'connected') {
					// console.log('Logged in');
					$("#login").css('display', 'none');
					// Only run if the user just logged in
					if (!loggedIn) {
						loggedIn = true;
						main();
					}
					testAPI();
				} else if (response.status === 'not_authorized') {
					// console.log('Not Authorized');
					e('login').style.display="block";
					FB.login();
				} else {
					FB.login();
					e('login').style.display="block";
		
				}
			});
			
			// If logged in, run the main script
			function main() {
				// grab the name from index.php, since php cannot be incorporated
				// into js (that I know of)
				var name = getName();
				// The previous function doesn't get all special characters for some reason,
				// but printing the name in a hidden div and then grabbing it does
				// the trick.
				e('nameParse').style.display="none";
				e('nameParse').innerHTML = name;
				name = e('nameParse').innerHTML;
				// Only execute if there is a name parameter
				if (name.length != 0) {
					getData(name);
					$('#header').css('display', "block");
				}
				else {
					// If no name passed, show the welcome screen,
					// but still add names to the autocomplete
					e('noParam').style.display="block";
					var ids = [];
					$(function() {
						FB.api('/me/friends', function(response) {
							var names = [];
							for (var i = 0; i < response.data.length; i++) {
								names[i] = response.data[i]['name'];
							}
							addOwnName(names);
						});
					});
					// console.log("No name entered");
				}
			}
			
			// Add the user to the list of possibilities
			function getData(name) {
				// console.log("Searching for " + name);
				// Get the name and id of yourself
				FB.api('me?fields=name,id', function(response) {
					var me = response.name;
					var meId = response.id;
					startSearch(name, me, meId);
				});
			}
			
			// Start collecting data by first getting all of the user's friends
			function startSearch(name, me, meId) {
				FB.api('me/friends?limit=6000&fields=name,id', function(response) {
					var names = [];
					var ids = [];
					for (var i = 0; i < response.data.length; i++) {
						names[i] = response.data[i].name;
						ids[i] = response.data[i].id;
					}
					// Add yourself to the array
					names[names.length] = me;
					ids[ids.length] = meId;
					var id;
					var foundName = false;
					// Case insensitive search
					for (var i = 0; i < names.length; i++) {
						if (names[i].toLowerCase() == name.toLowerCase()) {
							foundName = true;
							name = names[i];
						}
					}
					// Not necessary anymore?
					id = (name == me) ? meId : ids[names.indexOf(name)];
					// Create a copy of the names to sort and then become part of the search function
					var availableTags = names.slice();
					availableTags.sort();
					$( "#tags" ).autocomplete({
						source: availableTags
					});
					// Print a message if the searched for name is not a friend
					if (!foundName) {
						// console.log("not found");
						e("header").innerHTML='<p>Sorry, it looks like ' + name + ' is not a Facebook friend.</p>';
						 return;
					}
					e('header').innerHTML = '<h1>' + name + '</h1>';
					e('tags').value = name;
					// Display the processing screen until everything has been calculated.
					// The response from the api call is what takes the most time
					e('processing').style.display="block";
					var pictures = [];
					var totalLikes = [];
					var totalComments = [];
					// Get the timeframe parameters from index.php
					var until = getUntil();
					var since = getSince();
					// Complicated API call to get all the pictures
					var call = id + "/albums?limit=1&fields=name,photos.limit(100).since(" + since + ").until(" + until + ").fields(images,source,likes.limit(1000),comments.limit(1000),name,link),count";
					var limit = getLimit();
					getPictures(id, call, 0, 0, since, pictures, totalLikes, totalComments, name, limit, until);
				});
			}
			
			// Grab every picture of the person within the given timeframe. Probably overly complicated, but 
			// in writing it I kept getting errors, so threw in a bunch of if/elses and it seems to work well.
			// Probably shouldn't mess with it too much
			function getPictures(id, call, index, offset, since, pictures, totalLikes, totalComments, name, limit, until) {
				e('processing').innerHTML="<h1>Processing</h1><h2>(" + (index) + " Pictures Processed)</h2>";
				if (index > 5000 && index <= 10000) {
					e('processing').innerHTML+="<h3>Really? Over 5000?</h3>";
				} else if (index > 10000) {
					e('processing').innerHTML+="<h3>Over 10,000???</h3>";
				}
				FB.api(call, function(response) {
					var album;
					var dat = response.data[0];
					if (dat) {
						var photos;
						var page;
						// There is a difference beteween albums and pictures in the album. If (dat), then it is
						// an album, and the pictures are stored in dat.photos.data, otherwise they're just stored
						// in response.data
						if ((dat.name)) {
							if (dat.count > 0) {
								// If there is at least one picture in the album...
								if (dat.photos) {
									photos = dat.photos.data;
								// Otherwise move on to the next album (offset + 1) and return, otherwise the executing of this function will continue, which can
								// result in pictures being added multiple times
								} else {
									var nextCall = id + "/albums?offset=" + (offset + 1) + "&limit=1&fields=name,photos.limit(100).since(" + since + ").until(" + until + ").fields(images,source,likes.limit(1000),comments.limit(1000),name,link),count";
								getPictures(id, nextCall, index, offset + 1, since, pictures, totalLikes, totalComments, name, limit, until);
								return;
								}
							// Not exactly sure what dat.count > 0 is. May have just been me adding too many extra cases. Regardless, move on to the next
							// album if dat.count <= 0
							} else {
								var nextCall = id + "/albums?offset=" + (offset + 1) + "&limit=1&fields=name,photos.limit(100).since(" + since + ").until(" + until + ").fields(images,source,likes.limit(1000),comments.limit(1000),name,link),count";
								getPictures(id, nextCall, index, offset + 1, since, pictures, totalLikes, totalComments, name, limit, until);
								return;
							}
						} else {
							photos = response.data;
						}
						// If there is something in the photos object, collect all the needed data (comments, likes, and sources)
						if (photos) {
							for (var i = 0; i < photos.length; i++) {
								// If the number of photos is greater than the max allowed in settings, exit and process the data
								if (index > limit) {
									// console.log("Final picture count: " + index);
									processPictureData(id, since, pictures, totalLikes, totalComments, name, limit, until);
									return;
								}
								var likes = 0;
								var title="";
								var lnk = photos[i]['link'];
								if (photos[i].name) {
									title = photos[i].name;
								}
								var dat2 = photos[i];
								if (dat2.likes) {
									likes = dat2.likes.data.length;
								}
								// Get the highest quality image
								if (dat2.images) {
									pictures.push([dat2.source, likes, title, lnk, dat2.images[0].source]);
								// If there's nothing in the images array, just use the default source
								} else {
									pictures.push([dat2.source, likes, title, lnk, dat2.source]);
								}
								// For every like, record the name, and check to see if 
								// it already exists in the array
								for (var j = 0; j < likes; j++) {
									var liker = dat2.likes.data[j].name;
									var liker_id = dat2.likes.data[j].id;
									var found = false;
									for (var k = 0; k < totalLikes.length; k++) {
										if (totalLikes[k][2] == liker_id) {
											totalLikes[k][1]++;
											found = true;
											break;
										}
									}
									if (!found) {
										totalLikes.push([liker, 1, liker_id]);
									}
								}
								var comments = 0;
								if (dat2.comments) {
									comments = (dat2.comments.data).length;
								}
								// For every comment, record the name (if it isn't the person being viewed)
								for (var j = 0; j < comments; j++) {
									var commentor = dat2.comments.data[j].from.name;
									var commentor_id = dat2.comments.data[j].from.id;
									if (commentor != name) {
										var found = false;
										for (var k = 0; k < totalComments.length; k++) {
											if (totalComments[k][2] == commentor_id) {
												totalComments[k][1]++;
												found = true;
												break;
											}
										}
										if (!found) {
											totalComments.push([commentor, 1, commentor_id]);
										}
									}
								}
								index++;
							}
						}
						// If the response was an album, check to see if there are more photos. If not, move on
						// to the next album, otherwise get the paging data
						if (dat.name) {
							if (dat.photos) {
								page = dat.photos.paging;
							} else {
								var nextCall = id + "/albums?offset=" + (offset + 1) + "&limit=1&fields=name,photos.limit(100).since(" + since + ").until(" + until + ").fields(images,source,likes.limit(1000),comments.limit(1000),name,link),count";
								getPictures(id, nextCall, index, offset + 1, since, pictures, totalLikes, totalComments, name, limit, until);
								return;
							}
						// If the response returned is an array of pictures, get the paging data
						} else {
							page = response.paging;
						}
						// If there is another page in the album, go to the next page
						if (page.next) {
							getPictures(id, page.next, index, offset, since, pictures, totalLikes, totalComments, name, limit, until);
							return;
						// Otherwise display the number of pictures processed so far and move on to the next album
						} else {
							e('processing').innerHTML="<h1>Processing</h1><h2>(" + index + " Pictures Processed)";
							var nextCall = id + "/albums?offset=" + (offset + 1) + "&limit=1&fields=name,photos.limit(100).since(" + since + ").until(" + until + ").fields(images,source,likes.limit(1000),comments.limit(1000),name,link),count";
							getPictures(id, nextCall, index, offset + 1, since, pictures, totalLikes, totalComments, name, limit, until);
							return;
							// console.log("final count: " + index);
						}
					// If there are no more pictures at all, process the data
					} else {
						// console.log("Final picture count: " + index);
						processPictureData(id, since, pictures, totalLikes, totalComments, name, until);
					}
				});
			}
			
			// Process the picture data collected in getPictures();
			function processPictureData(id, since, pictures, totalLikes, totalComments, name, until) {
				// Sort the pictures by number of likes
				pictures.sort((function(index){
						return function(a, b) {
							return (a[index] === b[index] ? 0 : (a[index] > b[index] ? -1 : 1));
						};
				})(1));
					
				// Display most popular uploaded pictures
				// Could use a lot of work style-wise;
				var index = Math.min(3, pictures.length);
				var row = document.createElement('div');
				row.className = "row";
				row.id="picRow_0";
				if (index == 0) {
					e('uploadedPics').innerHTML += '<h3>No pictures in timeframe given</h3>';
				}
				for (var i = 0; i < index; i++) {
					var quote = pictures[i][2];
					var likes = " likes";
					var height = [];
					if (pictures[i][1] == 1) {
						likes = " like";
					} if (quote.length > 0) {
						quote = '<div class="caption">' + quote + "</div>";
					}
					row.innerHTML += '<div class="container">' + 
										'<img id="picture_' + i + '" src="' + pictures[i][0] + '" class="topPic" alt="Popular Picture" />' + quote +
										'<div class="caption">' + pictures[i][1] + likes + 
									  '</div>' + 
									  '<span class="fb_link" style="margin-top:-14pt;"><a href="' + 
											pictures[i][3] + '" target="_blank"><img class="fb_small" style="width:12pt;height:12pt;"src="fb_small.png" alt="Facebook" /></a></span></div>';
				}
				e('uploadedPics').appendChild(row);
				e('uploadedPics').innerHTML += '<hr />';
				getStatuses(id, since, totalLikes, totalComments, name, pictures, until);
			}
			
			// Log likes and comments from user statuses
			function getStatuses(id, since, totalLikes, totalComments, name, pictures, until) {
				// Get the comment  data of statuses
				FB.api((id + "/statuses?limit=10000&fields=comments.limit(10000),from,likes.limit(10000),message&since=" + since + "&until=" + until), function(response) {
					var comment = [];
					var statuses = [];
					var likecount = [];
					for (var i = 0; i < response.data.length; i++) {
						
						 /////////////////////////////
						////Most Popular Statuses////
						var message = response.data[i].message;
						if (!message) {
							message = "(Hidden/Deleted Status)";
						}
						// convert new line characters into break tags
						message = message.replace(/\n/g, '<br />');
						var likes = response.data[i].likes ? response.data[i].likes.data.length : 0;
						statuses[i] = [message, likes];
						
						 ///////////////////////////
						////Names of Commenters////
						if (response.data[i].comments) {
							comment[i] = response.data[i].comments.data; // returns the data array containing comments
							// For every comment on that status...
							for (var j = 0; j < comment[i].length; j++) {
								var commenter = comment[i][j].from.name;
								var commenter_id = comment[i][j].from.id;
								if (commenter != name) {
									var found = false;
									for (var k = 0; k < totalComments.length; k++) {
										if (totalComments[k][2] == commenter_id) {
											totalComments[k][1]++;
											found = true;
											break;
										}
									}
									if (!found) {
										totalComments.push([commenter, 1, commenter_id]);
									}
								}
							}
						}
						 ///////////////////////
						////Names of likers////
						if (response.data[i].likes) {
							comment[i] = response.data[i].likes.data; // returns the data array containing likes
							for (var j = 0; j < comment[i].length; j++) {
								var liker = comment[i][j].name;
								var liker_id = comment[i][j].id;
								var found = false;
								for (var k = 0; k < totalLikes.length; k++) {
									if (totalLikes[k][2] == liker_id) {
										totalLikes[k][1]++;
										found=true;
										break;
									}
								}
								if (!found) {
									totalLikes.push([liker, 1, liker_id]);
								}
							}
						}
					}
					
					// Sort the statuses based on the number of likes
					statuses.sort((function(index){
						return function(a, b) {
							return (a[index] === b[index] ? 0 : (a[index] > b[index] ? -1 : 1));
						};
					})(1));
					
					// Print the most liked statuses to the page
					var index = Math.min(3, statuses.length);
					
					var more = false;
					var row = document.createElement('div');
					row.className = "row";
					row.id="statusRow_0";
					if (index == 0) {
						e('statuses').innerHTML += '<h3>No statuses in the timeframe given</h3>';
					}
					for (var i = 0; i < index; i++) {
						var like = "likes";
						if (statuses[i][1] == 1) {
							like = "like";
						}
						row.innerHTML += '<div class="container"><div class="textBox"><q>' + statuses[i][0] + 
																			'</q></div><div class="caption">' + statuses[i][1] + ' ' + like + '</div><div>';
						// e("status_" + (i+1)).innerHTML = statuses[i][0];
					}
					e('statuses').appendChild(row);
					e('statuses').innerHTML += '<hr />';
					
					// Sort total commenters based on number of comments
					totalComments.sort((function(index){
						return function(a, b) {
							return (a[index] === b[index] ? 0 : (a[index] > b[index] ? -1 : 1));
						};
					})(1));
					// Draw popularity graphs
					var index = Math.min(4, totalComments.length);
					var type = ["success", "info", "warning", "danger"]; 
					var row = document.createElement('div');
					row.id = 'commentRow_0';
					for (var i = 0; i < index; i++) {
						var num = parseInt(100.0 * totalComments[i][1] / totalComments[0][1], 10);
						// console.log(num);
						row.innerHTML+='<span class="bar_names">' + totalComments[i][0] + ' / ' + totalComments[i][1] + '</span>' + 
																			'<div class="progress">' + 
																				'<div class="progress-bar progress-bar-' + type[i] + '" style="width:' + num + '%;" title="Stuff"></div>' + 
																			'</div>';
					}
					
					e('commentGraph').appendChild(row);
					// Sort the total likes
					totalLikes.sort((function(index){
						return function(a, b) {
							return (a[index] === b[index] ? 0 : (a[index] > b[index] ? -1 : 1));
						};
					})(1));
					// Most likes graph
					var index = Math.min(4, totalLikes.length);
					var type = ["success", "info", "warning", "danger"]; 
					var row2 = document.createElement('div');
					row2.id = 'likeRow_0';
					for (var i = 0; i < index; i++) {
						var num = parseInt(100.0 * totalLikes[i][1] / totalLikes[0][1], 10);
						row2.innerHTML+='<span class="bar_names">' + totalLikes[i][0] + ' / ' + totalLikes[i][1] + '</span>' + 
															'<div class="progress">' + 
																'<div class="progress-bar progress-bar-' + type[i] + '" style="width:' + num + '%;" title="Stuff"></div>' + 
															'</div>';
					}
					e('likeGraph').appendChild(row2);
					getFinalInfo(id, totalLikes, totalComments, since, pictures, statuses);
				});
			}
			
			// Complie all the data gathered to get the person's "top" friends
			function getFinalInfo(id, totalLikes, totalComments, since, pictures, statuses) {
				var total = [];
				for (var i = 0; i < totalLikes.length; i++) {
					total[i] = [totalLikes[i][0], totalLikes[i][1], 0, 0, totalLikes[i][2]];
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
					if (!found) {
						total.push([currentName, 0, currentComments, 0, currentId]);
					}
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
				var index = Math.min(total.length, 3);
				var row = document.createElement('div');
				row.id="fRow_0";
				row.className = "row";
				if (index == 0) {
					e('overall').innerHTML += '<h3>No friend activity in timeframe given</h3>';
				}
				for (var i = 0; i < index; i++) {
					var com = "comments";
					var like = "likes";
					if (total[i][2] == 1) {
						com = "comment";
					}
					if (total[i][1] == 1) {
						like = "like";
					}
					var width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
					row.innerHTML += '<div class="container"><div class="heading"><h2><img class="friendPic" style="display:none" id="friend_' + i + 
								'" src="https://graph.facebook.com/' + total[i][4] + '/picture?width=' + width + 
								'" /><a href="https://facebook.com/' + total[i][4] + '/" target="_blank">' + total[i][0] + '</a>' +  
								'</h2></div><div class="caption">' + total[i][2] + ' ' + com + ' and ' + total[i][1] + ' ' + like + '</div></div>';
				}
				e('overall').appendChild(row);
				e('overall').innerHTML += "<hr />";
				sendFinalData(pictures, totalLikes, totalComments, total, statuses);
				getFriendUsers(id, since);
			}
			
			// Get the names of all the user's friends who use this app
			function getFriendUsers(id, since) {
				FB.api('/fql', { q:{"query1":"SELECT uid FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1=me())AND is_app_user=1"}}, function(response) {
					var users = [];
					var links = [];
					var dat = response.data[0].fql_result_set;
					var len = 0;
					for (var i = 0; i < dat.length; i++) {
						var path = dat[i]['uid'];
						FB.api(path + '/', function(response) {
							links[len] = response['link'];
							users[len] = response.name;
							if (len == dat.length - 1) {
								e('users').innerHTML="";
								for (var i = 0; i < len + 1; i++) {
									e('users').innerHTML+='<a href="' + links[i] + '" target="_blank">' + users[i] + '</a>, ';
								}
								e('users').innerHTML+='and You use QuickView';
								// $('#picPage').slideDown({duration: 1000, queue: false});
								// $('#picPage').hide().fadeIn({duration: 1500, queue: false});
								e("picPage").style.display="block";
								e('left').style.backgroundImage="url('bgDark.png')";
								e('list').style.display="block";
								e("processing").style.display="none";
							}
							len++;
						});
					}
					// Processing is over once this is done, so display the results
					if (dat.length == 0) {
						e("picPage").style.display="block";
						e("processing").style.display="none";
						e('left').style.backgroundImage="url('bgDark.png')";
						e('list').style.display="block";
					}
					if (users.length < 1) {
						e('users').innerHTML+='You are the only one out of all your friends that uses QuickView';
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
		
		
		 // Functions for adding/removing items starts here //
		/////////////////////////////////////////////////////
		
		var pictures = [];
		var picIndex = 3;
		var statusIndex = 3;
		var totFIndex = 3;
		var comIndex = 4;
		var likeIndex = 4;
		var totalLikes = [];
		var totalComments = [];
		var total = [];
		var statuses = [];
		var type = ["success", "info", "warning", "danger"]; //Labels for bar graph colors, from Bootstram
		var firstRun = true;
		
		// Send the data out of the FB API encompassed functions
		function sendFinalData(pics, likes, comments, likesAndComments, topStatuses) {
			pictures = pics;
			totalLikes = likes;
			totalComments = comments;
			total = likesAndComments;
			statuses = topStatuses;
			// Show two rows of each item initially by just calling it one more time
			// TODO: don't display anything in the API functions, just do it all here by calling it twice
			morePics();
			moreStatuses();
			moreFriends();
			moreComments();
			// e('from').value="< ?= (isset($_POST['from']) && strlen($_POST['from']) > 0 ?  $since / 31556926 + 1970 : '') ?>";
			e('from').value = getFromValue();
			e('until').value = getUntilValue();
			// e('until').value="< ?= (isset($_POST['until']) && strlen($_POST['until']) > 0 ? $until / 31556926 + 1970 : '') ?>";
			// e('tags').value="< ?= $name ?>";
			// e('tags').value = getName();
			// Reset the timeframe when the box is clicked
			e('from').onclick = function() { this.value = ""; };
			e('until').onclick = function() { this.value = ""; };
			// e('tags').onclick = function() { this.value = ""; };
			// Set the onclick function of the overlay image
			$("#largePic").click(function() {
				$(this).fadeOut(800);
				// e('largePic').style.display="none";
			});
		}
		
		// Show three more pictures that slide and fade in
		function morePics() {
			e('morePicsButton').innerHTML="Loading...";
			var more = false;
			var row = document.createElement('div');
			var rowNum = Math.floor(picIndex / 3);
			row.className = "row";
			row.id = "picRow_" + Math.floor(rowNum);
			for (var i = 0; i < 3; i++) {
				if (picIndex < pictures.length) {
					more = true;
					var quote = pictures[picIndex][2];
					var likes = " likes";
					if (pictures[picIndex][1] == 1) {
						likes = " like";
					}
					// Super messy code in order to not just use .innerHTML = (a bunch of elements)
					var container = document.createElement('div');
					var img = document.createElement('img');
					var caption = document.createElement('div');
					var span = document.createElement('span');
					var a = document.createElement('a');
					var fbImg = document.createElement('img');
					container.className = 'container';
					img.src=pictures[picIndex][0];
					img.alt = 'Popular Picture';
					img.id="picture_" + picIndex;
					img.className = 'topPic';
					caption.className = 'caption';
					caption.innerHTML = pictures[picIndex][1] + likes;
					span.className = 'fb_link';
					span.style.marginTop = "-14pt";
					a.href=pictures[picIndex][3];
					a.target="_blank";
					fbImg.className="fb_small";
					fbImg.style.width = "12pt";
					fbImg.style.height = "12pt";
					fbImg.src="fb_small.png";
					fbImg.alt = "Facebook";
					container.appendChild(img);
					if (quote.length > 0) {
						var quoteDiv = document.createElement('div');
						quoteDiv.className = 'caption';
						quoteDiv.innerHTML = quote;
						container.appendChild(quoteDiv);
					}
					container.appendChild(caption);
					a.appendChild(fbImg);
					span.appendChild(a);
					container.appendChild(span);
					$(container).appendTo(row);
					// row.appendChild(container);
					/*
					THIS IS SO MUCH EASIER, but apparently bad style...
					row.innerHTML+='<div class="container">' +
									'<img src="' + pictures[picIndex][0] + '" alt="Popular Picture" />' + quote +
									'<div class="caption">'  + pictures[picIndex][1] + likes +
								'</div>' + 
								'<span class="fb_link" style="margin-top:-14pt;"><a href="' + 
										pictures[picIndex][3] + '" target="_blank"><img class="fb_small" style="width:16pt;height:16pt;" src="fb_small.png" alt="Facebook" /></a></span></div>';
					*/
					picIndex++;
				} else {
					e('morePicsButton').innerHTML="End of Pictures";
				}
			}
			// $('#uploadedPics').append(row).hide().fadeIn(1000);
			// Attach the new row, but hide it until it can be animated in
			$(row).hide().appendTo('#uploadedPics');
			if (more) {
				e('uploadedPics').innerHTML += '<hr />';
			}
			e('lessPics').style.display="block";
			if (picIndex < pictures.length) {
				e('morePicsButton').innerHTML="Show More";
			} else {
				e('morePicsButton').innerHTML="End of Pictures";
				e('morePicsButton').disabled="disabled";
			}
			// Animate and get the onclick functions. When the html is updated, it
			// appears that the onclick functions are gone, so they have to be reattached
			$('#picRow_' + rowNum).slideDown({queue: false});
			$('#picRow_' + rowNum).hide().fadeIn({duration: 600, queue: false});		
			for (var i = 0; i < picIndex; i++) {
				if (e('picture_' + i)) {
					getOnClick(i);
				}
			}
			// fade('#picRow_' + rowNum);
			if (!firstRun) {			
				setTimeout(function() {$("body").animate({scrollTop: $(document).height()}, 400)}, 400);
			}
			document.body.style.height=$(document).height() + 'px';
		}
		// Don't think it's used anymore, but probably should
		function fade(i) {
			$(i).slideDown({queue: false});
			$(i).fadeIn({duration: 600, queue: false});
			if (i.indexOf('pic') != -1) {
				for (var i = 0; i < picIndex; i++) {
					if (e('picture_' + i)) {
						getOnClick(i);
					}
				}
			}
		}
		
		// Get onclick function of the particular element
		function getOnClick(i) {
			e('picture_' + i).onclick = function() {
				// console.log('click');
				e('largePic').style.display="block";
				e('largePic').innerHTML='<img class="overlay" id="overlay" src="' + pictures[i][4] + '" style="margin: auto"/>';
				$('.overlay').css('max-height', ((window.innerHeight > 0) ? window.innerHeight : screen.height) - 20 + 'px');
				$('.overlay').css('max-width', ((window.innerWidth > 0) ? window.innerWidth : screen.width) - 80 + 'px');
			};
		}
		
		// Remove the last row of images
		function lessPics() {
			// var string = e('uploadedPics').innerHTML;
			if (picIndex > 3) {
				var row = Math.ceil((picIndex - 3) / 3);
				$("#picRow_" + row).fadeOut({duration: 500, queue: false});
				$("#picRow_" + row).slideUp({duration: 500, queue: false});
				setTimeout(function() {
					e('uploadedPics').removeChild(e('uploadedPics').lastChild);
				}, 500);
				var child = e('uploadedPics').lastChild;
				var count = child.childNodes.length;
				e('uploadedPics').removeChild(e('uploadedPics').lastChild);
				// string = string.substr(0, string.lastIndexOf('<div class="row"'));
				// setTimeout(function() {e('uploadedPics').innerHTML=string;}, 490);
				picIndex  = row * 3;
			}
			if (picIndex > 3) {
				e('lessPicsButton').innerHTML="Show Less";
				e('morePicsButton').disabled="";
				e('morePicsButton').innerHTML="Show More";
			} else {
				e('lessPics').style.display="none";
			}
			
			// getClickAfter();
		}
		
		function moreStatuses() {
			e('moreStatusesButton').innerHTML="Loading...";
			var more = false;
			var row = document.createElement('div');
			row.className = "row";
			var rowNum = Math.ceil(statusIndex / 3);
			row.id = "statusRow_" + rowNum;
			for (var i = 0; i < 3; i++) {
				if (statusIndex < statuses.length) {
					var like = "likes";
					if (statuses[statusIndex][1] == 1) {
						like = "like";
					}
					more = true;
					row.innerHTML+='<div class="container"><div class="textBox"><q>' + statuses[statusIndex][0] + 
									'</q></div><div class="caption">' + statuses[statusIndex][1] + ' ' + like + '</div><div>';
					statusIndex++;
				} else {
					e('moreStatusesButton').innerHTML="End of Status Updates";
				}
			}
			$(row).hide();	
			e('statuses').appendChild(row);
			if (more)  {
				e('statuses').innerHTML += '<hr />';
			}
			e('lessStatuses').style.display="block";
			if (statusIndex < statuses.length) {
				e('moreStatusesButton').innerHTML="Show More";
			} else {
				e('moreStatusesButton').innerHTML="End of Status Updates";
				e('moreStatusesButton').disabled="disabled";
			}
			$('#statusRow_' + rowNum).slideDown({queue: false});
			$('#statusRow_' + rowNum).hide().fadeIn({duration: 600, queue: false});			
			if (!firstRun) {			
				setTimeout(function() {$("body").animate({scrollTop: $(document).height()}, 400)}, 400);
			}
			// =fade('#statusRow_' + rowNum);
		}
		
		function lessStatuses() {
			// var string = e('statuses').innerHTML;
			var row = Math.ceil((statusIndex - 3) / 3);
			if (statusIndex > 3) {		
				$("#statusRow_" + row).fadeOut({duration: 500, queue: false});
				$("#statusRow_" + row).slideUp({duration: 500, queue: false});
				setTimeout(function() {
					e('statuses').removeChild(e('statuses').lastChild);
				}, 500);
				var child = e('statuses').lastChild;
				var count = child.childNodes.length;
				e('statuses').removeChild(e('statuses').lastChild);
				// string = string.substr(0, string.lastIndexOf('<div class="row"'));
				// setTimeout( function() {e('statuses').innerHTML=string;}, 500);
				statusIndex  = row * 3;
			}
			if (statusIndex > 3) {
				e('lessStatusesButton').innerHTML="Show Less";
				e('moreStatusesButton').disabled="";
				e('moreStatusesButton').innerHTML="Show More";
			} else {
				e('lessStatuses').style.display="none";
			}
		}
		
		function moreFriends() {
			e('moreFriendsButton').innerHTML="Loading...";
			var more = false;
			var row = document.createElement('div');
			var rowNum = totFIndex / 3;
			row.id = 'fRow_' + rowNum;
			row.className = "row";
			if (total.length != 0) {
				var display = e('friend_1').style.display;
			}
			for (var i = 0; i < 3; i++) {
				var width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
				if (totFIndex < total.length) {
					more = true;
					var com = "comments";
					var like = "likes";
					if (total[totFIndex][2] == 1) {
						com = "comment";
					}
					if (total[totFIndex][1] == 1) {
						like = "like";
					}
					row.innerHTML += '<div class="container"><div class="heading"><h2><img class="friendPic" style="display: ' + display + '" id="friend_' + totFIndex + 
										'" src="https://graph.facebook.com/' + total[totFIndex][4] + '/picture?width=' + width + 
										'" style="display:' + display + '" /><a href="https://facebook.com/' + total[totFIndex][4] + 
										'/" target="_blank">' + total[totFIndex][0] + '</a>' + 
										'</h2></div><div class="caption">' + total[totFIndex][2] + ' ' + com + ' and ' + total[totFIndex][1] + ' ' + like + '</div></div>';
					totFIndex++;
				} else {
					e('moreFriendsButton').innerHTML="End of Top Friends";
				}
			}	
			$(row).hide();
			e('overall').appendChild(row);
			if (more) {
				e('overall').innerHTML += '<hr />';
			}
			e('lessFriends').style.display="block";
			if (totFIndex < total.length) {
				e('moreFriendsButton').innerHTML="Show More";
			} else {
				e('moreFriendsButton').innerHTML="End of Friends";
				e('moreFriendsButton').disabled="disabled";
			}
			$('#fRow_' + rowNum).slideDown({queue: false});
			$('#fRow_' + rowNum).hide().fadeIn({duration: 600, queue: false});		
			for (var i = 0; i < totFIndex; i++) {
				if (e('friend_' + i)) {
					getOnClick2(i);
				}
			}	
			/*		
			if (!firstRun) {
				var top = $('#topHR').offset();
				var bottom = $('#bottomHR').offset();
				var docHeight = $(document).height();
				var picHeight = $('#topFriendContainer').height();
				var searchHeight = $('#search').height();
				var graphHeight = Math.max($('#commentContainer').height(), $('#likeContainer').height());
				var windowHeight = $(window).height();	
				console.log('doc:' + docHeight + ' ' + 'graph:' + graphHeight + ' ' + 'window:' + windowHeight + ' friends:' + $('#topFriendContainer').height() + ' search:' + searchHeight);	
				console.log(picHeight + searchHeight - windowHeight);	
				setTimeout(function() {$("body").animate({scrollTop: (Math.max($('#topFriendContainer').height() + searchHeight - windowHeight, 0))}, 400)}, 400);
			}
			*/
			e('friendImgs').onclick = showHideFriends;
		}
		
		// Set onclick functions for top friends
		function getOnClick2(i) {
			var height = (window.innerHeight > 0) ? window.innerHeight : screen.height;
			height -= 100;
			// console.log(height);
			e('friend_' + i).onclick = function() {
				e('largePic').style.display="block";
				e('largePic').innerHTML='<img class="overlay" id="overlay" src="https://graph.facebook.com/' + total[i][4] + '/picture?height=' + height + '" />';
				$('.overlay').css('max-height', ((window.innerHeight > 0) ? window.innerHeight : screen.height) - 20 + 'px');
				$('.overlay').css('max-width', ((window.innerWidth > 0) ? window.innerWidth : screen.width) - 80 + 'px');
			};
		}
		
		function lessFriends() {
			if (totFIndex > 4) {
				
				var row  = Math.ceil((totFIndex - 3) / 3);
				$("#fRow_" + row).fadeOut({duration: 500, queue: false});
				$("#fRow_" + row).slideUp({duration: 500, queue: false});
				setTimeout(function() {e('overall').removeChild(e('overall').lastChild);}, 500);
				var child = e('overall').lastChild;
				var count = child.childNodes.length;
				e('overall').removeChild(e('overall').lastChild);
				// totFIndex -= count;
				totFIndex = row * 3;
			}
			if (totFIndex > 3) {
				e('lessFriendsButton').innerHTML="Show Less";		
				e('moreFriendsButton').disabled="";
				e('moreFriendsButton').innerHTML="Show More";
			} else {
				e('lessFriends').style.display="none";
			}
		}
		
		function moreComments() {
			e('moreCommentsButton').innerHTML="Loading...";
			var row = document.createElement('div');
			var rowNum = comIndex / 4;
			row.id = "commentRow_" + rowNum;
			for (var i = 0; i < 4; i++) {
				if (comIndex < totalComments.length) {
					var num = parseInt(100.0 * totalComments[comIndex][1] / totalComments[0][1], 10);
					row.innerHTML+='<span class="bar_names">' + totalComments[comIndex][0] + ' / ' + totalComments[comIndex][1] + '</span>' + 
																		'<div class="progress">' + 
																			'<div class="progress-bar progress-bar-' + type[i] + '" style="width:' + num + '%;" title="Stuff"></div>' + 
																		'</div>';
					comIndex++;
				} else {
					e('moreCommentsButton').innerHTML="End of Top Comments";
				}
			}
			$(row).hide();
			e('commentGraph').appendChild(row);
			e('lessComments').style.display="block";
			if (comIndex < totalComments.length) {
				e('moreCommentsButton').innerHTML="Show More";
			} else {
				e('moreCommentsButton').innerHTML="End of Top Comments";
				e('moreCommentsButton').disabled="disabled";
			}
			
			$('#commentRow_' + rowNum).slideDown({queue: false});
			$('#commentRow_' + rowNum).hide().fadeIn({duration: 600, queue: false});
			if (!firstRun) {
				setTimeout(function() {$("body").animate({scrollTop: $(document).height()}, 400)}, 400);
			}
			firstRun = false;
			// fade('#commentRow_' + rowNum);
			moreLikes();
		}
		
		function lessComments() {
			// var string = e('commentGraph').innerHTML;
			if (comIndex > 4 && comIndex >= (likeIndex - 3)) {
				var row  = Math.ceil((comIndex - 4) / 4);
				$("#commentRow_" + row).fadeOut({duration: 500, queue: false});
				$("#commentRow_" + row).slideUp({duration: 500, queue: false});
				setTimeout(function() {
					e('commentGraph').removeChild(e('commentGraph').lastChild);
				}, 500);
				// string = string.substr(0, string.lastIndexOf('<div id="commentRow'));
				comIndex = Math.ceil((comIndex - 4) / 4) * 4;
				// setTimeout(function() {e('commentGraph').innerHTML=string;}, 500);
				if (comIndex > 4) {
					e('lessCommentsButton').innerHTML="Show Less";
				} else { 
					e('lessComments').style.display="none";
				}
				e('moreCommentsButton').innerHTML="Show More";
				e('moreCommentsButton').disabled="";
			}
			lessLikes();
		}
		
		// Show more likes
		function moreLikes() {
			e('moreLikesButton').innerHTML="Loading...";
			var row = document.createElement('div');
			var rowNum = likeIndex / 4;
			row.id = "likeRow_" + rowNum;
			for (var i = 0; i < 4; i++) {
				if (likeIndex < totalLikes.length) {
					var num = parseInt(100.0 * totalLikes[likeIndex][1] / totalLikes[0][1], 10);
					// console.log(num);
					row.innerHTML+='<span class="bar_names">' + 
							totalLikes[likeIndex][0] + ' / ' + 
							totalLikes[likeIndex][1] + '</span>' + 
								'<div class="progress">' + 
									'<div class="progress-bar progress-bar-' + type[i] + 
									'" style="width:' + num + '%;" title="Stuff"></div>' + 
								'</div>';
					likeIndex++;
				} else {
					e('moreLikesButton').innerHTML="End of Top Likes";
				}
			}
			$(row).hide();
			e('likeGraph').appendChild(row);
			e('lessLikes').style.display="block";
			if (likeIndex < totalLikes.length) {
				e('moreLikesButton').innerHTML="Show More";
			} else {
				e('moreLikesButton').innerHTML="End of Top Likes";
				e('moreLikesButton').disabled="disabled";
			}
			$('#likeRow_' + rowNum).slideDown({queue: false});
			$('#likeRow_' + rowNum).hide().fadeIn({duration: 600, queue: false});
			// fade('#likeRow_' + rowNum);
		}
		
		// Hide some likes
		function lessLikes() {
			// var string = e('likes').innerHTML;
			if (likeIndex > 4 && likeIndex >= (comIndex + 1)) {
				var row = Math.ceil((likeIndex - 4) / 4);
				$("#likeRow_" + row).fadeOut({duration: 500, queue: false});
				$("#likeRow_" + row).slideUp({duration: 500, queue: false});
				setTimeout(function() {
					e('likeGraph').removeChild(e('likeGraph').lastChild);
				}, 500);
				// string = string.substr(0, string.lastIndexOf('<div id="likeRow'));
				likeIndex = row * 4;
				// setTimeout(function() {e('likes').innerHTML=string;}, 500);
				if (likeIndex > 4) {
					e('lessLikesButton').innerHTML="Show Less";
				} else { 
					e('lessLikes').style.display="none";
				}
				e('moreLikesButton').innerHTML="Show More";
				e('moreLikesButton').disabled="";
			}
		}
		
		// Load the SDK asynchronously
		(function(d){
			var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
			if (d.getElementById(id)) {return;}
			js = d.createElement('script'); js.id = id; js.async = true;
			js.src = "//connect.facebook.net/en_US/all.js";
			ref.parentNode.insertBefore(js, ref);
		}(document));
		
		// Here we run a very simple test of the Graph API after login is successful.
		// This testAPI() function is only called in those cases.
		function testAPI() {
			// console.log('Welcome!  Fetching your information.... ');
			FB.api('/me', function(response) {
				// console.log('Good to see you, ' + response.name + '.');
			});
		}
		
		// poor name for a function that shows the entire
		// search bar
		function showAll() {
			e('submit').style.display="block";
		}
		
		// Change the visible tab
		function changeTab(tab) {
			e('picPage').style.display="none";
			e('friends').style.display="none";
			e('statusPage').style.display="none";
			e('settings').style.display="none";	
			e('center').style.backgroundImage="none";
			e('right').style.backgroundImage="none";
			e('left').style.backgroundImage="none";
			e(tab).style.backgroundImage="url('bgDark.png')";
			
			if (tab == 'right') {
				$('#friends').fadeIn(600);
				// e('friends').style.display="block";
			} else if (tab == 'center') {
				$('#statusPage').fadeIn(600);
				// e('statusPage').style.display="block";
			} else {
				$('#picPage').fadeIn(600);
				// e('picPage').style.display="block";
			}
		}
		
		// Not currently being used, but shows/hides the\
		// settings tab
		function settingsTab() {
			if (e('settings').style.display == "block") {
				if (e('noParam').style.display != "block") {
					e('picPage').style.display="block";
				}
				e('settings').style.display="none";
			} else {
				e('picPage').style.display="none";
				e('friends').style.display="none";
				e('statusPage').style.display="none";
				e('settings').style.display="block";
			}
		}
		
		// Show/hide the pictures of the person's top friends
		function showHideFriends() {
			e('friendImgs').disabled="disabled";
			for (var i = 0; i < totFIndex; i++) {
				if (e('friend_' + i).style.display=="none") {
					// e('friend_' + i).style.display="block";
					$('#friend_' + i).slideDown({queue: false});
					$('#friend_' + i).hide().fadeIn({duration: 600, queue: false});
				} else {
					$('#friend_' + i).fadeOut({duration: 500, queue: false});
					$('#friend_' + i).slideUp({duration: 500, queue: false});
					// e('friend_' + i).style.display="none";
				}
			}
			setTimeout(function() {e('friendImgs').disabled=""}, 600);
		}
		
		// Shortcut so one doesn't have to type document.getElementById() each time
		function e(ele) {
			return document.getElementById(ele);
		}
	}
})();