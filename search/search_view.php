<!--
    영화의 상세 정보를 보여주는 페이지이다.
-->

<?php
    include '../oracle_in.php';

    $movieId = $_GET['movieId'];

    try {
        $conn = new PDO($dsn, $username, $password);
    } catch (PDOException $e) {
        echo("에러 내용: ".$e -> getMessage());
    }

    $sql = (
        "SELECT *
         FROM MOVIE M, ACTOR A
         WHERE M.MID = :movieID
         AND M.MID = A.MID");

    $stmt = $conn -> prepare($sql);
    $stmt -> execute(array($movieId));

    $MovieName = '';
    $OpenDay = '';
    $Director = '';
    $Actor = '';
    $Rating = '';
    $Length = '';

    if ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
        $MovieName = $row['TITLE'];
        $OpenDay = $row['OPEN_DAY'];
        $Director = $row['DIRECTOR'];
        $Actor = $row['NAME'];
        $Rating = $row['RATING'];
        $Length = $row['LENGTH'];
    }

    $today_date = date("y/m/d");
?>
<!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8"> 
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <!-- Bootstrap CSS -->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">
            <style> a {text-decoration: none;} </style>

            <link rel="stylesheet" type="text/css" href="../css/search_view.css">

            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Concert+One&display=swap" rel="stylesheet">


            <title>MOVIE VIEW</title>
        </head>

        <body>

            <div class="container">
                <table class="table table-bordered text-center">
                <thead>
                    <div id="search_view_header_box">
                        <div id="search_view_header" class="centered" onclick="location.href='../main/user_main.php'">
                    	    CNU CINEMA THEATER
                        </div>
                    </div>
                    <div id=search_view_title>영화 정보</div>

                </thead>

                <tbody> 
                    <tr> <td>제목</td> <td><?= $MovieName ?></td> </tr>
                    <tr> <td>개봉일</td> <td><?= $OpenDay ?></td> </tr>
                    <tr> <td>감독</td> <td><?= $Director ?></td> </tr>
                    <tr> <td>출연</td> <td><?= $Actor ?></td> </tr>
                    <tr> <td>등급</td> <td><?= $Rating ?></td> </tr>
                    <tr> <td>러닝타임</td> <td><?= $Length ?></td> </tr>

<?php
                $sql = (
                    "SELECT (M.OPEN_DAY+10) as AA
                     FROM MOVIE M
                     WHERE M.MID = :movieId");

                $stmt = $conn -> prepare($sql);
                $stmt -> execute(array($movieId));
                
                $CLOSE_DATE = '';
                
                if ($row2 = $stmt -> fetch(PDO::FETCH_ASSOC)) {     //개봉일에 10을 더한 값을 가져온다.
                    $CLOSE_DATE = $row2['AA'];  
                }

                $today_date = date("y/m/d");

                $sql = (
                    "SELECT COUNT(M.MID) as CNT_R
                     FROM MOVIE M, MSCHEDULE S, TICKETING T
                     WHERE T.SID = S.SID
                     AND S.MID = M.MID
                     AND M.MID = :movieId
                     AND T.STATUS = 'R'
                     GROUP BY M.MID");

                $stmt = $conn -> prepare($sql);
                $stmt -> execute(array($movieId));
                
                $CNT_R = '';
                
                if ($row2 = $stmt -> fetch(PDO::FETCH_ASSOC)) {     //예매자 수를 가져온다.
                    $CNT_R = $row2['CNT_R'];  
                }

                //상영 종료 영화의 경우 book버튼이 없고 예매자수 누적 관객수가 출력된다.
                if($CLOSE_DATE <= $today_date){

                    $sql = (
                        "SELECT COUNT(M.MID) as CNT_W
                         FROM MOVIE M, MSCHEDULE S, TICKETING T
                         WHERE T.SID = S.SID
                         AND S.MID = M.MID
                         AND M.MID = :movieId
                         AND T.STATUS = 'W'
                         GROUP BY M.MID");
    
                    $stmt = $conn -> prepare($sql);
                    $stmt -> execute(array($movieId));
                    
                    $CNT_W = '';
                    
                    if ($row2 = $stmt -> fetch(PDO::FETCH_ASSOC)) {
                        $CNT_W = $row2['CNT_W'];  
                    }

?>
                        <tr> <td>예매자 수</td> <td><?= $CNT_R ?></td> </tr>
                        <tr> <td>누적 관객 수</td> <td><?= $CNT_W ?></td> </tr>
                    </tbody>
                    </table>
<?php
                }
                //상영 예정 영화의 경우 book버튼이 있고 예매자수가 출력된다.
                else if($OpenDay > $today_date){
?>
                        <tr> <td>예매자 수</td> <td><?= $CNT_R ?></td> </tr>
                    </tbody>
                    </table>
                    <button id=book_btn onclick="location.href='../book/book_schedule.php?movieId=<?= $row['MID'] ?>'">Book</button>

<?php
                }
                //상영 중인 영화의 경우 book버튼이 있고 예매자수와 누적 관객수가 출력된다.
                else{

                    $sql = (
                        "SELECT COUNT(M.MID) as CNT_W
                         FROM MOVIE M, MSCHEDULE S, TICKETING T
                         WHERE T.SID = S.SID
                         AND S.MID = M.MID
                         AND M.MID = :movieId
                         AND T.STATUS = 'W'
                         GROUP BY M.MID");
    
                    $stmt = $conn -> prepare($sql);
                    $stmt -> execute(array($movieId));
                    
                    $CNT_W = '';
                    
                    if ($row2 = $stmt -> fetch(PDO::FETCH_ASSOC)) {
                        $CNT_W = $row2['CNT_W'];  
                    }

?>
                        <tr> <td>예매자 수</td> <td><?= $CNT_R ?></td> </tr>
                        <tr> <td>누적 관객 수</td> <td><?= $CNT_W ?></td> </tr>
                    </tbody>
                    </table>
                    <button id=book_btn onclick="location.href='../book/book_schedule.php?movieId=<?= $row['MID'] ?>'">Book</button>

<?php
                }
?>
            </div>

            
        </body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-
gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</html>