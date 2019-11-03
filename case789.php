<HTML>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<meta http-equiv=Content-type content="text/html; charset=euc-kr"><meta http-equiv=Cache-Control content=No-Cache><meta http-equiv=Pragma content=No-Cache>
<script language="JavaScript">
</script> 

<HEAD>
<TITLE>Pearsoftware</TITLE>
</HEAD>
   

<center>
   <form name=frmSearch action=search.php>
       <input type="text" name="name" size=60>
      <input type="submit" value="SEARCH"><br>
                        
   </form>
</center>


<?

$fp = fopen('log.txt', 'a');
fwrite($fp, '2되냐?');
fwrite($fp, $HTTP_GET_VARS[name]);
fclose($fp);


include ("db.inc");
            //아무것도 않칠 경우.
   if(!$HTTP_GET_VARS[name])
   { 
      exit;
   } 


            //금칙어 필터링과 오타수정
            switch($HTTP_GET_VARS[name])
           {
      case "spdlqj"  :
                      echo ("네이버<br>");
            search("네이버");
            break;
      case "내이버"  :
                      echo ("네이버<br>");
            search("네이버");
            break;
      case "ㅓㅁㅍㅁ"  :
                      echo ("자바<br>");
            search("java");
            break;
      case "ㅓㅁㅍㅁ"  :
                      echo ("자바<br>");
            search("java");
            break;
      case "やほお"  :
                      echo ("yahoo<br>");
            search("yahoo");
            break;

      case "ㅛ뫠ㅐ"  :
                      echo ("yahoo<br>");
            search("야후");
            break;   
      case "야호"  :
                      echo ("yahoo<br>");
            search("야후");
            break;   
      case "해ㅐ힏 ㅜㄷㅈㄴ"  :
                      echo ("google news<br>");
            search("Google 뉴스");
            break;   
      case "구글뉴스"  :
                      echo ("google news<br>");
            search("Google 뉴스");
            break;   
                        case "구글 뉴스"  :
                      echo ("google news<br>");
            search("Google 뉴스");
            break;   
      case "google news"  :
                      echo ("google news<br>");
            search("Google 뉴스");
            break;   
      case "rnrmfnews"  :
                      echo ("google news<br>");
            search("Google 뉴스");
            break;   
      case "sex" :
                                          echo ("금지어 입니다");
            exit;
            break;
      case "씨발" :
                                          echo ("금지어 입니다");
            exit;
            break;
      case "개새끼" :
                                          echo ("금지어 입니다");
            exit;
            break;
      case "미친놈" :
                                          echo ("금지어 입니다");
            exit;
            break;
      case "섹스" :
                                          echo ("금지어 입니다");
            exit;
            break;
                       default:
         search("%$HTTP_GET_VARS[name]%");
           }



mysql_close($db);
?>