 
 <?php
    require 'vendor/autoload.php';
    require_once 'dbconnect.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    date_default_timezone_set("Asia/Bangkok");

    if (isset($_POST['submitBtn'])) {
        if (!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvMimes)) {
            $objCSV = fopen($_FILES['csvFile']['tmp_name'], "r");
        } else {
            header("Location: thesisadd");
        }
    } else {
        die("มีบางอย่างผิดพลาด");
    }




    //   $count = 1;

    //   while (($objArr = fgetcsv($objCSV, 1000, ",")) !== FALSE) {
    //     $prefix = $objArr[0];
    //     $name = $objArr[1];
    //     $lastname = $objArr[2];
    //     $email = $objArr[3];

    //     if (isset($objArr[4])) {
    //       $studentID = $objArr[4];
    //     } else {
    //       $studentID = null;
    //     }
    //     if ($count > 1) {
    //         $token = bin2hex(random_bytes(16));
    //         $token_hash = hash("sha256", $token);
    //         $expiry = date("Y-m-d H:i:s", time() + 60 * 180);
    //         try {
    //           $stmt = $conn->prepare("SELECT * FROM account WHERE email = :email");
    //           $stmt->bindParam(':email', $email);
    //           $stmt->execute();

    //           if ($stmt->rowCount() > 0) {
    //             // echo '<script>alert("Email already exists! ");</script>';
    //             // echo '<script>window.location.href = "' . $page . '.php";</script>';
    //           } else {
    //             $insert = $conn->prepare("INSERT INTO account (password,studentId,prefix,name,lastname,email,role,download_permissions,member_manage_permission,account_manage_permission,status,reset_token_hash,reset_token_expires_at)
    //             VALUES(:token,:studentID,:prefix,:name,:lastname,:email,:role,:download_permissions,:member_manage_permission,:account_manage_permission,:status,:reset_token_hash,:reset_token_expires_at)");
    //             $insert->bindParam("token", $token_hash, PDO::PARAM_STR);
    //             $insert->bindParam("prefix", $prefix, PDO::PARAM_STR);
    //             $insert->bindParam("name", $name, PDO::PARAM_STR);
    //             $insert->bindParam("lastname", $lastname, PDO::PARAM_STR);
    //             $insert->bindParam("email", $email, PDO::PARAM_STR);
    //             $insert->bindParam("role", $role);
    //             $insert->bindParam("download_permissions", $download_permissions);
    //             $insert->bindParam("member_manage_permission", $member_manage_permission);
    //             $insert->bindParam("account_manage_permission", $account_manage_permission);
    //             $insert->bindParam("status", $status);
    //             $insert->bindParam("reset_token_hash", $token_hash);
    //             $insert->bindParam("reset_token_expires_at", $expiry);
    //             $insert->bindParam("studentID", $studentID);




    //             $result = $insert->execute();
    //             // echo "insert database";
    //           }
    //         } catch (PDOException $e) {
    //           echo $e;
    //         }

    //         if ($result) {

    //           $create_link = "https://www.rmuttcpethesis.com/FinalProj/createAccount.php?token=$token";

    //           // Send email to the user containing the reset link using PHPMailer
    //           $mail = new PHPMailer(true);
    //           try {
    //             //Server settings
    //             $mail->isSMTP();
    //             $mail->Host = 'rmuttcpethesis.com'; // Your SMTP server
    //             $mail->SMTPAuth = true;
    //             $mail->Username = 'rmuttcp'; // SMTP username
    //             $mail->Password = 'xG5qK2sg43'; // SMTP password
    //             $mail->SMTPSecure = 'tls';
    //             $mail->Port = 25;

    //             //Recipients
    //             $mail->setFrom("rmuttcp@rmuttcpethesis.com", "Admin");
    //             $mail->addAddress($email);

    //             //Content
    //             $mail->isHTML(true);
    //             $mail->Subject = 'Create New Account';
    //             $mail->Body = "To create your account, <br>
    //             token : $token <br>
    //             click on the following link: <a href='$create_link'>to create your account</a>";

    //             $mail->send();
    //             // echo '<script>alert("send mail complete");</script>';
    //             // echo '<script>window.location.href = "' . $page . '.php";</script>';

    //             // echo 'Email sent successfully. Check your inbox for the reset link.';
    //           } catch (Exception $e) {
    //             // echo "Error sending email: {$mail->ErrorInfo}";
    //           }
    //         } else {
    //           // echo '<script>alert("mail cant be send ");</script>';

    //           // echo '<script>window.location.href = "' . $page . '.php";</script>';
    //           // echo 'Email not found in our records.';
    //         }
    //     }



    //     $count++;
    //   }
    //   fclose($objCSV);
    //   echo '<script>alert("สร้างผู้ใช้และส่งอีเมล์เสร็จสิ้น ");</script>';
    //   echo '<script>window.location.href = "' . $page . '.php";</script>';
    ?>
