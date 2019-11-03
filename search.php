<?
include_once ("lib/common.php");
include_once ("lib/dbconn.php");
include_once ("lib/function.php");
?>

<html lang="ko">
<div class="row">
  <div class="col-lg-10 col-offset-3">
<head>
   <meta charset="UTF-8">
   <title>input - search</title>
      <style>
      
      form input[type="text"]{
         font-size: 23px;
      }
      
      form input[type="text"]{
         height: 40px;
         width: 320px;//검색할 단어
         font-size: 20px;
         border: 1px #ffa;
         background: #ffa;
         bertical-align: middle;
         color: black;
         
      }
      
      form button{
         
         border-radius: 5px;
         height: 40px;
         width: 320px;
         font-size: 23px;
         border: 20px #ffa;
         background: #FF7F00;
         bertical-align: middle;
         color: #fff;
         
      }
      form select{
         
         height: 40px;
         font-size: 23px;
         border: 1px #ffa;
         background: #ffa;
         bertical-align: middle;
         color: #black;
         
      }
      #container {width:950px; margin:100px aut10;}
      #content {float :left;width:35%;}
      #content-right{float:right;width:65%;}

      
      </style>
</head>
   <body>
      <form method = "post" action = "result.php">
      <div id="container">
      <div id="content">
      <h1>  MUKKABI</h1>  
	  
   <SELECT id="" name='r_id_num' >
		<OPTION VALUE="0">위치 전체</OPTION>
        <OPTION VALUE="0">구정문(신정문)</OPTION>
        <OPTION VALUE="10000">구정문(박물관)</OPTION>
		<OPTION VALUE="20000">사대부고</OPTION>
    </SELECT>
	
	   
	<SELECT id ="" name= 't_id_val'>
		<OPTION VALUE="100">전체</OPTION>
        <OPTION VALUE="0">한식</OPTION>
        <OPTION VALUE="11111">중식</OPTION>
        <OPTION VALUE="22222">일식</OPTION>
        <OPTION VALUE="33333">양식</OPTION>
		<OPTION VALUE="44444">분식및기타</OPTION>		
	</SELECT>
   
   
   <h2>
   <input type="text" placeholder="음식점을 입력하세요" name='searname'></h2>
   <button type="submit">SEARCH</button> 
   </div>
   <div id="content-right">
   <img align="center" src="aa.jpg" width="240" height="220" >
   </div>
   </div>
   </form>
    
   </body>
  </div>

</div>
</html>