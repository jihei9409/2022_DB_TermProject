<!--
   마이페이지의 메인화면이다. 원하는 조회 내역 버튼을 누르면 조회 결과 페이지로 이동할 수 있다. 
-->

<!DOCTYPE html>
<html>
<head> 
	<meta charset="utf-8">
	<title>CNU Cinema Theater</title>

	<link rel="stylesheet" type="text/css" href="../css/mypage_main.css">

	<script type="text/javascript" src="./login_check.js"></script>

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Concert+One&display=swap" rel="stylesheet">

</head>

<body> 
	

	<header>
		<div id="mypage_header_box">
			<div id="mypage_header" class="centered" onclick="location.href='../main/user_main.php'">
				CNU CINEMA THEATER
			</div>
		</div>
    </header>

	<section>
		
	
      		<div id="mypage_box">

	    		<div id="mypage_title" class="centered">
                    MY PAGE
	    		</div>
	    		<div id="mypage_form">
          				       	
                	<ul>
                    	<li><button id=mypage_btn onclick="location.href='book_list.php'"> 예매 내역 </button>
                    	<li><button id=mypage_btn onclick="location.href='cancel_list.php'"> 취소 내역 </button>
						<li><button id=mypage_btn onclick="location.href='last_watch_list.php'"> 지난 관람 내역 </button>
                	</ul>
                		    	
        		</div> 
    		</div> 

	</section> 
	
</body>
</html>

