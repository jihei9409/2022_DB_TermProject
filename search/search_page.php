<!--
    영화 조회 결과를 보여주는 페이지이다.
-->

<?php
    include '../oracle_in.php';

    $search_title = $_GET['m_name'] ?? '';
    $search_date = $_GET['m_date'];

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

    <link rel="stylesheet" type="text/css" href="../css/search_page.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Concert+One&display=swap" rel="stylesheet">

    <title>CNU Cinema!!</title>
   
</head>
<body>
    <div class="container">

    <table class="table table-bordered text-center">
        <thead>
            <div id="search_header_box">
			    <div id="search_header" class="centered" onclick="location.href='../main/user_main.php'">
				    CNU CINEMA THEATER
			    </div>
		    </div>

            
			<div id="search_title" class="centered">
				    검색 결과
			</div>
		    

            <tr>
                <th>영화 제목</th>
                <th>개봉 일자</th>
            </tr>
        </thead>

        <tbody>
  
<?php 
    //영화 제목과 관람 예정일이 입력되지 않았을 경우
    if(empty($search_title) && empty($search_date)){
        echo
        ("
            <script>
                window.alert('입력된 정보가 없습니다.');
                history.go(-1)
            </script>
        ");
    }

    //영화 제목이 입력되지 않았을 경우
    else if(empty($search_title)){
        
        $sql = 
            "SELECT M.MID, M.TITLE, M.OPEN_DAY 
            FROM MOVIE M, MSCHEDULE S
            WHERE M.MID = S.MID
            AND EXTRACT(DAY FROM (TO_TIMESTAMP(:m_date))) = EXTRACT(DAY FROM S.SDATETIME)
            AND EXTRACT(MONTH FROM (TO_TIMESTAMP(:m_date))) = EXTRACT(MONTH FROM S.SDATETIME)
            AND EXTRACT(YEAR FROM (TO_TIMESTAMP(:m_date))) = EXTRACT(YEAR FROM S.SDATETIME)
            GROUP BY M.MID, M.TITLE, M.OPEN_DAY
            ORDER BY M.MID";

        $stmt = $conn->prepare($sql);   

        $stmt->execute(array($search_date)); 

        while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
    
?> 
            <tr>
                <td><a href="search_view.php?movieId=<?= $row['MID'] ?>"><?= $row['TITLE'] ?></a></td> 
                
                <td><?= $row['OPEN_DAY'] ?></td> 
                
            </tr>
        
<?PHP   
        }
    }

    //관람 예정일이 입력되지 않았을 경우
    else if(empty($search_date)){

        $sql = 
        "SELECT M.MID, M.TITLE, M.OPEN_DAY 
        FROM MOVIE M
        WHERE (LOWER(M.TITLE) LIKE '%' || LOWER(:m_name) || '%') 
        ORDER BY M.MID";

        $stmt = $conn->prepare($sql);   

        $stmt->execute(array($search_title)); 

        while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {

?>
            <tr>
                <td><a href="search_view.php?movieId=<?= $row['MID'] ?>"><?= $row['TITLE'] ?></a></td> 
        
                <td><?= $row['OPEN_DAY'] ?></td>  
            </tr>
<?PHP   
        }
    }
    //영화 제목과 관람 예정일이 입력되었을 경우
    else{
        
        $sql = 
        "SELECT M.MID, M.TITLE, M.OPEN_DAY 
        FROM MOVIE M, MSCHEDULE S
        WHERE M.MID = S.MID
        AND (LOWER(M.TITLE) LIKE '%' || LOWER(:m_name) || '%') 
        AND EXTRACT(DAY FROM (TO_TIMESTAMP(:m_date))) = EXTRACT(DAY FROM S.SDATETIME)
        AND EXTRACT(MONTH FROM (TO_TIMESTAMP(:m_date))) = EXTRACT(MONTH FROM S.SDATETIME)
        AND EXTRACT(YEAR FROM (TO_TIMESTAMP(:m_date))) = EXTRACT(YEAR FROM S.SDATETIME)
        GROUP BY M.MID, M.TITLE, M.OPEN_DAY
        ORDER BY M.MID";

        $stmt = $conn->prepare($sql);   

        $stmt->execute(array($search_title, $search_date)); 

        while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {

        ?>
            <tr>
                <td><a href="search_view.php?movieId=<?= $row['MID'] ?>"><?= $row['TITLE'] ?></a></td> 
                
                <td><?= $row['OPEN_DAY'] ?></td> 
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