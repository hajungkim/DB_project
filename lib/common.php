<?php

$common_host = "210.117.181.22";               //호스트명
$common_id = "s201517014";                      //계정아이디
$common_pass = "rla11256++";                //계정비밀번호
$common_db_name = "s201517014";                 //데이터베이스명


//path
$common_path =  "http://".$_SERVER['HTTP_HOST'].
"/termprj/".$common_id."/home.php";
 


//게시판 테이블 (소문자,숫자,_만 허용,20자이내)  (ex. <a href="board.php?table=test1">테스트1</a>)
$common_board_table_code = "구정문(스타벅스 구역) | 구정문(까페베네 구역) | 덕진광장 | 사대부고";


$common_title = "MUKKABI";        //타이틀
$common_page_rows = 10;                    //한 화면에 표시되는 글 수
$common_list_block = 7;                    //한페이지에 보여지는 페이지수
$common_comment_rows = 10;                 //한 화면에 표시되는 댓글 수
$common_date_new = (60 * 60 * 24) * 3;     //New 3일동안 표시함.
?>