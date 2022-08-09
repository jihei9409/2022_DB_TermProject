<!--
   영화 취소를 진행하는 파일이다. 선택된 예매 내역의 STATUS를 C로 UPDATE한다.
-->

<?php
    include '../login/login_info.php';
    include '../oracle_in.php';


    try {
        $conn = new PDO($dsn, $username, $password);
    } catch (PDOException $e) {
        echo("에러 내용: ".$e -> getMessage());
    }

    if(empty($_POST['selectChk'])){
        echo
        ("
            <script>
                window.alert('취소할 예매 목록을 선택하세요.');
                history.go(-1)
            </script>
        ");
    }


    try{
        for($i=0;$i<count($_POST['selectChk']); $i++){      //선택된 예매 내역의 STATUS를 C로 업데이트 한다.
            $cancle_id = $_POST['selectChk'];

            echo($cancle_id[$i]);
    
            $sql = (
                "UPDATE TICKETING 
                    SET STATUS = 'C',
                        RC_DATE = (SELECT SYSDATE FROM DUAL) 
                    WHERE ID = $cancle_id[$i]");
    
            $stmt = $conn->prepare($sql);
    
            $stmt->execute(array());
    
        }
    
    
        echo"
            <script>
                window.alert('예매를 취소했습니다');
                document.location.href='./book_list.php';
            </script>
        ";
    }
    catch(Exception $e){
        echo("에러 내용: ".$e -> getMessage());
    }
    
    
?>