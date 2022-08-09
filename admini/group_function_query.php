<!--그룹함수질의 결과를 보여준다.-->
<?php
    include '../login/login_info.php';
    include '../oracle_in.php';

    try {
        $conn = new PDO($dsn, $username, $password);
    } catch (PDOException $e) {
        echo("에러 내용: ".$e -> getMessage());
    }
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0"
        crossorigin="anonymous">
    <style> a { text-decoration: none; } </style>

    <link rel="stylesheet" type="text/css" href="../css/mypage_list.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Concert+One&display=swap" rel="stylesheet">

    <title>CNU Cinema</title>
   
</head>

<body>
    <div class="container">

    <table class="table table-bordered text-center">
        <thead>
            <div id="mylist_header_box">
			    <div id="mylist_header" class="centered" onclick="location.href='../main/admini_main.php'">
				    CNU CINEMA THEATER
			    </div>
		    </div>

			<div id="mylist_title" class="centered">
				    그룹 함수 질의 결과
			</div>

            <div id = "query_title">
                질의 내용
            </div>
            <div id="query_info" class="centered">
            상영되었던 영화의 영화별, 상영 시간별 관람객의 수를 집계하여 출력하시오.
			</div>
		    
            <tr>
                <th>영화 제목</th>
                <th>상영 시작 시간</th>
                <th>관객 수</th>
            </tr>
        </thead>
        

        <tbody>
        
<?php
            $sql = (
                "SELECT
                    CASE GROUPING(M.TITLE)
                    WHEN 1 THEN '총 관객수'
                    ELSE M.TITLE END AS MOVIE_TITLE,
                    S.SDATETIME, COUNT(*) CNT_VIEWERS
                    FROM MOVIE M, TICKETING T, MSCHEDULE S
                    WHERE M.MID = S.MID
                    AND T.SID = S.SID
                    AND T.STATUS = 'W'
                    GROUP BY ROLLUP (M.TITLE, S.SDATETIME)
                 ");

            $stmt = $conn -> prepare($sql);
            $stmt -> execute(array());
    
            while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
?> 
            <tr>
                <td><?= $row['MOVIE_TITLE'] ?></td> 
                <td><?= $row['SDATETIME'] ?></td>
                <td><?= $row['CNT_VIEWERS'] ?></td>
                
            </tr>
<?PHP   
            }
                
?>  
        </tbody>      
    </table>
        
    </div>
</body>
</html>