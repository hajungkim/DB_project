<?php

//게시판 테이블
function get_board_table_code($str){
	global $common_board_table_code;
	$board_table_code = $common_board_table_code;
	$array_board_table_code = array_filter(explode("|", $board_table_code));	
	for ($i=0; $i<count($array_board_table_code); $i++) if (trim($array_board_table_code[$i]) == trim($str)) return true;
	return false;
}


//게시판 db 테이블 idx 레코드
function get_board_idx_code($table, $idx){
	global $connect;
	$tmp_sql = "select count(*) as cnt from qboard where qb_table = '".trim($table)."' and qb_idx = '".trim($idx)."'";
	$tmp_result = mysql_query($tmp_sql, $connect);
	$tmp_row = mysql_fetch_array($tmp_result);
	return $tmp_row['cnt'];
}


//비밀번호
function get_password($str){
	global $connect;
	$tmp_sql = "select password('".$str."') as db_passwd";
	$tmp_result = mysql_query($tmp_sql, $connect);
	$tmp_row = mysql_fetch_array($tmp_result);
	return $tmp_row['db_passwd'];
}


//한페이지에 보여줄 행, 현재페이지, 총페이지수, URL
function get_paging($write_pages, $cur_page, $total_page, $url){
	$str = "<ul class='pagination'>";
	$start_page = (floor(($cur_page - 1) / $write_pages)) * $write_pages + 1;
	//페이징에 표시될 마지막 페이지 구하기
	$end_page = $start_page + $write_pages - 1;
	if ($total_page <= $end_page) $end_page = $total_page; 
	//이전 페이징 영역으로 가는 링크
	if ($start_page > 1) {
		$prev_page = $start_page - 1;
		$str .= "<li><a href='".$url.$prev_page."'>&laquo;</a></li>";
	}
	//페이지들 출력 부분 링크
	for ($i=$start_page; $i<=$end_page; $i++) {
		if ($cur_page == $i) $str .= "<li class='disabled'><a href='".$url.$i."'>".$i."</a></li>";
		else $str .= "<li><a href='".$url.$i."'>".$i."</a></li>";    
	}
	//다음 페이징 영역으로 가는 링크
	if ($end_page < $total_page) {
		$next_page = $end_page + 1;
		$str .= "<li><a href='".$url.$next_page."'>&raquo;</a></li>";
	}
	$str .= "</ul>";
	return $str;
}



//문자열 끊기
function get_mb_strimwidth($content, $num){
	return mb_strimwidth($content, 0, $num, "...", "UTF-8");
}


function ip_hidden($ip){  
    $ip = preg_replace("|([0-9]{1,3}\.)([0-9]{1,3}\.)([0-9]{1,3}\.)([0-9]{1,3})|","\\1"."\\2"."☆."."\\4", $ip);  
    return $ip;  
}  


//경고메세지를 경고창으로
function alert($msg='', $url=''){
    if (!$msg) $msg = '올바른 방법으로 이용해 주십시오.';
	echo "<script type='text/javascript'>alert('$msg');";
    if (!$url) echo "history.go(-1);";
    echo "</script>";
    if ($url) goto_url($url);
    exit;
}


//URL 이동
function goto_url($url){
    echo "<script type='text/javascript'>location.replace('$url');</script>";
    exit;
}


//경고메세지 출력후 창을 닫음
function alert_close($msg){
    echo "<script type='text/javascript'>alert('$msg');window.close();</script>";
    exit;
}


//날짜기간
function get_date_period($date){
	return intval((strtotime(date("Y-m-d")) - strtotime(substr($date, 0, 10))) / 86400);
}


//New 표시
function get_date_new($date){
	global $common_date_new;
	$now_time = time();
	$rest_time = strtotime($date) + $common_date_new; 
	if ($now_time <= $rest_time) return "<span class='badge'>new</span>";
}


