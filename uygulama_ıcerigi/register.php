<?php
include 'db.php';
$hata = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if ($password !== $confirm) {
        $hata = "❌ Şifreler uyuşmuyor.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $fullname, $email, $hashedPassword);

        if ($stmt->execute()) {
            session_start();
            $_SESSION['user_id'] = $stmt->insert_id;
            $_SESSION['fullname'] = $fullname;

            header("Location: login.php?success=true");
            exit();
        } else {
            $hata = "❌ Kayıt sırasında hata oluştu: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tilko | Kayıt Ol</title>
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
  <div class="container">
    <div class="login-box">
      <h2>Kayıt Ol</h2>

      <?php if ($hata): ?>
        <div style="color: red; margin-bottom: 16px; font-weight: bold;"><?php echo $hata; ?></div>
      <?php endif; ?>

      <form action="" method="POST">
        <div class="input-box">
          <i class="fas fa-user"></i>
          <input type="text" name="fullname" placeholder="Ad Soyad" required />
        </div>
        <div class="input-box">
          <i class="fas fa-envelope"></i>
          <input type="email" name="email" placeholder="E-posta" required />
        </div>
        <div class="input-box">
          <i class="fas fa-lock"></i>
          <input type="password" name="password" placeholder="Şifre" required />
        </div>
        <div class="input-box">
          <i class="fas fa-lock"></i>
          <input type="password" name="confirm_password" placeholder="Şifreyi Onayla" required />
        </div>
        <button type="submit" class="login-btn">Kayıt Ol</button>
        <p class="register-link">Zaten bir hesabın var mı? <a href="login.php">Giriş Yap</a></p>
      </form>
    </div>
  </div>
</body>
</html>
