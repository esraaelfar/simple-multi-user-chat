<?php
session_start();
if (!isset($_SESSION['email']) ||  !isset($_SESSION['password'])) {
    header('Location: index.php');
    exit();
}
include('db.php');

$email = $_SESSION['email'];
$sql = "SELECT acc_id, user_name, `password`, gender, country, age , possion , address , city , phone , about_me , user_image FROM `user-account` WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($acc_id, $name, $password, $gender, $country, $age, $possion, $address, $city, $phone, $about_me, $user_image);
$stmt->fetch();
$stmt->close();


if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = $_POST["name"];
    $country = $_POST["country"];
    $age = $_POST["age"];
    $possion = $_POST["possion"];
    $address = $_POST["address"];
    $city = $_POST["city"];
    $phone = $_POST["phone"];
    $about_me = $_POST["about_me"];

    $sql_update = "UPDATE `user-account` 
                SET user_name = '$name',
                country = '$country',
                age = '$age',
                possion= '$possion',
                address = '$address',
                city = '$city',
                phone= '$phone',
                about_me = '$about_me'
                WHERE email = ?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("s", $email);
    if ($stmt->execute()) {
        echo "<script>alert('Profile Updated Successfully')</script>";
        header('refresh:.01 url= profile.php?id=?');
        exit();
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link rel="stylesheet" href="profile.css">
    <link rel="stylesheet" href="./fontawesome-free-6.4.2-web/css/all.min.css" />
    <script rel="sylesheet" href="./bootstrap-5.3.1-dist/js/bootstrap.bundle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <link rel="sylesheet" href="./bootstrap-5.3.1-dist/css/bootstrap.min.css">
</head>

<body>
    <div class="main-content">
        <!-- Top navbar -->
        <nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
            <div class="container-fluid">
                <a class="nav-link pr-0" href="home.php"><i class="fa-solid fa-house" style="font-size: 31px; color: #d8cbd3;"></i></a>
                <!-- Form -->
                <form class="navbar-search navbar-search-dark form-inline mr-3 d-none d-md-flex ml-lg-auto">
                    <div class="form-group mb-0">
                        <div class="input-group input-group-alternative">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                            </div>
                            <input class="form-control" placeholder="Search" type="text">
                        </div>
                    </div>
                </form>
                <!-- User -->
                <ul class="navbar-nav align-items-center d-none d-md-flex">
                    <li class="nav-item dropdown">
                        <a class="nav-link pr-0" href="profile.php" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <div class="media align-items-center">
                                <span class="avatar avatar-sm rounded-circle">
                                    <img alt="Image placeholder" src="<?php echo $user_image ?>">
                                </span>
                                <div class="media-body ml-2 d-none d-lg-block">
                                    <span class="mb-0 text-sm  font-weight-bold"><?php echo $name ?></span>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                            <div class=" dropdown-header noti-title">
                                <h6 class="text-overflow m-0">Welcome!</h6>
                            </div>
                            <a href="../examples/profile.html" class="dropdown-item">
                                <i class="ni ni-single-02"></i>
                                <span>My profile</span>
                            </a>
                            <a href="../examples/profile.html" class="dropdown-item">
                                <i class="ni ni-settings-gear-65"></i>
                                <span>Settings</span>
                            </a>
                            <a href="../examples/profile.html" class="dropdown-item">
                                <i class="ni ni-calendar-grid-58"></i>
                                <span>Activity</span>
                            </a>
                            <a href="../examples/profile.html" class="dropdown-item">
                                <i class="ni ni-support-16"></i>
                                <span>Support</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#!" class="dropdown-item">
                                <i class="ni ni-user-run"></i>
                                <span>Logout</span>
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- Header -->
        <div class="header pb-8 pt-5 pt-lg-8 d-flex align-items-center" style="min-height: 600px; background-image: url(./images/Profile-Background.jpeg); background-size: cover; background-position: center top;">
            <!-- Mask -->
            <span class="mask bg-gradient-default opacity-8"></span>
        </div>
        <!-- Page content -->
        <div class="container-fluid mt--7">
            <div class="row">
                <div class="col-xl-4 order-xl-2 mb-5 mb-xl-0">
                    <div class="card card-profile shadow">
                        <div class="row justify-content-center">
                            <div class="col-lg-3 order-lg-2">
                                <div class="card-profile-image">
                                    <a href="#">
                                        <img src="<?php echo $user_image ?>" class="rounded-circle">
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0 md-4 mt-0">
                            <div class="row mt-0">
                                <div class="col mt-0">
                                    <div class="card-profile-stats d-flex justify-content-center pt-0 md-5" style="margin-top: 0px !important;">
                                        <div>
                                            <span class="heading">22</span>
                                            <span class="description">Followers</span>
                                        </div>
                                        <div>
                                            <span class="heading">10</span>
                                            <span class="description">Following</span>
                                        </div>
                                        <div>
                                            <span class="heading">89</span>
                                            <span class="description">Posts</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <h3>
                                    <?php echo $name ?><span class="font-weight-light"> <?php echo ' ,' . $age ?></span>
                                </h3>
                                <div class="h5 font-weight-300">
                                    <i class="ni location_pin mr-2"></i><?php echo $address  . ' ,' ?> <?php echo $city . ' ,' ?> <?php echo $country ?>
                                </div>
                                <div class="h5 mt-4">
                                    <i class="ni business_briefcase-24 mr-2"></i><?php echo $possion ?>
                                </div>
                                <hr class="my-4">
                                <p><?php echo $about_me ?></p>
                                <a href="logout.php"><button class="btn logout btn-danger">Log out</button></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8 order-xl-1">
                    <div class="card bg-secondary shadow">
                        <div class="card-header bg-white border-0">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h3 class="mb-0">My account</h3>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
                                <div class="col-4 text-right">
                                    <a href="#!"><button type="submit" class="btn btn-sm btn-primary">Edit Profile</button></a>
                                </div>
                                <h6 class="heading-small text-muted mb-4">User information</h6>
                                <div class="pl-lg-4">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group focused">
                                                <label class="form-control-label" for="input-username">Username</label>
                                                <input type="text" id="input-username" class="form-control form-control-alternative" placeholder="Username" value="<?php echo $name ?>" name="name">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-email">Email address</label>
                                                <input type="email" id="input-email" class="form-control form-control-alternative" placeholder="<?php echo $email; ?>" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group focused">
                                                <label class="form-control-label" for="age">Age</label>
                                                <input type="text" id="input-first-name" class="form-control form-control-alternative" placeholder="age" name="age" value="<?php echo $age ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group focused">
                                                <label class="form-control-label" for="possion">Your possion</label>
                                                <input type="text" id="possion" class="form-control form-control-alternative" placeholder="possion" name="possion" value="<?php echo $possion ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-4">
                                <!-- Address -->
                                <h6 class="heading-small text-muted mb-4">Contact information</h6>
                                <div class="pl-lg-4">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group focused">
                                                <label class="form-control-label" for="input-address">Address</label>
                                                <input id="input-address" class="form-control form-control-alternative" placeholder="Home Address" name="address" value="<?php echo $address ?>" type="text">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group focused">
                                                <label class="form-control-label" for="input-city">City</label>
                                                <input type="text" id="input-city" class="form-control form-control-alternative" placeholder="City" name="city" value="<?php echo $city ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group focused">
                                                <label class="form-control-label" for="input-country">Country</label>
                                                <input type="text" id="input-country" class="form-control form-control-alternative" placeholder="Country" name="country" value="<?php echo $country ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group focused">
                                                <label class="form-control-label" for="input-country">Phone</label>
                                                <input type="number" id="input-country" class="form-control form-control-alternative" placeholder="phone" name="phone" value="<?php echo $phone ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-4">
                                <!-- Description -->
                                <h6 class="heading-small text-muted mb-4">About me</h6>
                                <div class="pl-lg-4">
                                    <div class="form-group focused">
                                        <label>About Me</label>
                                        <textarea rows="4" class="form-control form-control-alternative" placeholder="A few words about you ..." name="about_me"><?php echo $about_me ?></textarea>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const dialog = document.querySelector("dialog");
        const cartButton = document.getElementById("add__to__cart");
        const dialogButton = document.getElementById("close__dialog");

        cartButton.addEventListener("click", () => {
            dialog.showModal();
        });

        dialogButton.addEventListener("click", () => {
            dialog.close();
        });
    </script>
</body>

</html>