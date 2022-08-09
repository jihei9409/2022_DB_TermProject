<!-- 
    사용자 계정의 메인페이지이다. 사용자로부터 영화 제목, 관람 예정일을 입력 받는 화면을 보여준다.
-->
<!DOCTYPE html>
    <html lang="ko">
    <head> 
        <meta charset="utf-8">
        <title>Welcome CNU Cinema Theater</title>

        <link rel="stylesheet" type="text/css" href="../css/user_main.css">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Concert+One&display=swap" rel="stylesheet">

    </head>
    <body>

        <header>
            
            <div>   <!--마이페이지로 이동할 경우-->
                <button id=mypage_btn onclick="location.href='../mypage/mypage_main.php'"> MY </href=></button>
            </div>

		    <div id="main_header_box">
			    <div id="main_header" class="centered">
				    CNU CINEMA THEATER
			    </div>
		    </div>

        </header>

        <section>
        <div id="main_box">

            <div id="main_title" class="centered">
                영화 제목 혹은 관람 예정일 입력
            </div>
            <div id="main_search_form" class="centered">
                <form  name="main" method="get" action="../search/search_page.php">		       	
                    <ul>
                        <li><input type="text" id="m_name" name="m_name" placeholder=" 영화 제목을 입력하세요." ></li>
                        <li><input type="date" id="m_date" name="m_date" placeholder=" 관람 예정 일자를 입력하세요." ></li>
                    </ul>

                    <div>   <!--영화 검색 결과 화면으로 이동할 경우-->
                      	<button id=search_btn> SEARCH </button>
                  	</div>
                            
                </form>
            </div>
        </div>

        <section>
    </body>
</html>