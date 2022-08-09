<!--
    영화 예매를 위해 좌석을 선택한다.
-->

<?php
    include '../login/login_info.php';
    include '../oracle_in.php';

    $scheduleId = $_GET['scheduleId'];

    $user_id = $_SESSION['id'];

    try {
        $conn = new PDO($dsn, $username, $password);
    } catch (PDOException $e) {
        echo("에러 내용: ".$e -> getMessage());
    }

    //연령 등급에 맞지 않는 회원이 예매를 하려고 하면 안된다고 한다.
    $today_date = ("SELECT SYSDATE FROM DUAL");
    $sql = ( 
        "SELECT (EXTRACT(YEAR FROM ($today_date)) - EXTRACT(YEAR FROM BIRTH_DATE)) as OLD
         FROM CUSTOMER
         WHERE CID = $user_id");

    $stmt = $conn -> prepare($sql);
    $stmt -> execute(array());

    $user_old = '';

    if ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
        $user_old = $row['OLD'];
    }


    $sql = ( 
        "SELECT M.RATING
         FROM MSCHEDULE S, MOVIE M
         WHERE S.MID = M.MID
         AND S.SID = :scheduleId");

    $stmt = $conn -> prepare($sql);
    $stmt -> execute(array($scheduleId));

    $movie_rating = '';

    if ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
        $movie_rating = $row['RATING'];
    }

    //회원의 나이가 관람등급에 유효하지 않을 경우 오류를 출력하고 메인페이지로 돌아간다.
    if($movie_rating == '18' && $user_old < 18){ 
        echo"
        <script>
            window.alert('만 18세 미만의 회원은 예매가 불가합니다.');
            document.location.href='../main/user_main.php';
        </script>
        ";
    }
    else if($movie_rating == '15' && $user_old < 15){
        echo"
        <script>
            window.alert('만 15세 미만의 회원은 예매가 불가합니다.');
            document.location.href='../main/user_main.php';
        </script>
        ";
    }
    else if($movie_rating == '12' && $user_old < 12){
        echo"
        <script>
            window.alert('만 12세 미만의 회원은 예매가 불가합니다.');
            document.location.href='../main/user_main.php';
        </script>
        ";
    }
    //회원의 나이가 유효하다면 잔여 좌석을 보여준다.
    else{
    

    //해당 상영관의 전체 좌석 개수 가져오기
    $sql = (
        "SELECT T.SEATS, S.SID
         FROM THEATER T, MSCHEDULE S
         WHERE S.SID = $scheduleId
         AND T.TNAME = S.TNAME");

    $stmt = $conn -> prepare($sql);
    $stmt -> execute(array());

    $seats_num = '';

    if ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
        $seats_num = $row['SEATS'];
    }

    //해당 영화 스케줄의 예매된 좌석 개수를 찾아오기
    $sql = (
        "SELECT COUNT(T.SID) AS RESERV_CNT 
         FROM TICKETING T 
         WHERE T.SID = :scheduleId
         AND T.STATUS = 'R'
         GROUP BY T.SID");

    $stmt = $conn->prepare($sql);
    
    $stmt->execute(array($scheduleId));

    $row = $stmt -> fetch(PDO::FETCH_ASSOC);

    $reserved_num = 0;

    //만약 해당 스케줄의 영화를 예매한 사람이 없을 경우
    if(empty($row['RESERV_CNT'])){   
        $reserved_num = 0; 
    }
    //만약 해당 스케줄의 영화를 예매한 사람이 있을 경우
    else{
        $reserved_num = $row['RESERV_CNT'];
    }

    $remain_num = $seats_num - $reserved_num;


?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"> 
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" type="text/css" href="../css/book_movie_page.css">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Concert+One&display=swap" rel="stylesheet">

        <title>CNU CINEMA THEATER</title>
    </head>

    <body>

        <header>

		    <div id="book_movie_header_box">
			    <div id="book_movie_header" class="centered" onclick="location.href='../main/user_main.php'">
				    CNU CINEMA THEATER
			    </div>
		    </div>

        </header>    
        <section>

            <div>

                <box id="book_movie_box">

                    <div id="remain_seats">
                        <tr>남은 좌석의 수 : </tr>              <!--남은 좌석의 수를 보여준다-->
                        <tr><td><?= $remain_num ?></td></tr>
                        <br></br><tr>좌석을 선택하세요 </tr>
                    </div>

                    <form method="post" action="./book_result_page.php">

                        <div>
                        
<?php                   //이미 예매된 좌석을 제외하고 선택해야 하기 때문에 좌석의 상태가 R인 경우는 체크박스를 비활성화 한다.
                        //예매된 좌석을 가져온다.
                            $sql = (
                                "SELECT T.SEATS 
                                 FROM TICKETING T 
                                 WHERE T.SID = :scheduleId
                                 AND T.STATUS = 'R'
                                 ORDER BY T.SEATS");                          

                            $stmt = $conn->prepare($sql);                           

                            $stmt->execute(array($scheduleId));

                            $i = 1;
                            $row = $stmt -> fetch(PDO::FETCH_ASSOC);
                            while ($i <= $seats_num) {
                                if($i % 5 == 1){
?>                        
                                    <div>
                                        <box id = seats_box>
<?php                                    
                                }
                                if(empty($row['SEATS'])){   //예매된 좌석이 아니라면 리스트에 추가한다.
?>                        
                                    <input id = "seat_Chkbox" type="checkbox" name="selectChk[]" value=<?= $i ?>>
<?php   
                                    $i = $i+1;
                                    continue;
                                }

                                if($row['SEATS'] == $i){    //이미 예매된 좌석은 비활성화 한다.
?>                                   
                                    <input id = "seat_Chkbox" type="checkbox" name="selectChk[]" value=<?= $i ?> disabled="disabled">
<?php                                    
                                    $row = $stmt -> fetch(PDO::FETCH_ASSOC);
                                }
                                else{   //예매된 좌석이 아니라면 리스트에 추가한다.
?>                        
                                    <input id = "seat_Chkbox" type="checkbox" name="selectChk[]" value=<?= $i ?>>
<?php                                      
                                }
                                if($i % 5 == 0){
?>                        
                                        </box>
                                    </div>
<?php                                                    
                                }
                                $i = $i+1;
                            }
    }
?>

                        </div>

                        <input type="hidden" id="scheduleId" name="scheduleId" value=<?=$scheduleId?>>
                        <div>
                            <input id="submit_btn" type = "submit" value="Book">
                        </div>
                    </form>
                </box>

            </div>
        </section>
    </body>
</html>