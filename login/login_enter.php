<!--
	입력된 회원번호와 비밀번호가 올바른지 확인하여 로그인 기능을 수행한다.
-->
<?php
    include 'login_info.php';
    include '../oracle_in.php';

    $id_number = $_POST["id"];
    $pass = $_POST["pass"];

    try {
        $conn = new PDO($dsn, $username, $password);
    } catch (PDOException $e) {
        echo("에러 내용: ".$e -> getMessage());
    }

    $sql = "SELECT CID, PASSWORD FROM CUSTOMER WHERE CID = :id";    //입력된 회원번호가 CID와 같은 행의 회원번호, 비밀번호를 가져온다.

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id_number, PDO::PARAM_STR);
    $stmt->execute();

    $row = $stmt -> fetch(PDO::FETCH_ASSOC);

    if(empty($row['CID'])){     //입력된 회원 번호가 잘못되었을 경우
        
        echo
        ("
            <script>
                window.alert('존재하지 않는 계정입니다.');
                history.go(-1)
            </script>
        ");
        
    }
    else{
        if($id_number==$row['CID'] && $pass==$row['PASSWORD']){  //입력된 회원번호와 비밀번호가 DB에 존재할 경우

            $_SESSION['id'] = $id_number;

            if($id_number == 11){       //관리자 계정으로 로그인 되었을 경우
                echo
                ("
                    <script>
                        document.location.href = '../main/admini_main.php';
                    </script>
                ");
            }
            else{                       //사용자 계정으로 로그인 되었을 경우
                echo
                ("
                    <script>
                        document.location.href = '../main/user_main.php';
                    </script>
                ");
            }     
        }
        else{       //입력된 회원번호에 맞지 않는 비밀번호가 입력되었을 경우
            echo
            ("
                <script>
                    window.alert('잘못된 비밀번호입니다.');
                    history.go(-1)
                </script>
            ");

        }
    }
    
    

?>
