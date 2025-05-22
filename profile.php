<?php 
include 'connect.php'; 
session_start(); 

$userLoggedIn = isset($_SESSION['email']);
$errorMessage = '';
$successMessage = '';

if ($userLoggedIn) {
    $email = $_SESSION['email'];
    $result = $conn->query("SELECT username, twitter, facebook, instagram, youtube, profile_pic, password FROM users WHERE email='$email'");
    $user = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $conn->real_escape_string(trim($_POST['username']));
    $twitter = $conn->real_escape_string(trim($_POST['twitter']));
    $facebook = $conn->real_escape_string(trim($_POST['facebook']));
    $instagram = $conn->real_escape_string(trim($_POST['instagram']));
    $youtube = $conn->real_escape_string(trim($_POST['youtube']));
    
    $profilePicUpdated = false;
    $profileUpdated = false;

    if ($user['username'] !== $username || $user['twitter'] !== $twitter || $user['facebook'] !== $facebook || $user['instagram'] !== $instagram || $user['youtube'] !== $youtube) {
        $conn->query("UPDATE users SET username='$username', twitter='$twitter', facebook='$facebook', instagram='$instagram', youtube='$youtube' WHERE email='$email'");
        $profileUpdated = true;
        
        $_SESSION['username'] = $username;
        $user['username'] = $username;
        $user['twitter'] = $twitter;
        $user['facebook'] = $facebook;
        $user['instagram'] = $instagram;
        $user['youtube'] = $youtube;
    }

    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === 0) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = $_FILES['profile_pic']['type'];

        if (in_array($fileType, $allowedTypes)) {
            $uploadDir = 'uploads/profile_pics/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileName = uniqid() . '_' . basename($_FILES['profile_pic']['name']);
            $uploadFile = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $uploadFile)) {
                $profilePicPath = $uploadFile;
                $conn->query("UPDATE users SET profile_pic='$profilePicPath' WHERE email='$email'");
                $user['profile_pic'] = $profilePicPath;
                $profilePicUpdated = true;
            } else {
                $errorMessage = 'Failed to upload image.';
            }
        } else {
            $errorMessage = 'Only images (JPG, PNG, GIF) are allowed.';
        }
    }

    if ($profileUpdated || $profilePicUpdated) {
        $successMessage = 'Profile updated successfully!';
        header("Location: profile.php");
        exit();
    }

    if (!empty($_POST['current_password']) && !empty($_POST['new_password']) && !empty($_POST['new_password_confirm'])) {
        $currentPassword = trim($_POST['current_password']);
        $newPassword = trim($_POST['new_password']);
        $newPasswordConfirm = trim($_POST['new_password_confirm']);
    
        if ($newPassword !== $newPasswordConfirm) {
            $errorMessage = 'New passwords do not match!';
        } else {
            if (md5($currentPassword) === $user['password']) {
                $newPasswordHash = md5($newPassword);
    
                if ($conn->query("UPDATE users SET password='$newPasswordHash' WHERE email='$email'")) {
                    $successMessage = 'Password updated successfully!';
                } else {
                    $errorMessage = 'Failed to update password. Please try again.';
                }
            } else {
                $errorMessage = 'Current password is incorrect!';
            }
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RecipePlatform | Profile Settings</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/profile.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,0,0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<header>
    <nav class="navbar">
        <span class="hamburger-btn material-symbols-rounded">menu</span>
        <a href="#" class="logo">
        <img src="logo/logo-header.png" alt="logo">
        </a>
        <ul class="links">
            <span class="close-btn material-symbols-rounded">close</span>
            <li id="home-link"><a href="index.php">Home</a></li>
            <li id="about-link"><a href="about.php#about-us-container">About</a>
                <ul>
                    <li><a href="about.php#about-us-container">About Us</a></li>
                    <li><a href="about.php#recipe-container">Recipe</a></li>
                    <li><a href="about.php#grid">Best Foods</a></li>
                    <li><a href="about.php#objective-section">Objective</a></li>
                </ul>
            </li>
            <li id="recipe-link"><a href="recipe.php">Recipe</a></li>
            <li id="blog-link"><a href="blog.php">Blog</a></li>
            <li id="contact-link"><a href="contact.php">Contact</a></li>
        </ul>
        
            <?php if ($userLoggedIn): ?>
                <div class="user-menu">
                    <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="userMenuDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            My Account
                        </button>
                        <div class="dropdown-menu" aria-labelledby="userMenuDropdown">
                            <a class="dropdown-item" href="profile.php">Profile</a>
                            <a class="dropdown-item" href="logout.php">Sign Out</a>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <button class="login-btn">LOG IN</button>
            <?php endif; ?>
        </nav>
    </header>

    <div class="rp-container container light-style flex-grow-1 container-p-y">
        <h4 class="font-weight-bold py-3 mb-4">Profile Settings</h4>

        <?php if ($errorMessage): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($errorMessage); ?></div>
        <?php endif; ?>

        <?php if ($successMessage): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($successMessage); ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
    <div class="rp-card card overflow-hidden">
        <div class="row no-gutters row-bordered row-border-light">
            <div class="col-md-3 pt-0">
                <div class="list-group list-group-flush rp-list-group">
                    <a class="rp-list-group-item active" href="#account-general" data-toggle="list">General</a>
                    <a class="rp-list-group-item" href="#account-change-password" data-toggle="list">Change Password</a>
                    <a class="rp-list-group-item" href="#account-social-links" data-toggle="list">Social Links</a>
                </div>
            </div>
            <div class="col-md-9">
                <div class="tab-content">
                    <div class="tab-pane fade active show" id="account-general">
                        <div class="rp-card-body card-body media align-items-center">
                            <img src="<?php echo htmlspecialchars($user['profile_pic'] ?: 'https://bootdey.com/img/Content/avatar/avatar1.png'); ?>" alt="Avatar" class="d-block ui-w-80">
                            <div class="media-body ml-4">
                                <label class="rp-btn rp-btn-primary">
                                    Upload New Photo
                                    <input type="file" name="profile_pic" class="account-settings-fileinput" style="display: none;">
                                </label>
                            </div>
                        </div>
                        <hr>
                        <div class="rp-card-body card-body">
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control" value="<?php echo htmlspecialchars($_SESSION['email']); ?>" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="account-change-password">
                        <div class="rp-card-body card-body">
                            <div class="form-group">
                                <label>Current Password</label>
                                <input type="password" name="current_password" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>New Password</label>
                                <input type="password" name="new_password" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Confirm New Password</label>
                                <input type="password" name="new_password_confirm" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="account-social-links">
                        <div class="rp-card-body card-body pb-2">
                            <div class="form-group">
                                <label>Twitter</label>
                                <input type="text" name="twitter" class="form-control" value="<?php echo htmlspecialchars($user['twitter']); ?>" placeholder="https://twitter.com/username">
                            </div>
                            <div class="form-group">
                                <label>Facebook</label>
                                <input type="text" name="facebook" class="form-control" value="<?php echo htmlspecialchars($user['facebook']); ?>" placeholder="https://www.facebook.com/username">
                            </div>
                            <div class="form-group">
                                <label>Instagram</label>
                                <input type="text" name="instagram" class="form-control" value="<?php echo htmlspecialchars($user['instagram']); ?>" placeholder="https://www.instagram.com/username">
                            </div>
                            <div class="form-group">
                                <label>YouTube</label>
                                <input type="text" name="youtube" class="form-control" value="<?php echo htmlspecialchars($user['youtube']); ?>" placeholder="https://www.youtube.com/channel/username">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="text-right mt-3">
        <button type="submit" class="btn btn-primary">Save Changes</button>
        <button type="reset" class="btn btn-default">Cancel</button>
    </div>
</form>
    </div>

    <div id="footer-wrapper">
    <div id="footer">
    
        <div class="col one-fourth">
            <h4>Popular Sections</h4>
            <ul class="no-bullet">
                <li>
                    <span class="header"><a href="#">Recipe Categories</a></span>
                    Browse a variety of recipe categories tailored to all tastes.
                </li>
                <li>
                    <span class="header"><a href="#">How-To Guides</a></span>
                    Detailed guides to enhance your cooking experience.
                </li>
                <li>
                    <span class="header"><a href="#">Submit a Recipe</a></span>
                    Share your own recipes with the community and get featured.
                </li>
            </ul>
        </div>
        
        <div class="col one-fourth">
            <h4>Developer</h4>
            <div class="developer-list">
                <p>Rieyan Gomez</p>
                <p>Glenn Triunfo</p>
                <p>John Andrew Coronel</p>
                <p>Nadine De Guzman</p>
                <p>Hanz Joshua Ancuna</p>  
            </div>
        </div>

        <div class="col one-fourth no-margin-right">
            <h4>About Us</h4>
            <p>
                Our Recipe Platform is designed to inspire creativity in the kitchen. We provide a variety of tools and features to help you explore new recipes, submit your own, and connect with others passionate about cooking.
            </p>
            <p>Copyright © 2025 <a href="#">RecipePlatform</a> | Built to make cooking easy and fun.</p>
        </div>
        
        <div class="clear"></div>
    </div> 
</div>

</body>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
</html>