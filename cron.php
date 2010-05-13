<?php

	define('STATEFILE', "/home/trash/trash.state");
	define('SLEEPFILE', "/home/trash/trash.sleep");

	// returns "modified" or "unmodified"
	function get_firewall_state($file = STATEFILE) {
		return file_get_contents($file);
	}

	function get_firewall_mtime($file = STATEFILE) {
		return filemtime($file);
	}

	function myexec($cmd, &$output, &$return) {
		syslog(LOG_NOTICE, $_SERVER['REMOTE_ADDR'] . " [" . $_SESSION['Username'] . "] Executing [" . $cmd . "]");
		return exec($cmd, $output, $return);
	}

	function modify_firewall() {
		//$res = myexec('sudo -u root -g root /bin/bash /home/trash/modify.sh', $output = array(), $retval);
		$res = myexec('/bin/bash /home/trash/modify.sh', $output = array(), $retval);
		if ($retval != 0)
			die("Failed to modify firewall rules: " . $res);
	}

	function unmodify_firewall() {
		//$res = myexec('sudo -u root -g root /bin/bash /home/trash/unmodify.sh', $output = array(), $retval);
		$res = myexec('/bin/bash /home/trash/unmodify.sh', $output = array(), $retval);
		if ($retval != 0)
			die("Failed to unmodify firewall rules: " . $res);
	}

	function set_sleep_off($file = SLEEPFILE) {
		file_put_contents($file, "0");
	}

	function get_sleep_start($file = SLEEPFILE) {
		return file_get_contents($file);
	}


	// See if today is Sunday, noon or later, and we have not already modified the firewall
	$day = date('D');
	$hour = (int)date('G');
	$state = get_firewall_state();
	$sleep_start = get_sleep_start();

	if ( ($day == "Sun" && $hour >= 12) || ($day == "Mon" && $hour < 9) ) {
		if ( $state != "modified" ) {
			echo "Firewall unmodified.\n";

			if ($sleep_start > 0) {
				if ((time() - $sleep_start) >= 60*30) {
					echo "30 minute sleep time passed. Modifying firewall.\n";
					modify_firewall();
					set_sleep_off();
				} else {
					echo "30 minute sleep not passed. (" . (30*60 - (time() - $sleep_start)) . " second(s) left)\n";
				}
			} else {
				echo "No sleep record found.\n";

				if (time() - get_firewall_mtime() >= 60*60*24) {
					echo "Firewall mtime more than 24 hours ago.\n";
					echo "Modifying firewall.\n";
					modify_firewall();
				} else {
					echo "Firewall mtime not more than 24 hours ago.\n";
				}
			}
		} else {
			echo "Firewall already modified.\n";
		}
	} else {
		if ( $state == "modified" ) {
			echo "Time window not met.\n";
			unmodify_firewall();
			echo "Firewall now unmodified.\n";
		}
	}


?>