//최신글 추출
function latest($table, $rows=5, $subject_len=40){
	global $connect;
	$tmp_sql = "select * from qboard where qb_table = '".trim($table)."' and qb_ord = 0 order by qb_idx desc limit ".$rows;
	$tmp_result = mysql_query($tmp_sql, $connect); 
	$content .= "<ul class='list-group'>";
	while ($tmp_row = mysql_fetch_array($tmp_result)){
		$qb_idx = $tmp_row['qb_idx'];
		$qb_table = $tmp_row['qb_table'];
		$qb_subject = htmlspecialchars(get_mb_strimwidth($tmp_row['qb_subject'], $subject_len));
		$qb_date = str_replace("-", "/", (substr($tmp_row['qb_date'], 2, 8)));
		$content .= "<li class='list-group-item'>";
		$content .= "<span class='badge'>".$qb_date."</span>";
		$content .= "<a href=\"view.php?table=".$table."&idx=".$qb_idx."\">".$qb_subject."</a>";
		$content .= "</li>";
	}
	$content .= "</ul>";
	return $content;
}


//댓글
function comment_cnt($parent){
	global $connect;
	$tmp_sql = "select count(*) as comment_cnt from qboard_comment where qc_parent = ".trim($parent);
	$tmp_result = mysql_query($tmp_sql, $connect);
	$tmp_row = mysql_fetch_array($tmp_result);
	$comment_cnt = $tmp_row['comment_cnt'];
	if ($comment_cnt != 0) return "<span class='badge'>".$comment_cnt."</span>";
}





















/* 참고 : 그누보드4 http://sir.co.kr/main/gnuboard4/ */


