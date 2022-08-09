<!--
	로그인된 회원 번호를 세션으로 저장한다.
-->
<?php
  session_start();
  if( isset( $_SESSION['id'] ) ) {
    $login = TRUE;
  }
?>