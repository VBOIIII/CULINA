<?php 
include 'connect.php'; 
session_start(); 

// Check if the user is logged in
$userLoggedIn = isset($_SESSION['email']);
?>


<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Bookstore, Free Template, templatemo</title>
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <link href="css/main.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,0,0">
    <script src="js/login.js" defer></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/JavaScript" src="js/slideshow.js"></script>



    <link rel="stylesheet" type="text/css" media="all" href="css/jquery.dualSlider.0.2.css" />

    <script src="js/jquery.easing.1.3.js" type="text/javascript"></script>
    <script src="js/jquery.timers-1.2.js" type="text/javascript"></script>
    <script src="js/activepage.js" defer></script>  
    <script src="js/jquery.dualSlider.0.3.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/jquery-1-4-2.min.js"></script> 
    <link rel="stylesheet" href="css/slimbox2.css" type="text/css" media="screen" /> 
    <script type="text/JavaScript" src="js/slimbox2.js"></script> 


<script type="text/javascript">
    
    $(document).ready(function() {
        
        $("#carousel").dualSlider({
            auto:true,
            autoDelay: 6000,
            easingCarousel: "swing",
            easingDetails: "easeOutBack",
            durationCarousel: 1000,
            durationDetails: 600
        });
        
    }); 
    
</script>


 

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

    
    <div id="about_page">
        <section id="about-bg" style="background: url('image/about-bg.jpg') no-repeat center center; background-size: cover; width: 100%; height: 60vh; display: flex; justify-content: center; align-items: center; text-align: center;">
            <h1>DISCOVER THE RECIPES</h1>
        </section>
    </div>
    
    <!-- About Us Section -->
    <div class="about-us-container">
        <h2>About <span>Us</span></h2>
        <div class="about-us-content">
            <div class="about-us-image">
                <img src="image/about-pic.png" alt="About Us" />
            </div>
            <div class="about-us-text">
                <p>Welcome to our Recipe Platform! We are a passionate team of food lovers dedicated to bringing you the most delicious and diverse recipes from around the world. Our mission is to inspire creativity in the kitchen and make cooking accessible to everyone, regardless of skill level. Whether you're a seasoned chef or a beginner, we have recipes that cater to every taste, dietary preference, and occasion.</p>
                <p>At Recipe Platform, we believe that food is not just about nutrition, but also about joy, culture, and connection. Our goal is to create a space where you can explore, learn, and enjoy new recipes every day. Join us on our culinary journey!</p>
            </div>
        </div>
    </div>
    
   
    
    <div id="recipe-container">
        <div id="recipe-section-title">
            <h2>Explore <span>Recipe</span></h2>
        </div>
    
       
        <div class="recipe">
            <img src="image/grilled-chicken-salad.jpg" alt="Recipe 1" />
            <div class="recipe-info">
                <h3>Grilled Chicken Salad</h3>
                <p><strong>Time to Cook:</strong> 20 minutes</p>
                <p><strong>Ingredients:</strong> Chicken, Lettuce, Tomatoes, Cucumber, Dressing</p>
                <p><strong>Instructions:</strong> Grill the chicken, chop the vegetables, toss together with the dressing, and serve.</p>
            </div>
        </div>
  
        <div class="recipe">
            <img src="image/Spaghetti-Carbonara.jpg" alt="Recipe 2" />
            <div class="recipe-info">
                <h3>Spaghetti Carbonara</h3>
                <p><strong>Time to Cook:</strong> 30 minutes</p>
                <p><strong>Ingredients:</strong> Spaghetti, Pancetta, Parmesan, Eggs, Black Pepper</p>
                <p><strong>Instructions:</strong> Cook the spaghetti, fry pancetta, mix with eggs and parmesan, then combine with pasta.</p>
            </div>
        </div>
    
 
        <div class="recipe">
            <img src="image/Vegan-Buddha-Bowl.jpg" alt="Recipe 3" />
            <div class="recipe-info">
                <h3>Vegan Buddha Bowl</h3>
                <p><strong>Time to Cook:</strong> 25 minutes</p>
                <p><strong>Ingredients:</strong> Quinoa, Chickpeas, Avocado, Kale, Dressing</p>
                <p><strong>Instructions:</strong> Cook the quinoa, roast the chickpeas, and assemble with avocado and kale. Top with dressing.</p>
            </div>
        </div>
    
        <a href="recipe.php" title="view more" class="view-more-btn">View More</a>
    </div>
 

    <div id="grid">

            <div class="grid-title">
                <h2>BEST <span>FOODS</span></h2>
            </div>
            <ul id="hexGrid">
                <li class="hex">
                    <div class="hexIn">
                        <a class="hexLink" href="#">
                            <div class="img" style="background-image: url('image/Freshly-baked-bread.jpg');"></div>
                        </a>
                    </div>
                </li>
                <li class="hex">
                    <div class="hexIn">
                        <a class="hexLink" href="#">
                            <div class="img" style="background-image: url('image/Cup-of-coffee.jpg');"></div>
                        </a>
                    </div>
                </li>
                <li class="hex">
                    <div class="hexIn">
                        <a class="hexLink" href="#">
                            <div class="img" style="background-image: url('image/Chocolatecake.jpg');"></div>
                        </a>
                    </div>
                </li>
                <li class="hex">
                    <div class="hexIn">
                        <a class="hexLink" href="#">
                            <div class="img" style="background-image: url('image/Italian-pasta.jpg');"></div>
                        </a>
                    </div>
                </li>
                <li class="hex">
                    <div class="hexIn">
                        <a class="hexLink" href="#">
                            <div class="img" style="background-image: url('image/Fruit-salad.jpg');"></div>
                        </a>
                    </div>
                </li>
                <li class="hex">
                    <div class="hexIn">
                        <a class="hexLink" href="#">
                            <div class="img" style="background-image: url('image/Sushi.jpg');"></div>
                        </a>
                    </div>
                </li>
                <li class="hex">
                    <div class="hexIn">
                        <a class="hexLink" href="#">
                            <div class="img" style="background-image: url('image/Avocado-toast.jpg');"></div>
                        </a>
                    </div>
                </li>
                <li class="hex">
                    <div class="hexIn">
                        <a class="hexLink" href="#">
                            <div class="img" style="background-image: url('image/Tacos.jpg');"></div>
                        </a>
                    </div>
                </li>
                <li class="hex">
                    <div class="hexIn">
                        <a class="hexLink" href="#">
                            <div class="img" style="background-image: url('image/Barbecue-ribs.jpg');"></div>
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </div>



    <div id="objective-section">
    <div class="objective-text-container">
        <h3><span>Our</span> Objective</h3>
        <p>Our Recipe Platform aims to inspire creativity in the kitchen by providing a wide variety of delicious, easy-to-follow recipes. We believe cooking should be an enjoyable and accessible experience for everyone. Whether you're a seasoned chef or a beginner, our platform offers recipes to suit all tastes, dietary preferences, and occasions. Join us as we make cooking a fun and rewarding part of your daily life!</p>
    </div>
    <div class="objective-container">
        <img src="logo/logo.png" alt="Website Logo" class="website-logo"/>
    </div>
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



<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>