// 내용을 변환
function conv_content($content, $html=0){

    if ($html)
    {
        $source = array();
        $target = array();

        $source[] = "//";
        $target[] = "";

        if ($html == 2) { // 자동 줄바꿈
            $source[] = "/\n/";
            $target[] = "<br/>";
        }

        // 테이블 태그의 갯수를 세어 테이블이 깨지지 않도록 한다.
        $table_begin_count = substr_count(strtolower($content), "<table");
        $table_end_count = substr_count(strtolower($content), "</table");
        for ($i=$table_end_count; $i<$table_begin_count; $i++)
        {
            $content .= "</table>";
        }

        //$content = preg_replace_callback("/<([^>]+)>/s", 'bad130128', $content); 

        $content = preg_replace($source, $target, $content);

        // XSS (Cross Site Script) 막기
        // 완벽한 XSS 방지는 없다.
        
        // 이런 경우를 방지함 <IMG STYLE="xss:expr/*XSS*/ession(alert('XSS'))">
        //$content = preg_replace("#\/\*.*\*\/#iU", "", $content);
        // 위의 정규식이 아래와 같은 내용을 통과시키므로 not greedy(비탐욕수량자?) 옵션을 제거함. ignore case 옵션도 필요 없으므로 제거
        // <IMG STYLE="xss:ex//*XSS*/**/pression(alert('XSS'))"></IMG>
        $content = preg_replace("#\/\*.*\*\/#", "", $content);

        // object, embed 태그에서 javascript 코드 막기
        //$content = preg_replace_callback("#<(object|embed)([^>]+)>#i", "bad120422", $content);

        $content = preg_replace("/(on)([a-z]+)([^a-z]*)(\=)/i", "&#111;&#110;$2$3$4", $content);
        $content = preg_replace("/(dy)(nsrc)/i", "&#100;&#121;$2", $content);
        $content = preg_replace("/(lo)(wsrc)/i", "&#108;&#111;$2", $content);
        $content = preg_replace("/(sc)(ript)/i", "&#115;&#99;$2", $content);
        $content = preg_replace_callback("#<([^>]+)#", create_function('$m', 'return "<".str_replace("<", "&lt;", $m[1]);'), $content);
        //$content = preg_replace("/\<(\w|\s|\?)*(xml)/i", "", $content);
        $content = preg_replace("/\<(\w|\s|\?)*(xml)/i", "_$1$2_", $content);

        // 플래시의 액션스크립트와 자바스크립트의 연동을 차단하여 악의적인 사이트로의 이동을 막는다.
        // value="always" 를 value="never" 로, allowScriptaccess="always" 를 allowScriptaccess="never" 로 변환하는데 목적이 있다.
        $content = preg_replace("/((?<=\<param|\<embed)[^>]+)(\s*=\s*[\'\"]?)always([\'\"]?)([^>]+(?=\>))/i", "$1$2never$3$4", $content);

        // 이미지 태그의 src 속성에 삭제등의 링크가 있는 경우 게시물을 확인하는 것만으로도 데이터의 위변조가 가능하므로 이것을 막음
        $content = preg_replace("/<(img[^>]+delete\.php[^>]+bo_table[^>]+)/i", "*** CSRF 감지 : &lt;$1", $content);
        $content = preg_replace("/<(img[^>]+delete_comment\.php[^>]+bo_table[^>]+)/i", "*** CSRF 감지 : &lt;$1", $content);
        $content = preg_replace("/<(img[^>]+logout\.php[^>]+)/i", "*** CSRF 감지 : &lt;$1", $content);
        $content = preg_replace("/<(img[^>]+download\.php[^>]+bo_table[^>]+)/i", "*** CSRF 감지 : &lt;$1", $content);

        $content = preg_replace_callback("#style\s*=\s*[\"\']?[^\"\']+[\"\']?#i",
                    create_function('$matches', 'return str_replace("\\\\", "", stripslashes($matches[0]));'), $content);

        $pattern = "";
        $pattern .= "(e|&#(x65|101);?)";
        $pattern .= "(x|&#(x78|120);?)";
        $pattern .= "(p|&#(x70|112);?)";
        $pattern .= "(r|&#(x72|114);?)";
        $pattern .= "(e|&#(x65|101);?)";
        $pattern .= "(s|&#(x73|115);?)";
        $pattern .= "(s|&#(x73|115);?)";
        //$pattern .= "(i|&#(x6a|105);?)";
        $pattern .= "(i|&#(x69|105);?)";
        $pattern .= "(o|&#(x6f|111);?)";
        $pattern .= "(n|&#(x6e|110);?)";
        //$content = preg_replace("/".$pattern."/i", "__EXPRESSION__", $content);
        $content = preg_replace("/<[^>]*".$pattern."/i", "__EXPRESSION__", $content); 
        // <IMG STYLE="xss:e\xpression(alert('XSS'))"></IMG> 와 같은 코드에 취약점이 있어 수정함. 121213
        $content = preg_replace("/(?<=style)(\s*=\s*[\"\']?xss\:)/i", '="__XSS__', $content); 
        $content = bad_tag_convert($content);
    }
    else // text 이면
    {
        // & 처리 : &amp; &nbsp; 등의 코드를 정상 출력함
        $content = html_symbol($content);

        // 공백 처리
		//$content = preg_replace("/  /", "&nbsp; ", $content);
		$content = str_replace("  ", "&nbsp; ", $content);
		$content = str_replace("\n ", "\n&nbsp;", $content);

        $content = get_text($content, 1);

        $content = url_auto_link($content);
    }

    return $content;
}

// HTML SYMBOL 변환
// &nbsp; &amp; &middot; 등을 정상으로 출력
function html_symbol($str){
    return preg_replace("/\&([a-z0-9]{1,20}|\#[0-9]{0,3});/i", "&#038;\\1;", $str);
}

// TEXT 형식으로 변환
function get_text($str, $html=0){
    // TEXT 출력일 경우 &amp; &nbsp; 등의 코드를 정상으로 출력해 주기 위함
    if ($html == 0) $str = html_symbol($str);

    $source[] = "/</";
    $target[] = "&lt;";
    $source[] = "/>/";
    $target[] = "&gt;";
    //$source[] = "/\"/";
    //$target[] = "&#034;";
    $source[] = "/\'/";
    $target[] = "&#039;";
    //$source[] = "/}/"; $target[] = "&#125;";
    if ($html) {
        $source[] = "/\n/";
        $target[] = "<br/>";
    }

    return preg_replace($source, $target, $str);
}

