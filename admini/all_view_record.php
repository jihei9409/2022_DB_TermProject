<!--모든 관람 기록을 보여준다. Ticketing테이블의 모든 정보를 출력한다.-->
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
				    관람기록 목록
			</div>
		    
            <tr>
                <th>티켓팅 ID</th>
                <th>예매/취소/관람 날짜</th>
                <th>좌석</th>
                <th>관람 상태</th>
                <th>고객 ID</th>
                <th>영화 스케줄 ID</th>
            </tr>
        </thead>
        

        <tbody>
        
<?php
            
            $sql = (
                "SELECT *
                 FROM TICKETING
                 ORDER BY ID");

            $stmt = $conn -> prepare($sql);
            $stmt -> execute(array());
    
            while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
?> 
            <tr>
                <td><?= $row['ID'] ?></td> 
                <td><?= $row['RC_DATE'] ?></td>
                <td><?= $row['SEATS'] ?></td>
                <td><?= $row['STATUS'] ?></td>
                <td><?= $row['CID'] ?></td>
                <td><?= $row['SID'] ?></td>
            </tr>
<?PHP   
            }
                
?>  

        </tbody>      
    </table>
        
    </div>
</body>
</html>