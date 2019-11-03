<?php
session_start();
header("content-type:text/html; charset=utf-8");
include_once ("lib/common.php");
include_once ("lib/dbconn.php");
include_once ("head.php");
include_once ("search.php");
?>
<?php
 //if ($_GET['mode'] == "reply")   if ($_GET['mode'] == "modify")

include_once ("tail.php");
?>