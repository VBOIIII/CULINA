<?php
include 'connect.php';
include 'db.php';
session_start();

$userLoggedIn = isset($_SESSION['email']);
$userEmail = $userLoggedIn ? $_SESSION['email'] : 'Guest';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "recipe_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$blogId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($blogId <= 0) {
    die("Invalid blog ID.");
}

// Fetch the blog post details
$query = "SELECT blogs.*, categories.name AS category_name FROM blogs 
          JOIN categories ON blogs.category_id = categories.id 
          WHERE blogs.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $blogId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $blogPost = $result->fetch_assoc();
} else {
    die("Blog not found.");
}

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $userLoggedIn) {
    $commentText = isset($_POST['comment']) ? $_POST['comment'] : '';

    if (!empty($commentText)) {
        $query = "INSERT INTO comments_blog (blog_id, email, comment) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iss", $blogId, $userEmail, $commentText);
        $stmt->execute();
    }
}

// Fetch the comments for the blog post
$commentQuery = "SELECT comment, created_at, email FROM comments_blog WHERE blog_id = ? ORDER BY created_at DESC";
$commentStmt = $conn->prepare($commentQuery);
$commentStmt->bind_param("i", $blogId);
$commentStmt->execute();
$commentsResult = $commentStmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($blogPost['title']); ?> - Culina</title>
    <link href="css/main.css" rel="stylesheet" type="text/css" />
    <link href="css/blog.css" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,0,0">
    <script src="js/login.js" defer></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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

    
    
    <div class="blur-bg-overlay"></div>

<div class="form-popup" id="formPopup">
    <span class="close-btn material-symbols-rounded" onclick="closePopup()">close</span>

    <!-- Login Form -->
    <div class="form-box login" id="loginForm">
        <div class="form-details">
            <h2>Welcome Back</h2>
            <p>Log in using your personal information to stay connected with us.</p>
        </div>
        <div class="form-content">
            <h2>LOGIN</h2>
            <form id="loginFormAjax">
                <div class="input-field">
                    <input type="email" name="email" id="loginEmail" placeholder="Enter your email" required>
                </div>
                <div class="input-field">
                    <input type="password" name="password" id="loginPassword" placeholder="Enter your password" required>
                </div>
                <a href="#" class="forgot-pass-link">Forgot password?</a>
                <button type="submit" id="loginSubmit">Log In</button>
            </form>
            <div id="loginError" class="error-message" style="display:none;"></div>
            <div class="bottom-link">
                Don't have an account? <a href="javascript:void(0);" onclick="showSignupForm()">Sign Up</a>
            </div>
        </div>
    </div>

    <!-- Sign Up Form -->
    <div class="form-box signup" id="signupForm" style="display: none;">
        <div class="form-details">
            <h2>Create Account</h2>
            <p>Join our community by signing up with your personal information.</p>
        </div>
        <div class="form-content">
            <h2>SIGN UP</h2>
            <form id="signupFormAjax">
                <div class="input-field">
                    <input type="text" name="username" id="signupUsername" placeholder="Enter your username" required>
                </div>
                <div class="input-field">
                    <input type="email" name="email" id="signupEmail" placeholder="Enter your email" required>
                </div>
                <div class="input-field">
                    <input type="password" name="password" id="signupPassword" placeholder="Create a password" required>
                </div>
                <div class="policy-text">
                    <input type="checkbox" id="policy" required>
                    <label for="policy">
                        I agree to the <a href="#" class="option">Terms & Conditions</a>
                    </label>
                </div>
                <button type="submit" id="signupSubmit">Sign Up</button>
            </form>
            <div id="signupError" class="error-message" style="display:none;"></div>
            <div class="bottom-link">
                Already have an account? <a href="javascript:void(0);" onclick="showLoginForm()">Login</a>
            </div>
        </div>
    </div>
</div>

            <a href="blog.php" class="back-btn">
                <span class="material-icons"></span> Back
            </a>



          
        <section class="blog-details">
            <h1><?php echo htmlspecialchars($blogPost['title']); ?></h1>
            <p><strong>Category:</strong> <?php echo htmlspecialchars($blogPost['category_name']); ?></p>
            <p><strong>Published on:</strong> <?php echo date("F j, Y", strtotime($blogPost['created_at'])); ?></p>
            <img src="<?php echo htmlspecialchars($blogPost['image']); ?>" alt="<?php echo htmlspecialchars($blogPost['title']); ?>" class="blog-image">
            <div class="blog-content">
                <p><?php echo nl2br(htmlspecialchars($blogPost['content'])); ?></p>
            </div>
        </section>



    <section class="comments-section">
        <h2>Comments</h2>
        
        <!-- Display comments -->
        <?php while ($comment = $commentsResult->fetch_assoc()) { ?>
            <div class="comment">
                <p><strong><?php echo htmlspecialchars($comment['email']); ?></strong> 
                   <em>on <?php echo date("F j, Y, g:i a", strtotime($comment['created_at'])); ?></em></p>
                <p><?php echo nl2br(htmlspecialchars($comment['comment'])); ?></p>
            </div>
        <?php } ?>
        
        <!-- Comment form -->
        <?php if ($userLoggedIn) { ?>
            <form action="view_blogs.php?id=<?php echo $blogId; ?>" method="POST">
                <textarea name="comment" placeholder="Add your comment..." required></textarea>
                <button type="submit">Submit Comment</button>
            </form>
        <?php } else { ?>
            <p>You need to <a href="login.php">login</a> to leave a comment.</p>
        <?php } ?>
    </section>

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


    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
