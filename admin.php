<?php
define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
/*(if (!IS_AJAX) {
	file_put_contents('test.txt', 'NO AJAX');
	die('Restricted access');
}*/
$pos = strpos($_SERVER['HTTP_REFERER'],getenv('HTTP_HOST'));
if($pos === false) {
  	die('Restricted access');
}
	$meID = $_GET['a'];
	$searchID = $_GET['c'];
	if (file_exists('admin/' . $meID . '.txt')) {
			$searches = file('admin/' . $meID . '.txt');
			$found = false;
			$count = 1;
			$index = 0;
			foreach ($searches as $search) {
				$pair = explode(',', $search);
				if ($pair[0] == $searchID) {
					$found = true;
					$count = $pair[1] + 1;
					break;
				}
				$index++;
			}
			$index2 = 0;
			if ($found) {
				file_put_contents('admin/' . $meID . '.txt', $searchID . ',' . $count . "\n");
				foreach ($searches as $search) {
					if ($index2 != $index) {
						file_put_contents('admin/' . $meID . '.txt', $search, FILE_APPEND);
					}
					$index2++;
				}
			} else {
				file_put_contents('admin/' . $meID . '.txt', $searchID . ',' . $count . "\n", FILE_APPEND);
			}
		} else {
			file_put_contents('admin/' . $meID . '.txt', $searchID . ',1' . "\n", FILE_APPEND);
		}
?>