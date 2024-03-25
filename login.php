<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="icon" type="image/x-icon" href="./img/rmuttlogo16x16.jpg">
  <link rel="stylesheet" href="css/main.css">
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="bootstrap/js/bootstrap.min.js">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>
  <?php
  session_start();
  if (isset($_SESSION['isLogin'])) {
    if ($_SESSION['isLogin']) {
      header("Location: /FinalProj");
    }
  }

  include 'dbconnect.php';

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM account WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
      if ($user['status'] == 1) {
        // User has status 1, proceed with login
        $_SESSION['email'] = $user['email'];
        $_SESSION['prefix'] = $user['prefix'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['lastname'] = $user['lastname'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['thesis_manage_permission'] = $user['thesis_manage_permission'];
        $_SESSION['download_permissions'] = $user['download_permissions'];
        $_SESSION['member_manage_permission'] = $user['member_manage_permission'];
        $_SESSION['account_manage_permission'] = $user['account_manage_permission'];
        $_SESSION['status'] = $user['status'];
        $_SESSION['isLogin'] = TRUE;
        setcookie('email', $email, time() + (86400 * 30), "/");
        header("Location: " . $_SESSION['current_page']);
        exit();
      } else {
        // User has status other than 1, show SweetAlert error
        echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Login ไม่สำเร็จ",
                    text: "บัญชีนี้ไม่ได้รับการอนุมัติหรือถูกระงับ!"
                }).then(function() {
                    window.location = "/FinalProj/login";
                });
            </script>';
      }
    } else {
      // Incorrect email or password, show SweetAlert error
      echo '<script>
            Swal.fire({
                icon: "error",
                title: "Login ไม่สำเร็จ",
                text: "email หรือ password ไม่ถูกต้อง!"
            }).then(function() {
                window.location = "/FinalProj/login";
            });
        </script>';
    }
  }
  ?>
  <?php require 'template/header_login.php'; ?>
  <form method="post">
    <!-- Email: <input type="text" name="email"><br>
        Password: <input type="password" name="password"><br> -->
    <div class="container mt-5">
      <h1 class="h1 text-center">เข้าสู่ระบบ</h1>
      <div class="row justify-content-center">
        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
              <div class="form-group mb-3">
                <label for="email">Email</label>
                <input class="form-control" type="email" name="email" placeholder="Enter email" required>
              </div>
              <div class="form-group mb-3">
                <label for="pass">Password</label>
                <input class="form-control" type="password" name="password" placeholder="Password" required>
                <div class="form-text">
                  หากลืมรหัสผ่าน <span><a href="/FinalProj/pass">คลิกที่นี่</a></span>
                </div>
              </div>
              <input type="submit" class="btn btn-primary container-fluid mb-3" value="เข้าสู่ระบบ" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
  <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>