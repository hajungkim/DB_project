<?php
session_start();
header("content-type:text/html; charset=utf-8");
include_once ("lib/common.php");
include_once ("lib/dbconn.php");
include_once ("lib/function.php");
include_once ("head.php");

/*echo "<pre>";
print_r($_SESSION);
echo "</pre>";*/

?>


<div class="row">
  <div class="col-6"><h3>구정문(왼쪽)</h3><? echo latest("test1") ?></div>
  <div class="col-6"><h3>구정문(오른쪽)</h3><? echo latest("test2") ?></div>
</div>
<div class="row">  
  <div class="col-6"><h3>덕진광장</h3><? echo latest("test3") ?></div>
  <div class="col-6"><h3>사대부고</h3><? echo latest("test4") ?></div>
</div>





<? include_once ("tail.php") ?>