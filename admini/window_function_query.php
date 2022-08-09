
<!--윈도우 함수 질의의 결과를 보여준다.-->
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
				    윈도우 함수 질의 결과
			</div>

            <div id = "query_title">
                질의 내용
            </div>
            <div id="query_info" class="centered">
            상영관 별로 예매 횟수가 많았던 영화의 순위를 출력하시오. 
            (예매 횟수에는 지난 관람 내역, 취소 내역, 현재 예매 내역이 포함된다.)
			</div>
		    
            <tr>
                <th>상영관</th>
                <th>영화 제목</th>
                <th>관객 수</th>
                <th>순위</th>
            </tr>
        </thead>
        

        <tbody>
        
<?php
            $sql = (
                "SELECT 상영관, 영화제목, 관객수,
                 RANK() OVER (PARTITION BY 상영관 ORDER BY 관객수 DESC) AS 순위
                 FROM(
                 SELECT S.TNAME AS 상영관, 
                     CASE GROUPING(M.TITLE)
                         WHEN 1 THEN '상영관의 총 관객 수'
                         ELSE M.TITLE END AS 영화제목,
                     COUNT(*) AS 관객수
                 FROM MOVIE M, TICKETING T, MSCHEDULE S
                 WHERE M.MID = S.MID
                 AND T.SID = S.SID
                 GROUP BY S.TNAME, M.TITLE
                )
                 ");

            $stmt = $conn -> prepare($sql);
            $stmt -> execute(array());
    
            while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
?> 
            <tr>
                <td><?= $row['상영관'] ?></td> 
                <td><?= $row['영화제목'] ?></td>
                <td><?= $row['관객수'] ?></td>
                <td><?= $row['순위'] ?></td>
                
            </tr>
<?PHP   
            }
                
?>  
        </tbody>      
    </table>
        
    </div>
</body>
</html>