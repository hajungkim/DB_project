<?php
session_start();
header("content-type:text/html; charset=utf-8");
include_once ("lib/common.php");
//include_once ("lib/dbconn.php");
//include_once ("lib/function.php");

session_destroy(); //�α׾ƿ� ����

echo "<meta http-equiv=\"refresh\" content=\"0; url={$common_path}\">";	
exit;

?>