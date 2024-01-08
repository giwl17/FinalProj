<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Page</title>
  <link rel="icon" type="image/x-icon" href="./img/rmuttlogo16x16.jpg">
  <link rel="stylesheet" href="css/main.css">
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="bootstrap/js/bootstrap.min.js">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>
  <?php
  session_start();
  include 'dbconnect.php';

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $hashed_password = hash('sha256', $password);

    $sql = "SELECT * FROM account WHERE email = :email AND password = :password";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
      $_SESSION['email'] = $email;
      $_SESSION['name'] = $row['name'];
      setcookie('email', $email, time() + (86400 * 30), "/");
      header("Location: dashboard.php");
      exit();
    } else {
      echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "Login Failed",
                        text: "Invalid email or password!"
                    }).then(function() {
                        window.location = "login.php";
                    });
                  </script>';
    }
  }
  ?>
  <?php require 'template/header_login.php'; ?>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="container mt-4">
    <h1 class="h1 text-center">เข้าสู่ระบบ</h1>
    <!-- Email: <input type="text" name="email"><br>
        Password: <input type="password" name="password"><br> -->
    <div class="form-group mb-3">
      <label for="email">Email</label>
      <input class="form-control" type="email" name="email" placeholder="Enter email" required>
    </div>
    <div class="form-group mb-3">
      <label for="pass">Password</label>
      <input class="form-control" type="password" name="password" placeholder="Password" required>
      <div class="form-text">
        หากลืมรหัสผ่าน <span><a href="/FinalProj/pass.php">คลิกที่นี่</a></span>
      </div>
    </div>
    <input type="submit" class="btn btn-primary container-fluid mb-3" value="เข้าสู่ระบบ" />
    <!-- <input type="submit" value="Login"> -->
  </form>
  <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>