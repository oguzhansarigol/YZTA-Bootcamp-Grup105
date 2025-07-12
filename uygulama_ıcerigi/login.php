<?php
session_start();
include 'db.php';

$hata = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $passwordInput = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, fullname, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $fullname, $hashedPassword);
        $stmt->fetch();

        if (password_verify($passwordInput, $hashedPassword)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['fullname'] = $fullname;

          
            header("Location: genel_bilgi_segmesi.html");
            exit();
        } else {
            $hata = "❌ Hatalı şifre.";
        }
    } else {
        $hata = "❌ Bu e-posta ile kayıtlı kullanıcı bulunamadı.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tilko | Giriş</title>
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>  

  <div class="container">
    <h1>Tilko'ya Hoşgeldiniz</h1>
    <div class="login-box">
      <h2>Giriş Yap</h2>

      <?php if (isset($_GET['success']) && $_GET['success'] === 'true'): ?>
        <div style="color: white; margin-bottom: 16px; font-weight: bold;">
            ✅ Kayıt başarılı! Giriş yapabilirsiniz.
        </div>
      <?php endif; ?>


      <?php if ($hata): ?>
        <div style="color: red; margin-bottom: 16px; font-weight: bold;"><?php echo $hata; ?></div>
      <?php endif; ?>

      <form action="" method="POST">
        <div class="input-box">
          <i class="fas fa-user"></i>
          <input type="email" name="email" placeholder="E-posta" required />
        </div>
        <div class="input-box">
          <i class="fas fa-lock"></i>
          <input type="password" name="password" placeholder="Şifre" required />
        </div>
        <div class="options">
          <label><input type="checkbox" /> Beni Hatırla</label>
          <a href="#">Şifremi Unuttum?</a>
        </div>
        <button type="submit" class="login-btn">Giriş Yap</button>
        <p class="register-link">Hesabınız yok mu? <a href="register.php">Kayıt Ol</a></p>
      </form>
    </div>
  </div>
</body>
</html>
