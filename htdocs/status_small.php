<?php

	require('webscheduler.inc.php');

	// See if today is Sunday, noon or later, and we have not already modified the firewall
	$day = date('D');
	$hour = (int)date('G');
	$state = get_firewall_state();
	$sleep_start = get_sleep_start();

	header("Refresh: 1");

	function pretty_time($total_seconds) {
		$hours = 0; $minutes = 0; $seconds = $total_seconds;
		$result = "";

		if ($seconds >= 60*60) {
			$hours = floor($seconds / 60*60);
			$seconds -= $hours * 60 * 60;

			$result = sprintf("%d:", $hours);
		}

		if ($seconds >= 60) {
			$minutes = floor($seconds / 60);
			$seconds -= $minutes * 60;

			if($hours > 0) {
				$result .= sprintf("%02d:", $minutes);
			} else {
				$result .= sprintf("%d:", $minutes);
			}
		} else {
			if($hours > 0) {
				$result .= "00:";
			}
		}

		$result .= sprintf("%02d seconds", $seconds);

		return $result;
	}


?>

<html>
	<head>
		<title>Web Scheduler Status</title>
		<style type="text/css">
			body {
				background-color: <?=(get_firewall_state() == "unmodified" ? "green" : "red")?>;
				color: white;
				text-align: center;
			}
			a {
				color: yellow;
			}
		</style>
		<script type="text/javascript">
			function viewandclose() {
				win1 = window.open("index.php");
				self.close();
			}
		</script>
	</head>
	<body>
		<p>
			Web Access: <span style="font-weight: bold;"><?=(get_firewall_state() == "unmodified" ? "Permitted" : "Denied")?></span>
		</p>
<?php
	if ($sleep_start > 0) {
?>
		<p>
			Sleep time remaining: <span style="font-weight: bold;"><?= pretty_time((30*60) - (time() - $sleep_start)) ?></span>
		</p>
<?php
	}
?>
		<p>
			<a href="index.php" onclick="javascript:viewandclose();return(false);" target="_new">View Reminder</a>
		</p>
	</body>
</html>