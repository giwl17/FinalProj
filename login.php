<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="css/main.css">

    <style>
        
        button {
            padding: .8rem;
            border-style: none;
            background-color: #2991D6;
            border-radius: 40px;
            color: #fff;
            width: 100%;
            cursor: pointer;
        }

        input[type=text],
        input[type=password] {
            width: 100%;
            padding: .5rem 1rem;
        }

        label {
            display: block;
        }

        .input {
            width: 100%;
        }

        .container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background-color: #fff;
            width: 300px;
            padding: 1rem 2rem;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        }
    </style>
</head>

<body>
    <?php require_once "template/header_login.php"; ?>
    <div>
        <div class="container">
            <h1 style="margin-bottom: 1.5rem;">เข้าสู่ระบบ</h1>
            <div class="input">
                <label for="email">Email</label>
                <input type="text" name="email" id="">
            </div>
            <div class="input">
                <label for="pass">Password</label>
                <input type="password" name="pass" id="">
            </div>
            <div>
                หากลืมรหัสผ่าน <span><a href="#">คลิกที่นี่</a></span>
            </div>
            <button>เข้าสู่ระบบ</button>
        </div>
    </div>
</body>

</html>