// way.co.kr 의 wayboard 참고
function url_auto_link($str)
{
    // 속도 향상 031011
    $str = preg_replace("/&lt;/", "\t_lt_\t", $str);
    $str = preg_replace("/&gt;/", "\t_gt_\t", $str);
    $str = preg_replace("/&amp;/", "&", $str);
    $str = preg_replace("/&quot;/", "\"", $str);
    $str = preg_replace("/&nbsp;/", "\t_nbsp_\t", $str);
    $str = preg_replace("/([^(http:\/\/)]|\(|^)(www\.[^[:space:]]+)/i", "\\1<A HREF=\"http://\\2\" TARGET='_blank'>\\2</A>", $str);
    //$str = preg_replace("/([^(HREF=\"?'?)|(SRC=\"?'?)]|\(|^)((http|https|ftp|telnet|news|mms):\/\/[a-zA-Z0-9\.-]+\.[\xA1-\xFEa-zA-Z0-9\.:&#=_\?\/~\+%@;\-\|\,]+)/i", "\\1<A HREF=\"\\2\" TARGET='$config[cf_link_target]'>\\2</A>", $str);
    // 100825 : () 추가
    // 120315 : CHARSET 에 따라 링크시 글자 잘림 현상이 있어 수정
    if (strtoupper($g4['charset']) == 'UTF-8') {
        $str = preg_replace("/([^(HREF=\"?'?)|(SRC=\"?'?)]|\(|^)((http|https|ftp|telnet|news|mms):\/\/[a-zA-Z0-9\.-]+\.[가-힣\xA1-\xFEa-zA-Z0-9\.:&#=_\?\/~\+%@;\-\|\,\(\)]+)/i", "\\1<A HREF=\"\\2\" TARGET='_blank'>\\2</A>", $str);
    } else {
        $str = preg_replace("/([^(HREF=\"?'?)|(SRC=\"?'?)]|\(|^)((http|https|ftp|telnet|news|mms):\/\/[a-zA-Z0-9\.-]+\.[\xA1-\xFEa-zA-Z0-9\.:&#=_\?\/~\+%@;\-\|\,\(\)]+)/i", "\\1<A HREF=\"\\2\" TARGET='_blank'>\\2</A>", $str);
    }
    // 이메일 정규표현식 수정 061004
    //$str = preg_replace("/(([a-z0-9_]|\-|\.)+@([^[:space:]]*)([[:alnum:]-]))/i", "<a href='mailto:\\1'>\\1</a>", $str);
    $str = preg_replace("/([0-9a-z]([-_\.]?[0-9a-z])*@[0-9a-z]([-_\.]?[0-9a-z])*\.[a-z]{2,4})/i", "<a href='mailto:\\1'>\\1</a>", $str);
    $str = preg_replace("/\t_nbsp_\t/", "&nbsp;" , $str);
    $str = preg_replace("/\t_lt_\t/", "&lt;", $str);
    $str = preg_replace("/\t_gt_\t/", "&gt;", $str);

    return $str;
}

// 악성태그 변환
function bad_tag_convert($code)
{
	//$code = preg_replace_callback("#(\<(embed|object)[^\>]*)\>(\<\/(embed|object)\>)?#i",
	//embed 또는 object 태그를 막지 않는 경우 필터링이 되도록 수정
	$code = preg_replace_callback("#(\<(embed|object)[^\>]*)\>?(\<\/(embed|object)\>)?#i",
				create_function('$matches', 'return "<div class=\"embedx\">보안문제로 인하여 관리자 아이디로는 embed 또는 object 태그를 볼 수 없습니다. 확인하시려면 관리권한이 없는 다른 아이디로 접속하세요.</div>";'),
				$code);

    //return preg_replace("/\<([\/]?)(script|iframe)([^\>]*)\>/i", "&lt;$1$2$3&gt;", $code);
    // script 나 iframe 태그를 막지 않는 경우 필터링이 되도록 수정
    return preg_replace("/\<([\/]?)(script|iframe|form)([^\>]*)\>?/i", "&lt;$1$2$3&gt;", $code);
}






























?>
