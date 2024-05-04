<?php 
include('db.php');

$sql2 = "SELECT email,`password` FROM `user-account`";
$result2 = $conn->query($sql2);
$data2 = $result2->fetch_all(MYSQLI_ASSOC);

session_start();

    $emailErr = $passwordErr ="";
    $email = $password = "";
    $accErr = "";
    
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $email = $_POST['email'];
        $password =  md5($_POST['password']);

        $email = test_input($_POST["email"]);
        $password = test_input($_POST["password"]);

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if(empty($password)){
                $passwordErr = "Please Enter Your Password";
            }
        }else{
            $emailErr = "Enter Valid email";
        }
        if(empty($emailErr) && empty($passwordErr)){
            $sql = "SELECT email, `password` FROM `user-account` WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->bind_result($_email, $_password);
            $stmt->fetch();
            $stmt->close();
            if ($_email == $email && $password == $_password) {
                $_SESSION['email'] = $email;
                $_SESSION['password'] = $password;
                header('Location: home.php');
                exit();
            } else {
                $accErr = "Invalid Email or Password!";
            }
        } 
    }
    function test_input($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social</title>
    <link rel="stylesheet" href="./register.css">
    <link rel="stylesheet" href="./bootstrap-5.3.1-dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="./fontawesome-free-6.4.2-web/css/all.min.css" />
</head>

<body>
    <div class="form_wrapper" style="margin: 13% auto 0;">
        <div class="form_container">
            <div class="title_container">
                <h2 class="text-center">Sign in</h2>
                <p class="text-center" style="color: red;"><?php echo $accErr ?></p>
            </div>
            <div class="row clearfix">
                <div class="">
                    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
                        <div class="input_field"> <span><i aria-hidden="true" class="fa fa-envelope"></i></span>
                            <input type="email" name="email" placeholder="Email" required />
                            <p style="color: red;"><?php echo $emailErr ?></p>
                        </div>
                        <div class="input_field"> <span><i aria-hidden="true" class="fa fa-lock"></i></span>
                            <input type="password" name="password" placeholder="Password" required />
                            <p style="color: red;"><?php echo $passwordErr ?></p>
                        </div>
                        <input class="btn btn-primary button" type="submit" value="Login in" />
                    </form>
                    <span>Don't have an account?<a href="register.php">register now</a></span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>