<?php
include_once ("lib/common.php");
include_once ("lib/dbconn.php");
include_once ("lib/function.php");
include_once ("head.php");
mysql_query("SET NAMES utf8");
$condi1 = $_POST['t_id_val'];
$condi2 = $_POST['r_id_num'];
$condi3 = $_POST['searname'];
echo $query;
if($condi3==null){
	if($condi1=="100"){
		$query = "select r_name,call_num,position,score from restaurant where r_id >= $condi2 ORDER BY score desc";
	}else
	{
		$query = "select r_name,call_num,position,score from restaurant where t_id = $condi1 and r_id >= $condi2 ORDER BY score desc";
	}
}else{
	if($condi1=="100"){
		$query = "select r_name,call_num,position,score from restaurant where r_id >= $condi2 and r_name like '%$condi3%' ORDER BY score desc";
	}else{
		$query = "select r_name,call_num,position,score from restaurant where t_id = $condi1 and r_id >= $condi2 and r_name like '%$condi3%' ORDER BY score desc";
		}
}
//$query = "select r_name,call_num,position,score from restaurant where t_id = $condi1 and r_id >= $condi2 ORDER BY score desc";
$result = mysql_query($query,$connect);
?>

<html lang="ko">
<META charset="UTF-8">


<table align=center width="800" border="" cellpadding="10">
	<tr align=center>
				<td bgcolor="#4374D9"><font color=white>식당명</font></td>
				<td bgcolor="#4374D9"><font color=white>전화번호</font></td>
				<td bgcolor="#4374D9"><font color=white>위치</font></td>
				<td bgcolor="#4374D9"><font color=white>별점</font></td>
		</tr> 
	<ul>
	<?php
		while($row=mysql_fetch_array($result)){
	?></ul> 
		<tr>
				<td ><? print $row["r_name"]?></td>
				<td ><? print $row["call_num"]?></td>
				<td name = "mapname" ><? print $row["position"]?>
				<form method = "post" action = "map.php">
				<input type = "submit" value="map" action = "map.php"/></form></td>
				
				<td><? print $row["score"]?></td>
		</tr>
	<ul>
	<?php
	}
	?>
	</ul>
	</table>
	
	</html>
	
	
	<?php
//if ($_GET['mode'] == "reply")   if ($_GET['mode'] == "modify")

include_once ("tail.php");
?>