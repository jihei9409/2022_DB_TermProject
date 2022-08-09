<!--예매를 진행하는 파일이다.-->
<?php
    include '../login/login_info.php';
    include '../oracle_in.php';

    $scheduleId = $_POST['scheduleId'];
    //$book_num = $_POST['book_num'];
    $user_id = $_SESSION['id'];

    try {
        $conn = new PDO($dsn, $username, $password);
    } catch (PDOException $e) {
        echo("에러 내용: ".$e -> getMessage());
    }

    if(empty($_POST['selectChk'])){     //선택된 좌석이 없을 경우 오류를 출력한다.
        echo
        ("
            <script>
                window.alert('예매할 좌석을 선택하세요.');
                history.go(-1)
            </script>
        ");
    }

    if(count($_POST['selectChk']) > 10){    //선택된 좌석이 11개 이상일 경우 오류를 출력한다,
        echo
        ("
            <script>
                window.alert('11개 이상의 좌석을 예매할 수 없습니다.');
                history.go(-1)
            </script>
        ");
    }
    else{       //선택된 좌석이 10개 이하일 경우
        for($i=0;$i<count($_POST['selectChk']); $i++){      //선택된 체크박스의 값을 가져와서 이를 Ticketing테이블에 추가한다.
            $checked_seat = $_POST['selectChk'];
    
            $sql = (
                "INSERT INTO TICKETING (RC_DATE, SEATS, STATUS, CID, SID)
                VALUES ((SELECT SYSDATE FROM DUAL), TO_NUMBER($checked_seat[$i]), 'R', $user_id, $scheduleId)");
    
            $stmt = $conn->prepare($sql);
    
            $stmt->execute(array());
    
        }
    
    
        echo"
            <script>
                window.alert('예매에 성공했습니다!');
                document.location.href='../main/user_main.php';
            </script>
        ";
        
    }

    
?>