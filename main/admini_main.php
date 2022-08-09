<!--
    관리자 계정의 메인페이지이다. 관리자가 확인할 수 있는 정보들의 페이지로 이동할 수 있다.
-->
<!DOCTYPE html>
    <html lang="ko">
    <head> 
        <meta charset="utf-8">
        <title>Welcome CNU Cinema Theater</title>

        <link rel="stylesheet" type="text/css" href="../css/admini_main.css">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Concert+One&display=swap" rel="stylesheet">

    </head>
    <body>

        <header>
		    <div id="main_header_box">
			    <div id="main_header" class="centered">
				    CNU CINEMA THEATER
			    </div>
		    </div>

        </header>

        <section>
        <div id="main_box">

            <div id="main_title" class="centered">
                관람 기록 또는 질의 결과 확인
            </div>
            <div id="main_select" class="centered">
                	       	
                    <ul>
                        <li><button id=admini_main_btn onclick="location.href='../admini/all_view_record.php'"> 전체 영화 관람 기록 조회 </button>
                    	<li><button id=admini_main_btn onclick="location.href='../admini/join_query.php'"> 조인 질의 </button>
						<li><button id=admini_main_btn onclick="location.href='../admini/group_function_query.php'"> 그룹 함수 질의 </button>
                        <li><button id=admini_main_btn onclick="location.href='../admini/window_function_query.php'"> 윈도우 함수 질의 </button>
                    </ul>

            </div>
        </div>

        <section>
    </body>
</html>