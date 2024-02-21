 
 <?php
  $role =  $_POST["role"];
  $download_permissions = $_POST["download_permissions"];
  $member_manage_permission = $_POST["member_manage_permission"];
  $account_manage_permission = $_POST["account_manage_permission"];
  $status = $_POST["status"];
  $page = $_POST["page"];
  $objCSV = fopen($_FILES['csvFile']['tmp_name'], "r");
  $objCSV1 = fopen($_FILES['csvFile']['tmp_name'], "r");
  date_default_timezone_set("Asia/Bangkok");

  $error_msg = [];
  require 'vendor/autoload.php';
  require_once 'dbconnect.php';

  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;


  $count = 1;
  //check csv can be add 
  while (($objArray = fgetcsv($objCSV1, 1000, ",")) !== FALSE) {
    $prefix = $objArray[0];
    $name = $objArray[1];
    $lastname = $objArray[2];
    $email = $objArray[3];
    if (isset($objArray[4])) {
      $studentID = $objArray[4];
    } else {
      $studentID = null;
    }
    try {
      $check = $conn->prepare("SELECT * FROM account WHERE email = :email OR name = :name AND lastname = :lastname");
      $check->bindParam("name", $name, PDO::PARAM_STR);
      $check->bindParam("lastname", $lastname, PDO::PARAM_STR);
      $check->bindParam("email", $email, PDO::PARAM_STR);
      $check->execute();
      $show = $check->fetchAll();
      foreach($show as $s){
        if($s){
        array_push($error_msg["name"],$s["name"]);
        array_push($error_msg["lastname"],$s["lastname"]);
        array_push($error_msg["email"],$s["email"]);
      }
    }

    } catch (PDOException $e) {
      echo $e;
    }
  }

  // get csv and add to dbsm\
  if(count($error_msg)==0){
  while (($objArr = fgetcsv($objCSV, 1000, ",")) !== FALSE) {
    $prefix = $objArr[0];
    $name = $objArr[1];
    $lastname = $objArr[2];
    $email = $objArr[3];

    if (isset($objArr[4])) {
      $studentID = $objArr[4];
    } else {
      $studentID = null;
    }
    if ($count > 1) {
      $token = bin2hex(random_bytes(16));
      $token_hash = password_hash($token, PASSWORD_DEFAULT);
      $expiry = date("Y-m-d H:i:s", time() + 60 * 180);
      try {
        $stmt = $conn->prepare("SELECT * FROM account WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
          echo '<script>alert("Email already exists! ");</script>';
          // echo '<script>window.location.href = "' . $page . '.php";</script>';
        } else {
          $insert = $conn->prepare("INSERT INTO account (password,studentId,prefix,name,lastname,email,role,download_permissions,member_manage_permission,account_manage_permission,status,reset_token_hash,reset_token_expires_at)
            VALUES(:password,:studentID,:prefix,:name,:lastname,:email,:role,:download_permissions,:member_manage_permission,:account_manage_permission,:status,:reset_token_hash,:reset_token_expires_at)");
          $insert->bindParam("password", $token_hash, PDO::PARAM_STR);
          $insert->bindParam("prefix", $prefix, PDO::PARAM_STR);
          $insert->bindParam("name", $name, PDO::PARAM_STR);
          $insert->bindParam("lastname", $lastname, PDO::PARAM_STR);
          $insert->bindParam("email", $email, PDO::PARAM_STR);
          $insert->bindParam("role", $role);
          $insert->bindParam("download_permissions", $download_permissions);
          $insert->bindParam("member_manage_permission", $member_manage_permission);
          $insert->bindParam("account_manage_permission", $account_manage_permission);
          $insert->bindParam("status", $status);
          $insert->bindParam("reset_token_hash", $token);
          $insert->bindParam("reset_token_expires_at", $expiry);
          $insert->bindParam("studentID", $studentID);
          $result = $insert->execute();
         
        }
      } catch (PDOException $e) {
        // echo $e;
        echo '<script>alert("มีข้อผิดพลาด");</script>';
      }

      if ($result) {

        $create_link = "https://www.rmuttcpethesis.com/FinalProj/createAccount.php?token=$token";

        // Send email to the user containing the reset link using PHPMailer
        $mail = new PHPMailer(true);
        try {
          //Server settings
          $mail->isSMTP();
          $mail->Host = 'rmuttcpethesis.com'; // Your SMTP server
          $mail->SMTPAuth = true;
          $mail->Username = 'rmuttcp'; // SMTP username
          $mail->Password = 'xG5qK2sg43'; // SMTP password
          $mail->SMTPSecure = 'tls';
          $mail->Port = 25;

          //Recipients
          $mail->setFrom("rmuttcp@rmuttcpethesis.com", "Admin");
          $mail->addAddress($email);

          //Content
          $mail->isHTML(true);
          $mail->Subject = 'Create New Account';
          $mail->Body = "To create your account, <br>
            token : $token <br>
            click on the following link: <a href='$create_link'>to create your account</a>";

          $mail->send();
          // echo '<script>window.location.href = "' . $page . '.php";</script>';

          // echo 'Email sent successfully. Check your inbox for the reset link.';
        } catch (Exception $e) {
          // echo "Error sending email: {$mail->ErrorInfo}";

        }
      } else {

        // echo '<script>window.location.href = "' . $page . '.php";</script>';
        // echo 'Email not found in our records.';
      }
    }



    $count++;
  }
  fclose($objCSV);
  echo '<script>alert("สร้างผู้ใช้และส่งอีเมล์เสร็จสิ้น ");</script>';
  echo '<script>window.location.href = "' . $page . '.php";</script>';
}else{
  echo '<script>alert("มีผู้ใช้ซ้ำไม่สามารถสร้างผู้ใช้ได้ ");</script>';
  echo '<script>window.location.href = "' . $page . '.php";</script>';
}
  ?>
