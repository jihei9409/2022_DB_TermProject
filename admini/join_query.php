<!--조인 질의의 결과를 보여준다.-->

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
				    조인 질의 결과
			</div>

            <div id = "query_title">
                질의 내용
            </div>
            <div id="query_info" class="centered">
            상영된 영화의 id(MID)가 ‘M001’인 영화를 예매한 관객의 이름, 성별, 관객의
            ‘M001’ 영화에 대한 관람 기록 상태(지난 관람, 취소, 예매)를 출력하시오. 
            (순서는 관객의 생년월일 순으로 출력한다.)
			</div>
		    
            <tr>
                <th>관객 이름</th>
                <th>성별</th>
                <th>관람 상태</th>
            </tr>
        </thead>
        

        <tbody>
        
<?php
            $sql = (
                "SELECT C.NAME, C.SEX, T.STATUS
                 FROM CUSTOMER C, TICKETING T, MSCHEDULE S
                 WHERE S.SID = T.SID
                 AND C.CID = T.CID
                 AND S.MID = 'M001'
                 ORDER BY C.BIRTH_DATE
                 ");

            $stmt = $conn -> prepare($sql);
            $stmt -> execute(array());
    
            while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
?> 
            <tr>
                <td><?= $row['NAME'] ?></td> 
                <td><?= $row['SEX'] ?></td>
                <td><?= $row['STATUS'] ?></td>
                
            </tr>
<?PHP   
            }
                
?>  
        </tbody>      
    </table>
        
    </div>
</body>
</html>