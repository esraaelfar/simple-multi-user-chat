<?php 
include('db.php');
$sql = "SELECT * FROM apps_countries";
$result = $conn->query($sql);
$data = $result->fetch_all(MYSQLI_ASSOC);

$sql2 = "SELECT email FROM `user-account`";
$result2 = $conn->query($sql2);
$data2 = $result2->fetch_all(MYSQLI_ASSOC);

    $emailErr = $genderErr = $nameErr = $passwordErr = $countryErr ="";
    $email = $password = $confpass = $country = $name = $gender = "";
    $accErr = "";
    $fname = $lname = "";
    

    if($_SERVER['REQUEST_METHOD']== "POST"){
        $email = $_POST['email'];
        $password =  md5($_POST['password']);
        $confpass = md5($_POST['confpass']);
        $fname = $_POST["fname"];
        $lname = $_POST["lname"];
        $gender = $_POST["gender"];
        $country = $_POST["country"];

        $email = test_input($_POST["email"]);
        $password = test_input($_POST["password"]);
        $confpass = test_input($_POST["confpass"]);
        $fname = test_input($_POST["fname"]);
        $lname = test_input($_POST["lname"]);
        $name = $fname . " " . $lname;

        if (preg_match("/^[a-zA-Z ]*$/",$name)) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailErr = "Enter Valid email";
            }
            if(empty($gender)){
                $genderErr = "Gender is required";
            }
            if(empty($password)){
                $passwordErr = "Please Enter Your Password";
            }elseif(empty($confpass)){
                $passwordErr = "Please Enter Your Password";
            }elseif(strlen($password) != 8){
                $passwordErr = "password should be 8 numbers digit";
            }elseif($password == $confpass){

            }else{
                $passwordErr = "password is not match";
            }
            if(empty($country)){
                $countryErr = "Country is Required.";
            }
        }else{
            $nameErr = "Only letters and white space allowed";
        }
        if(empty($nameErr) && empty($emailErr) && empty($passwordErr) && empty($genderErr) && empty($countryErr)){
            if ($gender == 'male') {
                $user_image = "./images/boy-profile.jpg";
            } elseif ($gender == 'female') {
                $user_image = "./images/girl profilejpeg.jpeg";
            }
            foreach($data2  as $value){
                if($value['email'] == $email){
                    $accErr = "Account already exists!";
                }}if(empty($accErr)){
                    $sql_insert = "INSERT INTO `user-account` (user_name, email, `password`, gender, country, user_image) 
                        VALUES ('$name', '$email', '$password', '$gender', '$country', '$user_image')";

                    if ($conn->query($sql_insert) == TRUE) {
                        $_SESSION['email'] = $email;
                        $_SESSION['password'] = $password;
                        $_SESSION['name'] = $name;
                        $_SESSION['gender'] = $gender;
                        $_SESSION['country'] = $country;

                        header('Location: index.php');
                    exit(); 
                    } 
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
    <div class="form_wrapper">
        <div class="form_container">
            <div class="title_container">
                <h2 class="text-center">Sign UP</h2>
                <p class="text-center" style="color: red;"><?php echo $accErr ?></p>
            </div>
            <div class="row clearfix">
                <div class="">
                    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
                        <div class="row clearfix">
                            <div class="col_half">
                                <div class="input_field"> <span><i aria-hidden="true" class="fa fa-user"></i></span>
                                    <input type="text" name="fname" placeholder="First Name" required />
                                </div>
                            </div>
                            <div class="col_half">
                                <div class="input_field"> <span><i aria-hidden="true" class="fa fa-user"></i></span>
                                    <input type="text" name="lname" placeholder="Last Name" required />
                                    <p style="color: red;"><?php echo $nameErr ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="input_field"> <span><i aria-hidden="true" class="fa fa-envelope"></i></span>
                            <input type="email" name="email" placeholder="Email" required />
                            <p style="color: red;"><?php echo $emailErr ?></p>
                        </div>
                        <div class="input_field"> <span><i aria-hidden="true" class="fa fa-lock"></i></span>
                            <input type="password" name="password" placeholder="Password" required />
                            <p style="color: red;"><?php echo $passwordErr ?></p>
                        </div>
                        <div class="input_field"> <span><i aria-hidden="true" class="fa fa-lock"></i></span>
                            <input type="password" name="confpass" placeholder="Re-type Password" required />
                            <p style="color: red;"><?php echo $passwordErr ?></p>
                        </div>
                        <div class="input_field radio_option">
                            <input type="radio" name="gender" value="male" id="rd1" <?php  if(isset($gender) && $gender=="male") echo "checked"; ?>>
                            <label for="rd1">Male</label>
                            <input type="radio" name="gender" value="female" id="rd2" <?php  if(isset($gender) && $gender=="female") echo "checked"; ?>>
                            <label for="rd2">Female</label>
                            <p style="color: red;"><?php echo $genderErr ?></p>
                        </div>
                        <div class="input_field select_option">
                            <select name="country">
                                <option>Select a country</option>
                                <?php foreach($data as $row){?>
                                <option><?php echo $row['country_name'] ?></option>
                                <?php } ?>
                            </select>
                            <p style="color: red;"><?php echo $countryErr ?></p>
                            <div class="select_arrow"></div>
                        </div>
                        <div class="input_field checkbox_option">
                            <input type="checkbox" id="cb1">
                            <label for="cb1">I agree with terms and conditions</label>
                        </div>
                        <div class="input_field checkbox_option">
                            <input type="checkbox" id="cb2">
                            <label for="cb2">I want to receive the newsletter</label>
                        </div>
                        <input class="btn btn-primary button" type="submit" value="Register" />
                    </form>
                    <a href="index.php" style="margin-left: 86px;">Already have an account?</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>