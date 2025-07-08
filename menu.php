<?php
session_start(); // Start the session at the very beginning
if (!isset($_SESSION['user_id'])) { //
    // If user is not logged in, redirect to login page
    header("Location: login.php"); //
    exit(); //
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>เลือกวิธีใช้งาน | Bottle Bank+</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <

  <div class="container">
    <div class="logo">Bottle Bank<span>+</span></div>
    <h2 class="sub-title">เลือกวิธีใช้งาน</h2>

    <div class="menu">
        <button class="btn btn-tline" onclick="window.location.href='deposit.php'">ฝากขวด</button>
        <button class="btn btn-tline" onclick="window.location.href='withdraw.php'">ถอนขวด</button>
        <button class="btn btn-tline" onclick="window.location.href='history.php'">ประวัติ</button>
        <button class="btn btn-tline" onclick="window.location.href='logout.php'">ออกจากระบบ</button>
    </div>
  </div>

  <div class="wave-bg"></div>

</body>
</html>