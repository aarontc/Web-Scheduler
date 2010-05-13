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
		$res = myexec('sudo -u root -g root /bin/bash /home/trash/modify.sh', $output = array(), $retval);
		//$res = myexec('/bin/bash /home/trash/modify.sh', $output = array(), $retval);
		if ($retval != 0)
			die("Failed to modify firewall rules: " . $res);
	}

	function unmodify_firewall() {
		$res = myexec('sudo -u root -g root /bin/bash /home/trash/unmodify.sh', $output = array(), $retval);
		//$res = myexec('/bin/bash /home/trash/unmodify.sh', $output = array(), $retval);
		if ($retval != 0)
			die("Failed to unmodify firewall rules: " . $res);
	}

	function set_sleep_off($file = SLEEPFILE) {
		file_put_contents($file, "0");
	}

	function get_sleep_start($file = SLEEPFILE) {
		return file_get_contents($file);
	}

	function set_trash_sleep($file = SLEEPFILE) {
		file_put_contents($file, time());
	}

?>
