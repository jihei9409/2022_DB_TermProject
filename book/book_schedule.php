<!--영화 예매를 위한 상영 스케줄을 보여준다.-->
<?php
    include '../oracle_in.php';

    $movieId = $_GET['movieId'];

    try {
        $conn = new PDO($dsn, $username, $password);
    } catch (PDOException $e) {
        echo("에러 내용: ".$e -> getMessage());
    }

    date_default_timezone_set('Asia/Seoul');

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

    <link rel="stylesheet" type="text/css" href="../css/book_schedule_page.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Concert+One&display=swap" rel="stylesheet">

    <title>CNU Cinema</title>
   
</head>
<body>
    <div class="container">
    <table class="table table-bordered text-center">
        <thead>
            <div id="schedule_header_box">
			    <div id="schedule_header" class="centered" onclick="location.href='../main/user_main.php'">
				    CNU CINEMA THEATER
			    </div>
		    </div>

			<div id="schedule_title" class="centered">
				    상영 스케줄
			</div>
		    
            <tr>
                <th>상영관</th>
                <th>상영 날짜</th>
                <th>상영 시간</th>
            </tr>
        </thead>
        
        <tbody>


<?php

            $sql = (
                "SELECT SID, TNAME, TO_CHAR(SDATETIME, 'YYYY-MM-DD') as YMD, TO_CHAR(SDATETIME, 'HH24:MI') as HM
                 FROM MSCHEDULE 
                 WHERE MID = :movieId
                 ORDER BY SDATETIME
                 ");

            $stmt = $conn -> prepare($sql);
            $stmt -> execute(array($movieId));
            
            while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {

?> 
                <tr>
                    <td><?= $row['TNAME'] ?></td> 
                    <td><?= $row['YMD'] ?></td>
<?php
                if($row['YMD'] < date("Y-m-d")){    //이미 상영이 종료된 일정은 선택할 수 없도록 한다.
?>
                    <td style="color:grey"><?= $row['HM'] ?></td>
<?php
                }
                else if($row['YMD'] == date("Y-m-d") && $row['HM'] < date("H:i")){ //이미 상영이 종료된 일정은 선택할 수 없도록 한다.
?>
                    <td style="color:grey"><?= $row['HM'] ?></td>
<?php     
                }
                else{   //상영 중인 일정은 링크를 통해 좌석 선택 페이지로 이동한다.
?>
                    <td><a href="book_movie.php?scheduleId=<?= $row['SID'] ?>"><?= $row['HM'] ?></a></td>

                </tr>
<?PHP
                }
            }

?>

        </tbody>
    </table>
    </div>
</body>
</html>