<?php

	header("Connection: close");

//	print_r($_SERVER);
	function real_request() {
		return "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	}

	if ( $_SERVER['HTTP_HOST'] != "datc.aaronnet.woot.myvwan.com:81" ) {
		header("Location: http://datc.aaronnet.woot.myvwan.com:81/index.php?dest=" . urlencode(real_request()));
		exit();
	}

	require('webscheduler.inc.php');
	
	if (isset ($_POST['trashdone'])) {
		if ($_POST['trashdone'] == "Yes") {
			if (isset($_POST['pin']) && $_POST['pin'] == "4042") {
				if(get_firewall_state() != "unmodified")
					unmodify_firewall();
				set_sleep_off();
				header("Location: " . (isset($_GET['dest']) ? $_GET['dest'] : "http://www.google.com/ig"));
				exit();
			}
		} else {
			set_trash_sleep();
			unmodify_firewall();
			header("Location: " . (isset($_GET['dest']) ? $_GET['dest'] : "http://www.google.com/ig"));
			exit();
		}
	}


?><html>
	<head>
		<style type="text/css">
			.centered {
				text-align: center;
				position: relative;
				margin: 10px;
				padding: 20px;
			}
		</style>
		<script type="text/javascript">
			function showstat() {
				statwin = window.open("status_small.php","statwin","menubar=no,width=150,height=175,toolbar=no");
			}
		</script>
	</head>
	<body>
<?php
if (!isset($_POST['trashdone'])) { ?>
		<div class="centered" style="text-align: center; font-weight: bold; font-size: 5em; background-color: red; color: white;">
			HAVE YOU TAKEN THE TRASH OUT?
		</div>
		<div class="centered">
			<form method="post">
				<input type="submit" name="trashdone" value="Yes" style="font-size: 5em"/>
				<input type="submit" name="trashdone" value="No" onclick="javascript:showstat();return(true);" style="font-size: 5em"/>
			</form>
		</div>
<?php } else { ?>
		<div class="centered" style="text-align: center; font-weight: bold; font-size: 5em; background-color: red; color: white;">
			ENTER THE PIN:
		</div>
		<div class="centered">
			<form method="post">
				<input type="text" name="pin" />
				<input type="submit" name="trashdone" value="Yes" style="font-size: 5em"/>
			</form>
		</div>
<?php } ?>
	</body>
</html>
