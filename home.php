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

$sql1 = "SELECT content, image, created_at, updated_at, author_id FROM posts WHERE id = ?";
$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("i", $id);
$stmt1->execute();
$stmt1->bind_result($content, $image, $created_at, $updated_at, $author_id);
$stmt1->fetch();
$stmt1->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['post'])){
    $create_post = $_POST['create_post'];
    $file = $_FILES["image"]['tmp_name'];

    if(!empty($file)){
        $file = addslashes(file_get_contents($_FILES["image"]['tmp_name']));
        $sql_insert = "INSERT INTO posts (content, image, author_id) VALUES ('$create_post', '$file', '$acc_id')";
        if ($conn->query($sql_insert) == TRUE) {
            echo "<script>alert('Post created  successfully!')</script>";
            header('refresh:.01 url= home.php?id=?');
            exit(); 
        } else {
            echo "Error: " . $sql_insert . "<br>" . $conn->error;
        }
    }else {
        echo "<script>alert('Please select an image/video to post!')</script>";
        header('refresh:.01 url= home.php?id=?');
    } }
    if(isset($_POST['delete'])){
        $id = $_POST['id'];
        $sql_delete = "DELETE FROM posts WHERE id = ?";
        $stmt1 = $conn->prepare($sql_delete);
        $stmt1->bind_param("i", $id);
        $stmt1->execute();
        echo "<script>alert('Post deleted')</script>";
        header('refresh:.01 url= home.php?id=?');
    }
    if (isset($_POST['edit'])) {
        $id = $_POST['id'];
        $editcontent = $_POST['editcontent'];
    
        $sql_check_author = "SELECT author_id FROM posts WHERE id = ?";
        $stmt_check_author = $conn->prepare($sql_check_author);
        $stmt_check_author->bind_param("i", $id);
        $stmt_check_author->execute();
        $stmt_check_author->bind_result($author_id);
        $stmt_check_author->fetch();
        $stmt_check_author->close();
    
        // Update the post only if the currently logged-in user is the author
        if ($author_id == $acc_id) {
            $sql_update = "UPDATE posts SET content = ? WHERE id = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("si", $editcontent, $id);
            if ($stmt_update->execute()) {
                echo "<script>alert('Post Updated Successfully')</script>";
                header('refresh:.01 url= home.php?id=?');
                exit();
            }else{
                echo "Error updating post: " . $conn->error;
            }
        }else {
            echo "<script>alert('Cannot modify posts of other ')</script>";
            header('refresh:.01 url= home.php?id=?');
        }
    }
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width= , initial-scale=1.0" />
    <title>Social</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v2.1.6/css/unicons.css" />
    <link rel="stylesheet" href="./styles.css" />
    <link rel="stylesheet" href="profile.css">
    <link rel="stylesheet" href="./fontawesome-free-6.4.2-web/css/all.min.css" />
    <link rel="stylesheet" href="./bootstrap-5.3.1-dist/css/bootstrap.min.css">
