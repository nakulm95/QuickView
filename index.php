<?php

$useragent=$_SERVER['HTTP_USER_AGENT'];
if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
header('Location: mobile.php');

	$since = "";
	$name = "";
	$limit = 1000000;
	if (isset($_POST['noLimit'])) {
		setcookie('limit', 1000000, time()-3600);
		header("Location: index.php");
	}else if (isset($_POST['limit']) && is_numeric($_POST['limit'])) {
		$limit = $_POST['limit'];
		setcookie('limit', $_POST['limit'], time() + 100000000);
	} else if (isset($_COOKIE['limit']))
		$limit = $_COOKIE['limit'];
	if (isset($_POST['name']))
		$name = $_POST['name'];
	$since = 2004;
	$until = 2014;
	$rev = false;
	if (isset($_POST['from']) && strlen($_POST['from']) > 0) {
		$since = $_POST['from'];
	} if (isset($_POST['until']) && strlen($_POST['until']) > 0) {
		$until = $_POST['until'];
	}
	if ($since > $until) {
		$rev = true;
		$tmp = $since;
		$since = $until;
		$until = $tmp;
	}
	$since -= 1970;
	$since *= 31556926;
	$until -= 1970;
	$until *= 31556926;
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>QuickView</title>
    <link href="css/index2.css" rel="stylesheet" type="text/css" />
    <link href="icon.png" rel="icon" type="image/png" />
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/themes/smoothness/jquery-ui.css" />
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/jquery-ui.min.js"></script>
    <script>
	
		// Functions necessary to interact with main.js
	
		function getName() {
			return "<?= htmlspecialchars(html_entity_decode($name)) ?>";
		}
		
		function getUntil() {
			return "<?= round($until); ?>";
		}
		
		function getSince() {
			return "<?= round($since); ?>";
		}
		
		function getLimit() {
			return "<?= $limit ?>";
		}
		
		function getFromValue() {
			return "<?= (isset($_POST['from']) && strlen($_POST['from']) > 0 ?  $since / 31556926 + 1970 : '') ?>";
		}
		
		function getUntilValue() {
			return "<?= (isset($_POST['until']) && strlen($_POST['until']) > 0 ? $until / 31556926 + 1970 : '') ?>";
		}
		
	</script>
    <script src="main.js"></script>
	<!--<script src="//code.jquery.com/jquery-1.10.2.js"></script>-->
    <!--<script src="css/toggle.js" type="text/javascript"></script> -->

</head>

<body>

<div id="fb-root"></div>
<div id="nameParse"></div>
<div id="largePic"></div>
<div id="all">
    <div id="banner">
        <a href="index.php"><img id="binoc" src="banner_binoc.png" alt="QuickView"/></a><!--<img id="settingsImg" src="settings.png">--><br />
    </div>
    <div id="wrapper">
        <div id="search">
            <form action="" method="post">
                <input id="tags" type="text" placeholder="Search for friends" name="name" />
                <div id="submit">
                    Timeframe (optional): From <input id="from" placeholder="Year (2004-2014)" name="from" type="text" pattern="^20((0[4-9])|1[0-4])(\.\d*)?$"> 
                    to <input id="until"  placeholder="Year (exclusive)" name="until" type="text" pattern="^20((0[4-9])|1[0-5])(\.\d*)?$">
                    <input type="submit" value="search" />
                </div>
            </form>
        </div>
        <div id="header">
            <h1><?= $name ?></h1>
            <!--<img src="search.png" alt="search" /> -->
        </div>
        <div id="list">
            <ul id="tabs">
                <li><div id="left">Pics</div></li>
                <li><div id="center">Statuses</div></li>
                <li><div id="right">Friends</div></li>
            </ul>
        </div>
        <div id="main">
            <div id="picPage">
                <div id="uploadedPics">
                    <h2>Most Liked Pictures</h2>
                    <hr />
                </div>
                <div id="morePics">
                    <button id="morePicsButton">Show More</button>
                </div>
                <div id="lessPics">
                    <button id="lessPicsButton">Show Less</button>
                </div>
            </div>
            <div id="statusPage">
                <div id="statuses">
                    <h2>Most Liked Statuses</h2>
                    <hr />
                </div>
                <div id="moreStatuses">
                     <button  id="moreStatusesButton">Show More</button>
                </div>
                <div id="lessStatuses">
                    <button id="lessStatusesButton">Show Less</button>
                </div>
            </div>
            <div id="friends">
                <h2>Top Facebook Friends</h2>
                    <hr id="topHR" />
                <div class="container" id="topFriendContainer">
                    <div id="overall">
                        <h3>Overall</h3>
                   		<button id="friendImgs">Show/Hide Pictures</button>
                        <hr />
                    </div>
                    <div id="moreFriends">
                         <button id="moreFriendsButton">Show More</button>
                    </div>
                    <div id="lessFriends">
                        <button id="lessFriendsButton">Show Less</button>
                    </div>
                </div>
                <hr id="bottomHR" />
                <div class="container" id="commentContainer">
                    <div id="comments">
                        <h3>Comments</h3>
                        <hr />
                        <div id="commentGraph"></div>
                    </div>
                    <div id="moreComments">
                         <button id="moreCommentsButton">Show More</button>
                    </div>
                    <div id="lessComments">
                        <button id="lessCommentsButton">Show Less</button>
                    </div>
                </div>
                <div class="container" id="likeContainer">
                    <div id="likes">
                        <h3>Likes</h3>
                        <hr />
                        <div id="likeGraph"></div>
                    </div>
                    <div id="moreLikes">
                         <button id="moreLikesButton">Show More</button>
                    </div>
                    <div id="lessLikes">
                        <button id="lessLikesButton">Show Less</button>
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
        <div id="users"></div>
        <div id="login">
        	<h2>Welcome to Quickview! Log in to continue</h2>
            <div class="fb-login-button" scope="basic_info, friends_photos, friends_status, 
                    user_photos, user_status" data-size="xlarge" data-show-faces="false" data-auto-logout-link="true"></div>
        </div>
    </div>
</div>
</body>
</html>
<script>


</script>
