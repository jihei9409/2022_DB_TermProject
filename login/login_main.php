<!--
	로그인 페이지를 구성하는 코드이다. 사용자로부터 회원번호와 비밀번호를 입력받는다.	
-->


<!DOCTYPE html>
<html>
<head> 
	<meta charset="utf-8">
	<title>CNU Cinema Theater</title>

	<link rel="stylesheet" type="text/css" href="../css/login_page.css">

	<!-- <script type="text/javascript" src="./login_check.js"></script> -->

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Concert+One&display=swap" rel="stylesheet">

</head>

<body> 
	

	<header>
		<div id="login_header_box">
			<div id="login_header" class="centered">
				CNU<br>CINEMA<br>THEATER
			</div>
		</div>
    </header>

	<section>
		
	
      		<div id="login_box">

	    		<div id="login_title" class="centered">
                    LOGIN
	    		</div>
	    		<div id="login_form">
          		<form  name="login_main" method="post" action="login_enter.php">		       	
                  	<ul>
                        <li><input type="text" id="id" name="id" placeholder=" 회원번호를 입력하세요." ></li>
                        <li><input type="password" id="pass" name="pass" placeholder=" 비밀번호를 입력하세요." ></li>
                  	</ul>
                  	<div>
                      	<button id=login_btn> LOGIN </button>
                  	</div>
                </form>		    	
        		</div> 
    		</div> 

	</section> 
	
</body>
</html>