</head>
<body>
    <nav>
        <div class="container">
            <div class="search-bar">
                <i class="uil uil-search"></i>
                <input type="search" placeholder="Search for creators, inspirations and projects" />
            </div>
            <div class="create">
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
            </div>
        </div>
    </nav>
    <main>
        <div class="container">
            <div class="left">
                <a class="profile">
                    <div class="profile-pic">
                        <img src="<?php echo $user_image ?>">
                    </div>
                    <div class="handle">
                        <h4 class="mt-2" style="margin-top: 13px;"><?php echo 'Hello' . ' ' . $name ?></h4>
                        <p class="text-muted"><?php echo  $email ?></p>
                    </div>
                </a>
                <div class="sidebar">
                    <a class="menu-item active">
                        <span><i class="uil uil-home"></i></span>
                        <h3>Home</h3>
                    </a>
                    <a class="menu-item " href="profile.php">
                        <span><i class="uil uil-compass"></i></span>
                        <h3>My Profile</h3>
                    </a>
                    <a class="menu-item " href="logout.php">
                        <span><i class="uil-left-arrow-to-left"></i></span>
                        <h3>Log out</h3>
                    </a>
                </div>
            </div>
            <div class="middle">
                <form class="create-post" action="<?php $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
                    <div class="profile-pic">
                        <img src="<?php echo $user_image ?>" alt="">
                    </div>
                    <textarea cols="65" placeholder="What's on your mind ?" id="create_post" name="create_post" style="margin-left: 11px; border-bottom: 1px solid #ccc; outline: none;"></textarea>
                    <div class="import-data d-flex">
                        <label style="margin-bottom: 0px;" for="uploadImage" class="custom-file-upload">
                            <span><i class="fa-solid fa-image"></i></span>
                            <input type="file" id="uploadImage" name="image" accept=".jpg,.jpeg,.png,.gif,.mp4"/>
                        </label>    
                    </div>
                    <input type="submit" value="Post" name="post" class="btn btn-primary" style="left: 0px; top: 0px; color: azure !important;background: #0d6efd; padding: 7px 11px;">
                </form>
                <div class="feeds" style="padding-top: 9px;">
                    <?php 
                        $sql_select = "SELECT posts.*, `user-account`.*
                                        FROM posts
                                        JOIN `user-account` ON posts.author_id = `user-account`.acc_id
                                        ORDER BY posts.created_at DESC";
                        $stmt = $conn->prepare($sql_select);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {                        
                        ?>
                    <div class="feed">
                        <div class="head">
                        </div>
                        <div class="user">
                            <div class="profile-pic">
                                <img src="<?php echo $row['user_image'] ?>" alt="">
                            </div>
                            <div class="info">
                                <h3 style="font-size: 20px;"><?php echo  $row['user_name'] ?></h3>
                                <small style="font-size: 12px;"><?php echo $row['city'] ?>, <?php echo $row['created_at'] ?></small>
                            </div>
                            <div class="dropdown" style="margin-left: 329px;">
                                <button onclick="myFunction(<?php echo $row['id']; ?>)" class="dropbtn">:</button>
                                <div id="myDropdown<?php echo $row['id']; ?>" class="dropdown-content">
                                <!-- Trigger/Open The Modal -->
                                <button id="myBtn" style="height: 38px; margin: auto; padding: 6px 69px 7px 69px; border:none;">Edit</button>
                                <!-- The Modal -->
                                <div id="myModal" class="modal">
                                <!-- Modal content -->
                                <div class="modal-content">
                                    <span class="close">&times;</span>
                                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                                    <textarea class="mb-5" cols="60" style="outline: none;" name="editcontent"><?php echo $row['content'] ?></textarea>
                                    <?php echo '<img src="data:image;base64,'. base64_encode($row['image']).'" alt="" style="width: 420px; height:500px; margin: auto;">'?>
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <input type="submit" name="edit" value="edit">
                                    </form>                                    
                                </div>
                                </div>
                                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        <input type="submit" name="delete" value="Delete">
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="caption mt-4">
                            <p><?php echo $row['content'] ?></p>
                        </div>
                        <div class="photo">
                            <?php echo '<img src="data:image;base64,'. base64_encode($row['image']).'" alt="" style="width: 420px; height:500px; margin: auto;">'?>
                        </div>
                    </div>
                    <hr/>
                    <?php }} ?>
                </div>
            </div>
        </div>
    </main>
    <script>
    function myFunction(postId) {
        var dropdown = document.getElementById("myDropdown" + postId);
        dropdown.classList.toggle("show");
    }

    window.onclick = function(event) {
        if (!event.target.matches('.dropbtn')) {
            var dropdowns = document.getElementsByClassName("dropdown-content");
            for (var i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                }
            }
        }
    }
</script>
<script>
    // Get the modal
    var modal = document.getElementById("myModal");
    // Get the button that opens the modal
    var btn = document.getElementById("myBtn");
    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];
    // When the user clicks the button, open the modal 
    btn.onclick = function() {
    modal.style.display = "block";
    }
    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
    modal.style.display = "none";
    }
    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
    }
</script>
<script src="index.js"></script>
</body>
</html>