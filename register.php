<?php
session_start();
include 'php/db.php';

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    echo "<script>alert('การตรวจสอบความถูกต้องไม่สำเร็จ.'); window.history.back();</script>";
    exit();
  }

  $username = trim($_POST["username"]);
  $phone = trim($_POST["phone"]);
  $password = $_POST["password"];
  $confirm_password = $_POST["confirm_password"];

  // Validation
  if (empty($username) || empty($phone) || empty($password) || empty($confirm_password)) {
    echo "<script>alert('กรุณากรอกข้อมูลให้ครบทุกช่อง'); window.history.back();</script>";
    exit();
  }

  if (!preg_match('/^[0-9]{10}$/', $phone)) {
    echo "<script>alert('เบอร์โทรศัพท์ต้องเป็นตัวเลข 10 หลัก'); window.history.back();</script>";
    exit();
  }

  if ($password !== $confirm_password) {
    echo "<script>alert('รหัสผ่านไม่ตรงกัน'); window.history.back();</script>";
    exit();
  }

  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // ตรวจสอบซ้ำ
  $check_stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR phone = ?");
  $check_stmt->bind_param("ss", $username, $phone);
  $check_stmt->execute();
  $check_stmt->store_result();

  if ($check_stmt->num_rows > 0) {
    echo "<script>alert('ชื่อผู้ใช้หรือเบอร์โทรนี้ถูกใช้แล้ว'); window.history.back();</script>";
    $check_stmt->close();
    $conn->close();
    exit();
  }
  $check_stmt->close();

  // เพิ่มผู้ใช้ใหม่
  $stmt = $conn->prepare("INSERT INTO users (username, phone, password) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $username, $phone, $hashed_password);

  if ($stmt->execute()) {
    echo "<script>alert('สมัครสมาชิกสำเร็จ'); window.location.href='login.php';</script>";
  } else {
    echo "<script>alert('เกิดข้อผิดพลาดในการสมัครสมาชิก'); window.history.back();</script>";
  }

  $stmt->close();
  $conn->close();
  exit();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>สมัครสมาชิก | Bottle Bank+</title>
  <link rel="stylesheet" href="css/register.css">
</head>
<body>
  <div class="container">
    <div class="logo">Bottle Bank<span>+</span></div>
    <h2 class="sub-title">สมัครสมาชิก</h2>

    <form action="register.php" method="POST">
      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

      <input type="text" name="username" class="input" placeholder="ชื่อผู้ใช้" required><br>
      <input type="tel" name="phone" class="input" placeholder="เบอร์โทรศัพท์" pattern="[0-9]{10}" required><br>
      <input type="password" name="password" class="input" placeholder="รหัสผ่าน" required><br>
      <input type="password" name="confirm_password" class="input" placeholder="ยืนยันรหัสผ่าน" required><br>

      <button type="submit" class="btn btn-tline">สมัครสมาชิก</button>
    </form>

    <div class="menu">
      <button class="btn btn-tline" onclick="window.location.href='login.php'">เข้าสู่ระบบ</button>
      <button class="btn btn-tline" onclick="window.location.href='index.php'">กลับไปยังหน้าแรก</button>
    </div>
  </div>
</body>
</html>
