<?php 
include 'connect.php'; 
session_start(); 

// Check if the user is logged in
$userLoggedIn = isset($_SESSION['email']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Culina - Discover & Share Recipes</title>
    <meta name="keywords" content="recipes, cooking, culinary" />
    <meta name="description" content="Culina - Your ultimate destination for discovering and sharing recipes from around the world." />
    <link href="css/main.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,0,0">
    <script src="js/login.js" defer></script>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    
    <header>
        <nav class="navbar">
            <span class="hamburger-btn material-symbols-rounded">menu</span>
            <a href="#" class="logo">
                <img src="logo\logo-header.png" alt="logo">
            </a>
            <ul class="links">
                <span class="close-btn material-symbols-rounded">close</span>
                <li><a href="index.php" class="selected">Home</a></li>
                <li>
                    <a href="about.php">About</a>
                    <ul>
                        <li><a href="#about-us-container">About Us</a></li>
                        <li><a href="#recipe-container">Recipe</a></li>
                        <li><a href="#grid">Best Foods</a></li>
                        <li><a href="#templatemo_slider_wrapper">Goal</a></li>
                    </ul>
                </li>
                <li><a href="recipe.php">Recipe</a></li>
                <li><a href="blog.php">Blog</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>

           
            <?php if ($userLoggedIn): ?>
                <div class="user-menu">
                    <span>Welcome, <?php echo $_SESSION['email']; ?></span>
                    <label class="menu-icon" for="menu-toggle">
                        <span class="material-icons">See</span>
                    </label>
                    <input type="checkbox" id="menu-toggle" class="menu-toggle">
                    <ul class="user-options">
                        <li><a href="profile.php">Profile</a></li>
                        <li><a href="logout.php">Sign Out</a></li>
                    </ul>
                </div>
            <?php else: ?>
                <button class="login-btn" data-toggle="modal" data-target="#loginModal">LOG IN</button>
            <?php endif; ?>

        </nav>
    </header>

    <div class="blur-bg-overlay"></div>
    <div class="form-popup"></div>

    
    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner">
            <div class="carousel-item active" style="background-image: url('image/home-page1.jpg');">
                <div class="carousel-caption d-none d-md-block">
                    <h1>Welcome to Culina</h1>
                    <p>Your ultimate destination for discovering and sharing recipes from around the world.</p>
                    <div class="buttons">
                        <a href="recipe.html" class="btn btn-primary">Discover</a>
                        <a href="#signup" class="btn btn-secondary">Sign Up</a>
                    </div>
                </div>
            </div>
            <div class="carousel-item" style="background-image: url('image/home-page3.jpg');">
                <div class="carousel-caption d-none d-md-block">
                    <h1>Explore New Tastes</h1>
                    <p>Delve into a world of culinary delights and share your creations.</p>
                    <div class="buttons">
                        <a href="recipe.html" class="btn btn-primary">Discover</a>
                        <a href="#signup" class="btn btn-secondary">Sign Up</a>
                    </div>
                </div>
            </div>
            <div class="carousel-item" style="background-image: url('image/home-page2.jpg');">
                <div class="carousel-caption d-none d-md-block">
                    <h1>Join the Culinary Community</h1>
                    <p>Share, learn, and grow together in the kitchen.</p>
                    <div class="buttons">
                        <a href="about.html" class="btn btn-primary">Discover</a>
                        <a href="#signup" class="btn btn-secondary">Sign Up</a>
                    </div>
                </div>
            </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>

    <!-- Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
