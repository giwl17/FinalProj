<!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['csvFile']['tmp_name'])) {
        // Handling CSV file upload
        $role = $_POST["role"];
        $download_permissions = $_POST["download_permissions"];
        $member_manage_permission = $_POST["member_manage_permission"];
        $account_manage_permission = $_POST["account_manage_permission"];
        $thesis_manage_permission = $_POST["thesis_manage_permission"];
        $status = $_POST["status"];
        $page = $_POST["page"];

        $objCSV = fopen($_FILES['csvFile']['tmp_name'], "r");
        $objCSV1 = fopen($_FILES['csvFile']['tmp_name'], "r");

        date_default_timezone_set("Asia/Bangkok");
        $error_msg["prefix"] = [];
        $error_msg["name"] = [];
        $error_msg["lastname"] = [];
        $error_msg["email"] = [];
        require 'vendor/autoload.php';
        require_once 'dbconnect.php';

        $count = 0;
        $count_check = 0;

        // Check for duplicate users
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
            if ($count_check > 0) {
                try {
                    $check = $conn->prepare("SELECT * FROM account WHERE email = :email OR name = :name AND lastname = :lastname");
                    $check->bindParam("name", $name, PDO::PARAM_STR);
                    $check->bindParam("lastname", $lastname, PDO::PARAM_STR);
                    $check->bindParam("email", $email, PDO::PARAM_STR);
                    $check->execute();
                    $show = $check->fetchAll();

                    $check_pos = $conn->prepare("SELECT * FROM academic_positions WHERE positionName = :prefix OR positionShort = :prefix ");
                    $check_pos->bindParam("prefix", $prefix, PDO::PARAM_STR);
                    $check_pos->execute();
                    $show_pos = $check_pos->fetchAll();
                    if ($page == "teacher_add" && $check_pos->rowCount() == 0) {
                        array_push($error_msg["prefix"], $prefix);
                        array_push($error_msg["name"], $name);
                        array_push($error_msg["lastname"], $lastname);
                        array_push($error_msg["email"], $email);
                    } else if ($show) {
                        array_push($error_msg["prefix"], $prefix);
                        array_push($error_msg["name"], $name);
                        array_push($error_msg["lastname"], $lastname);
                        array_push($error_msg["email"], $email);
                    }
                } catch (PDOException $e) {
                    echo $e;
                }
            }
            $count_check++;
        }

        // If no errors, proceed with creating accounts and sending emails
        if (count($error_msg["name"]) == 0 && count($error_msg["lastname"]) == 0 && count($error_msg["email"]) == 0) {
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
                if ($count > 0) {
                    $token = bin2hex(random_bytes(16));
                    $token_hash = password_hash($token, PASSWORD_DEFAULT);
                    $expiry = date("Y-m-d H:i:s", time() + 60 * 10080);
                    try {
                        $stmt = $conn->prepare("SELECT * FROM account WHERE email = :email");
                        $stmt->bindParam(':email', $email);
                        $stmt->execute();

                        if ($stmt->rowCount() > 0) {
                            echo '<script>
                                Swal.fire({
                                title: "อีเมล์นี้ถูกใช้งานแล้ว!",
                                icon: "error"
                                });
                            </script>';
                        } else {
                            $insert = $conn->prepare("INSERT INTO account (password,studentId,prefix,name,lastname,email,role,download_permissions,member_manage_permission,account_manage_permission,thesis_manage_permission,status,reset_token_hash,reset_token_expires_at)
                                VALUES(:password,:studentID,:prefix,:name,:lastname,:email,:role,:download_permissions,:member_manage_permission,:account_manage_permission,:thesis_manage_permission,:status,:reset_token_hash,:reset_token_expires_at)");
                            $insert->bindParam("password", $token_hash, PDO::PARAM_STR);
                            $insert->bindParam("prefix", $prefix, PDO::PARAM_STR);
                            $insert->bindParam("name", $name, PDO::PARAM_STR);
                            $insert->bindParam("lastname", $lastname, PDO::PARAM_STR);
                            $insert->bindParam("email", $email, PDO::PARAM_STR);
                            $insert->bindParam("role", $role);
                            $insert->bindParam("download_permissions", $download_permissions);
                            $insert->bindParam("member_manage_permission", $member_manage_permission);
                            $insert->bindParam("account_manage_permission", $account_manage_permission);
                            $insert->bindParam("thesis_manage_permission", $thesis_manage_permission);
                            $insert->bindParam("status", $status);
                            $insert->bindParam("reset_token_hash", $token);
                            $insert->bindParam("reset_token_expires_at", $expiry);
                            $insert->bindParam("studentID", $studentID);
                            $result = $insert->execute();
                        }
                    } catch (PDOException $e) {
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
                            $mail->setFrom("rmuttcp@rmuttcpethesis.com", "rmuttcpethesis.com");
                            $mail->addAddress($email);

                            //Content
                            $mail->isHTML(true);
                            $mail->Subject = 'Create New Password';
                            /*  $mail->Body = "สร้างรหัสผ่านของคุณ, <br>
                            click on the following link: <a href='$create_link'>to create your account</a>"; */
                            $mail->Body = "คลิกที่ลิงค์เพื่อทำการสร้างรหัสผ่านของคุณ : <a href='" . $create_link . "'>สร้างรหัสผ่าน</a>";
                            $mail->send();
                        } catch (Exception $e) {
                        }
                    }
                }
                $count++;
            }
            fclose($objCSV);
            echo '<script>
                Swal.fire({
                title: "สร้างผู้ใช้และส่งอีเมล์เสร็จสิ้น",
                icon: "success"
                });
            </script>';
            $tab = '';
            $tabshow = '';
            $tabcsv = 'active';
            $tabcsvshow = 'show active';
        } else {
            echo '<script>
                Swal.fire({
                title: "มีผู้ใช้ซ้ำไม่สามารถสร้างผู้ใช้ได้",
                icon: "error"
                });
            </script>';

            $tab = '';
            $tabshow = '';
            $tabcsv = 'active';
            $tabcsvshow = 'show active';
        }
    } else {
        // Handling form submission for single user creation
        $prefix = $_POST["prefix"];
        $name = $_POST["name"];
        $lastname = $_POST["lastname"];
        $email = $_POST["email"];
        $role = $_POST["role"];
        $download_permissions = $_POST["download_permissions"];
        $member_manage_permission = $_POST["member_manage_permission"];
        $account_manage_permission = $_POST["account_manage_permission"];
        $thesis_manage_permission = $_POST["thesis_manage_permission"];
        $status = $_POST["status"];
        $page = $_POST["page"];

        if (isset($_POST["studentID"])) {
            $studentID = $_POST["studentID"];
        } else {
            $studentID = null;
        }

        date_default_timezone_set("Asia/Bangkok");
        $token = bin2hex(random_bytes(16));
        $token_hash = password_hash($token, PASSWORD_DEFAULT);
        $expiry = date("Y-m-d H:i:s", time() + 60 * 10080);



        require 'vendor/autoload.php';
        require_once 'dbconnect.php';

        try {
            $stmt = $conn->prepare("SELECT * FROM account WHERE email = :email OR name = :name AND lastname = :lastname");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                echo '<script>
                Swal.fire({
                title: "Email already exists!",
                icon: "error"
                });
            </script>';
            } else {
                $insert = $conn->prepare("INSERT INTO account (password,studentId,prefix,name,lastname,email,role,thesis_manage_permission,download_permissions,member_manage_permission,account_manage_permission,status,reset_token_hash,reset_token_expires_at)
                VALUES(:token,:studentID,:prefix,:name,:lastname,:email,:role,:thesis_manage_permission,:download_permissions,:member_manage_permission,:account_manage_permission,:status,:reset_token_hash,:reset_token_expires_at)");
                $insert->bindParam("token", $token_hash, PDO::PARAM_STR);
                $insert->bindParam("prefix", $prefix, PDO::PARAM_STR);
                $insert->bindParam("name", $name, PDO::PARAM_STR);
                $insert->bindParam("lastname", $lastname, PDO::PARAM_STR);
                $insert->bindParam("email", $email, PDO::PARAM_STR);
                $insert->bindParam("role", $role);
                $insert->bindParam("download_permissions", $download_permissions);
                $insert->bindParam("member_manage_permission", $member_manage_permission);
                $insert->bindParam("account_manage_permission", $account_manage_permission);
                $insert->bindParam("thesis_manage_permission", $thesis_manage_permission);
                $insert->bindParam("status", $status);
                $insert->bindParam("reset_token_hash", $token);
                $insert->bindParam("reset_token_expires_at", $expiry);
                $insert->bindParam("studentID", $studentID);

                $result = $insert->execute();
            }
        } catch (PDOException $e) {
            // echo $e;
            echo '<script>
                Swal.fire({
                title: "Database Error",
                icon: "error"
                });
            </script>';
        }

        if ($result) {
            // Construct the reset link with the token
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
                password : $token <br>
                click on the following link: <a href='$create_link'>to create your account</a>";

                $mail->send();
                echo '<script>
                    Swal.fire({
                    title: "สร้างผู้ใช้และส่งอีเมล์เสร็จสิ้น",
                    icon: "success"
                    });
                </script>';
                $tab = 'active';
                $tabshow = 'show active';
                $tabcsv = '';
                $tabcsvshow = '';
            } catch (Exception $e) {
                // echo "Error sending email: {$mail->ErrorInfo}";
                echo '<script>
                Swal.fire({
                title: "ส่งอีเมล์ไม่สำเร็จ",
                icon: "error"
                });
            </script>';
            }
        } else {
            echo '<script>
                Swal.fire({
                title: "ไม่สามารถส่งอีเมล์ได้",
                icon: "error"
                });
            </script>';

            $tab = 'active';
            $tabshow = 'show active';
            $tabcsv = '';
            $tabcsvshow = '';
        }
    }
}
?>