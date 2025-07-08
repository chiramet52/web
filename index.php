<?php
session_start(); // ถ้าในอนาคตจะใช้ session
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>Bottle Bank+</title>
  <link rel="stylesheet" href="css/style.css">
  <!-- ฟอนต์ไทย (Optional) -->
  <link href="https://fonts.googleapis.com/css2?family=Prompt&display=swap" rel="stylesheet">
</head>
<body>
  <div class="container">
    <div class="logo" id="logo">Bottle Bank<span>+</span></div>
    <button class="btn" onclick="window.location.href='login.php'">เริ่มต้นใช้งาน</button>
  </div>
  <div class="wave-bg"></div>

  <script src="script.js"></script>
</body>
</html>
