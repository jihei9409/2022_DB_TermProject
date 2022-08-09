<!--
   예매 내역 조회 페이지이다. 
-->
<?php
    include '../login/login_info.php';
    include '../oracle_in.php';

    $user_id = $_SESSION['id'];

    $start_date = $_GET['start_date'] ?? '';
    $end_date = $_GET['end_date'] ?? '';

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
			    <div id="mylist_header" class="centered" onclick="location.href='../main/user_main.php'">
				    CNU CINEMA THEATER
			    </div>
		    </div>

			<div id="mylist_title" class="centered">
				    예매 목록
			</div>
		    
            <tr>
                <th>예매일</th>
                <th>영화 제목</th>
                <th>영화 일정</th>
                <th>상영관</th>
                <th>좌석</th>
                <th>예매 취소</th>
            </tr>
        </thead>
        
        <form class="row">
            <div class="col-10" style="float: left; width: 40%;">
                <div id="info_text"> [ 조회 시작일을 선택하세요. ] </div>
                <label for="start_date" class="visually-hidden">start_date</label>
                <input type="date" class="form-control" id="start_date" name="start_date" placeholder="조회 시작일 입력" value="<?= $searchWord ?>">
            </div>
            <div class="col-10" style=" float: left; width: 40%;">
                <div id="info_text"> [ 조회 종료일을 선택하세요. ] </div>
                <label for="end_date" class="visually-hidden">end_date</label>
                <input type="date" class="form-control" id="end_date" name="end_date" placeholder="조회 시작일 입력" value="<?= $searchWord ?>">
            </div>
            <div class="col-auto text-end" style=" float: left; width: 10%;">
                <button type="submit" id="search_btn">Search</button>
            </div>   
        
        </form>

        <form method="post" action="./cancle_movie.php">
        <tbody>
        

<?php
            if(empty($start_date) && empty($end_date)){ //조회 시작일과 조회 종료일이 입력되지 않았을 경우는 모든 예매 내역을 보여준다.

                $sql = (
                    "SELECT T.ID, T.RC_DATE, TO_CHAR(S.SDATETIME, 'YYYY-MM-DD HH24:MI') as SCHE, M.TITLE, S.TNAME, T.SEATS
                     FROM TICKETING T, MSCHEDULE S, MOVIE M
                     WHERE T.SID = S.SID
                     AND S.MID = M.MID
                     AND T.CID = $user_id
                     AND T.STATUS = 'R'
                     ORDER BY T.RC_DATE DESC");

                    $stmt = $conn -> prepare($sql);
                    $stmt -> execute(array());
    
                while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
?> 
                    <tr>
                        <td><?= $row['RC_DATE'] ?></td> 
                        <td><?= $row['TITLE'] ?></td>
                        <td><?= $row['SCHE'] ?></td>
                        <td><?= $row['TNAME'] ?></td>
                        <td><?= $row['SEATS'] ?></td>
                        <td><input id = "cancle_Chkbox" type="checkbox" name="selectChk[]" value=<?= $row['ID']?>></td>

                    </tr>
<?PHP   
                }
            }
            
            else if(empty($start_date)){    //조회 시작일이 입력되지 않았을 경우
                echo
                ("
                    <script>
                        window.alert('조회 시작일을 선택하세요.');
                        history.go(-1)
                    </script>
                ");
            }
            else if(empty($end_date)){      //조회 종료일이 입력되지 않았을 경우
                echo
                ("
                    <script>
                        window.alert('조회 종료일을 선택하세요.');
                        history.go(-1)
                    </script>
                ");
            }

            else{       //조회 시작일과 종료일이 입력되었을 경우

                $sql = (
                    "SELECT T.ID, T.RC_DATE, TO_CHAR(S.SDATETIME, 'YYYY-MM-DD HH24:MI') as SCHE, M.TITLE, S.TNAME, T.SEATS
                     FROM TICKETING T, MSCHEDULE S, MOVIE M
                     WHERE T.SID = S.SID
                     AND S.MID = M.MID
                     AND T.CID = $user_id
                     AND T.STATUS = 'R'
                     AND T.RC_DATE >= :start_date
                     AND ((EXTRACT(DAY FROM (TO_TIMESTAMP(T.RC_DATE))) <= EXTRACT(DAY FROM TO_TIMESTAMP(:end_date))
                     AND EXTRACT(MONTH FROM (TO_TIMESTAMP(T.RC_DATE))) = EXTRACT(MONTH FROM TO_TIMESTAMP(:end_date))
                     AND EXTRACT(YEAR FROM (TO_TIMESTAMP(T.RC_DATE))) <= EXTRACT(YEAR FROM TO_TIMESTAMP(:end_date)))
                     OR (EXTRACT(DAY FROM (TO_TIMESTAMP(T.RC_DATE))) > EXTRACT(DAY FROM TO_TIMESTAMP(:end_date))
                     AND EXTRACT(MONTH FROM (TO_TIMESTAMP(T.RC_DATE))) < EXTRACT(MONTH FROM TO_TIMESTAMP(:end_date))
                     AND EXTRACT(YEAR FROM (TO_TIMESTAMP(T.RC_DATE))) <= EXTRACT(YEAR FROM TO_TIMESTAMP(:end_date)))
                     OR (EXTRACT(DAY FROM (TO_TIMESTAMP(T.RC_DATE))) <= EXTRACT(DAY FROM TO_TIMESTAMP(:end_date))
                     AND EXTRACT(MONTH FROM (TO_TIMESTAMP(T.RC_DATE))) < EXTRACT(MONTH FROM TO_TIMESTAMP(:end_date))
                     AND EXTRACT(YEAR FROM (TO_TIMESTAMP(T.RC_DATE))) <= EXTRACT(YEAR FROM TO_TIMESTAMP(:end_date))))
                     ORDER BY T.RC_DATE DESC");

                    $stmt = $conn -> prepare($sql);
                    $stmt -> execute(array($start_date, $end_date));
                    
                while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
?> 
                    <tr>
                        <td><?= $row['RC_DATE'] ?></td> 
                        <td><?= $row['TITLE'] ?></td>
                        <td><?= $row['SCHE'] ?></td>
                        <td><?= $row['TNAME'] ?></td>
                        <td><?= $row['SEATS'] ?></td>
                        <td><input id = "cancle_Chkbox" type="checkbox" name="selectChk[]" value=<?= $row['ID']?>></td>
                    </tr>
<?PHP   
                }
            }
?>  

        </tbody>      
    </table>
        <div id = btn_div>
            <input id="cancle_btn" type = "submit" value="예매 취소">
        </div>
    </form>
    </div>
</body>
</html>