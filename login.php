<?php
session_start();
include 'php/db.php';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $error = 'การตรวจสอบความถูกต้องไม่สำเร็จ. กรุณาลองใหม่อีกครั้ง.';
    } else {
        $username = trim($_POST['username']);
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
        if ($stmt === false) {
            error_log('Prepare failed: ' . htmlspecialchars($conn->error));
            $error = 'เกิดข้อผิดพลาดในการเตรียมคำสั่ง';
        } else {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows === 1) {
                $stmt->bind_result($id, $user, $hashed_password);
                $stmt->fetch();

                if (password_verify($password, $hashed_password)) {
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $id;
                    $_SESSION['username'] = $user;

                    echo "<script>alert('เข้าสู่ระบบสำเร็จ'); window.location.href = 'menu.php';</script>";
                    exit();
                } else {
                    $error = "รหัสผ่านไม่ถูกต้อง";
                }
            } else {
                $error = "ไม่พบบัญชีผู้ใช้นี้";
            }
            $stmt->close();
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>เข้าสู่ระบบ | Bottle Bank+</title>
  <link rel="stylesheet" href="css/login.css">
</head>
<body>
  <div class="container">
    <div class="logo">Bottle Bank<span>+</span></div>
    <h2 class="sub-title">เข้าสู่ระบบ</h2>

    <form action="login.php" method="POST" class="register-form">
      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
      <input type="text" name="username" placeholder="ชื่อผู้ใช้" required>
      <input type="password" name="password" placeholder="รหัสผ่าน" required>

      <div class="forgot-password">
        <a href="forgot_password.php">ลืมรหัสผ่าน?</a>
      </div>

      <!-- ✅ ปุ่มอยู่ใน form และเป็น type="submit" -->
      <button type="submit" class="btn btn-tline">เข้าสู่ระบบ</button>
    </form>

    <div class="menu">
      <button type="button" class="btn btn-tline" onclick="window.location.href='register.php'">สมัครสมาชิก</button><br>
      <button type="button" class="btn btn-tline" onclick="window.location.href='index.php'">กลับไปยังหน้าแรก</button>
    </div>

    <?php if (!empty($error)) : ?>
      <script>
        alert('เกิดข้อผิดพลาด: <?php echo htmlspecialchars($error); ?>');
      </script>
    <?php endif; ?>
  </div>

  <div class="wave-bg"></div>
</body>
</html>
