<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
		"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Dummy page</title>
	<script type="text/javascript">
		function getLocation() {
			return '<?php print "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'] ?>';
		}
	</script>
</head>
<body>
<?php
print "hash: [" . $_GET['hash'] . "]<br />";
print "self: [" . "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'] . "]";
?>
</body>
</html>