<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<?php

include_once ("lib/common.php");
include_once ("lib/dbconn.php");
include_once ("head.php");
?>


<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta name="generator" content="kimsQ">
<meta name="author" content="<?=$browse['name']?>">
<meta name="keywords" content="<?=$browse['title']?> <?=$R['tag']?>">
<title>네이버 맵</title>
</head>

<body  leftmargin=0 topmargin=0 marginwidth=0 >
<?
function getUTF_8($str)
{
	return iconv('EUC-KR','UTF-8',$str);
}

function getEUC_KR($str)
{
	return iconv('UTF-8','EUC-KR',$str);
}

function getnavermapXml2($navermapxml_url,&$ygeopoint_x,&$ygeopoint_y){
	$pquery = $navermapxml_url;
	$fp = fsockopen ("maps.naver.com", 80, $errno, $errstr, 30);
	if (!$fp) {
		echo "$errstr ($errno)";
	} else {
		fputs($fp, "GET /api/geocode.php?");
		fputs($fp, $pquery);
		fputs($fp, " HTTP/1.1\r\n");
		fputs($fp, "Host: maps.naver.com\r\n");
		fputs($fp, "Connection: Close\r\n\r\n");
		$header = "";
		while (!feof($fp)) {
			$out = fgets ($fp,512);
			if (trim($out) == "") {
				break;
			}
			$header .= $out;
		}
		$mapbody = "";
		while (!feof($fp)) {
			$out = fgets ($fp,512);
			$mapbody .= getUTF_8($out);
		}
		$idx = strpos(strtolower($header), "transfer-encoding: chunked");
		if ($idx > -1) { // chunk data
			$temp = "";
			$offset = 0;
			do {
				$idx1 = strpos($mapbody, "\r\n", $offset);
				$chunkLength = hexdec(substr($mapbody, $offset, $idx1 - $offset));

				if ($chunkLength == 0) {
					break;
				} else {
					$temp .= substr($mapbody, $idx1+2, $chunkLength);
					$offset = $idx1 + $chunkLength + 4;
				}
			} while(true);
			$mapbody = $temp;
		}
		fclose ($fp);
	  }
	// 여기까지 주소 검색 xml 파싱

	$map_x_point_1=explode("<x>", ($mapbody));
	$map_x_point_2=explode("</x>", $map_x_point_1[1]);
	$ygeopoint_x=$map_x_point_2[0];
	$map_y_point_1=explode("<y>", ($mapbody));
	$map_y_point_2=explode("</y>", $map_y_point_1[1]);
	$ygeopoint_y=$map_y_point_2[0];
#
}//end function
#
$naver_mapkey	= "Se7ua_2nYZ";//http://dev.naver.com/openapi/register 지도키 발급코드
//$addr = $_POST['mapname']// <== 여기에 주소를 입력하거나 값을 넘겨주면 되겠지요
$navermapxml_url='key='.$naver_mapkey.'&query='.getUTF_8($addr);
#
getnavermapXml2($navermapxml_url,$ygeopoint_x,$ygeopoint_y);
?>
<!-- 네이버 지도 키 값 -->
<script type="text/javascript" src="http://map.naver.com/js/naverMap.naver?key=<?php echo $naver_mapkey?>"></script>
<!-- 네이버 지도 키 값 끝 -->
<style>
#mapcontainer{
	width: <?php echo $map_width?>px;
	height: <?php echo $map_height?>px;http://naver.com/
	margin:0;
}
</style>
<div id="mapbody"></div>
<div id="display"></div>
<script type="text/javascript">

 var x_point = '<? echo ($ygeopoint_x)?$ygeopoint_x:0; ?>';
 var y_point = '<? echo ($ygeopoint_y)?$ygeopoint_y:0; ?>';
 /*
 * 지도API 2.0은 기존의 카텍 좌표 외에도 위경도 좌표를 지원합니다.
 * 위경도 좌표를 사용하기 위해서는 기존의 NPoint 클래스 대신 NLatLng 클래스를 사용해야 합니다.
 *
 * http://maps.naver.com/api/geocode.php 에서 "경기도성남시정자1동25-1"을 검색한 결과인
 * x : 321033, y : 529749
 * 를 예로 들어 설명해 보겠습니다.
 *
 * 편의를 위해 전역변수로 mapObj, tm128, latlng를 선언해 두었습니다.
 */
var mapObj = new NMap(document.getElementById('mapbody'),<?php echo $map_width?>,<?php echo $map_height?>);
var tm128 = new NPoint(x_point,y_point);
var latlng;
/*
 * 경기도성남시정자1동25-1의 위치로 이동합니다. 레벨은 1로 지정하였습니다.
 * 인덱스맵과 확대/축소 컨트롤러를 등록하고 마우스 줌인/아웃을 활성화 하였습니다.
 */
mapObj.setCenterAndZoom(tm128, <?php echo $yzoom?>);
mapObj.addControl(new NZoomControl());
mapObj.enableWheelZoom();
latlng = mapObj.fromTM128ToLatLng(tm128);
/*
 * NMark도 마찬가지로 tm128 대신 위경도를 사용하여 아이콘을 표시하였습니다.
 */
var mark = new NMark(latlng, new NIcon('../image/ic_spot.png',new NSize(52,41),new NSize(14,40)));
mapObj.addOverlay(mark);
</script>
</body>
</html>
<?php
 //if ($_GET['mode'] == "reply")   if ($_GET['mode'] == "modify")

include_once ("tail.php");
?>