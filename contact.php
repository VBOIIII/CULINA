<?php 
include 'connect.php'; 
session_start(); 


$userLoggedIn = isset($_SESSION['email']);
$userEmail = $userLoggedIn ? $_SESSION['email'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link href="css/contact-recipe.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,0,0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="js/login.js" defer></script>
    <script src="js/activepage.js" defer></script>

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

    <section id="contact" class="contact">
        <div class="contact-container" data-aos="fade-in" data-aos-duration="4000">
            <div class="contact-info">
                <div class="contact-info-content">
                    <h2>Contact <span class="text-gradient">Us</span></h2>
                    <p>Do you have a question or are you interested in collaborating with us? We would absolutely love to hear from you! Your thoughts and ideas are important to us, and we believe that great things can happen when we work together.</p>
                    <div class="contact-details">
                        <div class="contact-detail">
                            <i class="bi bi-envelope-fill"></i>
                            <span>contact@yourcompany.com</span>
                        </div>
                        <div class="contact-detail">
                            <i class="bi bi-globe"></i>
                            <span><a href="https://www.yourwebsite.com" target="_blank">Visit Our Website</a></span>
                        </div>
                        <div class="contact-detail">
                            <i class="bi bi-phone-fill"></i>
                            <span>+1234567890</span>
                        </div>
                        <div class="contact-detail">
                            <i class="bi bi-geo-alt-fill"></i>
                            <span>123 Tech Avenue, Your City</span>
                        </div>
                    </div>
                    <div class="contact-social">
                        <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                        <a href="#" aria-label="Twitter"><i class="bi bi-twitter"></i></a>
                        <a href="#" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
                        <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                    </div>
                </div>
            </div>

            <form class="contact-form" id="contact-form" method="POST" action="submit_contact.php">
                <div class="form-group">
                    <label for="name">Your Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Your Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($userEmail); ?>" required>
                </div>
                <div class="form-group">
                    <label for="message">Your Message</label>
                    <textarea id="message" name="message" rows="5" required></textarea>
                </div>
                <button type="submit" class="submit-btn">Send Message</button>
            </form>

    </div>
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
