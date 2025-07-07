<?php
session_start();

include 'php/db.php';

// Generate CSRF token if not already set
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate CSRF token
    if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        echo "<script>alert('การตรวจสอบความถูกต้องไม่สำเร็จ. กรุณาลองใหม่อีกครั้ง.'); window.history.back();</script>";
        exit();
    }

    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Basic server-side validation
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        echo "<script>alert('กรุณากรอกข้อมูลให้ครบทุกช่อง'); window.history.back();</script>";
        exit();
    }

    if ($password !== $confirm_password) {
        echo "<script>alert('รหัสผ่านไม่ตรงกัน'); window.history.back();</script>";
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if username or email already exists
    $check_stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    if ($check_stmt === false) {
        error_log('Prepare failed: ' . htmlspecialchars($conn->error));
        echo "<script>alert('เกิดข้อผิดพลาดในการเตรียมคำสั่ง'); window.history.back();</script>";
        exit();
    }
    $check_stmt->bind_param("ss", $username, $email);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        echo "<script>alert('ชื่อผู้ใช้หรืออีเมลนี้มีอยู่แล้ว'); window.history.back();</script>";
        $check_stmt->close();
        $conn->close();
        exit();
    }
    $check_stmt->close();

    // Insert new user into the database
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    if ($stmt === false) {
        error_log('Prepare failed: ' . htmlspecialchars($conn->error));
        echo "<script>alert('เกิดข้อผิดพลาดในการเตรียมคำสั่ง'); window.history.back();</script>";
        exit();
    }
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    if ($stmt->execute()) {
        echo "<script>alert('สมัครสมาชิกสำเร็จ'); window.location.href='login.php';</script>";
    } else {
        error_log('Execute failed: ' . htmlspecialchars($stmt->error));
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
  <link rel="stylesheet" href="css/font.css">
</head>
<body>
  <div class="container">
    <div class="logo">Bottle Bank<span>+</span></div>
    <h2 class="sub-title">สมัครสมาชิก</h2>

    <form action="register.php" method="POST">
      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
      <input type="text" name="username" placeholder="ชื่อผู้ใช้" required><br>
      <input type="email" name="email" placeholder="อีเมล" required><br>
      <input type="password" name="password" placeholder="รหัสผ่าน" required><br>
      <input type="password" name="confirm_password" placeholder="ยืนยันรหัสผ่าน" required><br>
      <button type="submit" class="btn">สมัครสมาชิก</button>
    </form>

    <div class="menu">
      <button class="btn btn-tline" onclick="window.location.href='login.php'">เข้าสู่ระบบ</button>
    </div>
  </div>
</body>
</html>