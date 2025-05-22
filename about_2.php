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

<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/JavaScript" src="js/slideshow.js"></script>



<link rel="stylesheet" type="text/css" media="all" href="css/jquery.dualSlider.0.2.css" />

<script src="js/jquery-1.3.2.min.js" type="text/javascript"></script>
<script src="js/jquery.easing.1.3.js" type="text/javascript"></script>
<script src="js/jquery.timers-1.2.js" type="text/javascript"></script>
<script src="js/jquery.dualSlider.0.3.min.js" type="text/javascript"></script>


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

<script type="text/javascript" src="js/jquery-1-4-2.min.js"></script> 
<link rel="stylesheet" href="css/slimbox2.css" type="text/css" media="screen" /> 
<script type="text/JavaScript" src="js/slimbox2.js"></script> 
 

</head>
<body>
<header>
        <nav class="navbar">
            <span class="hamburger-btn material-symbols-rounded">menu</span>
            <a href="#" class="logo">
                <img src="images/logo.jpg" alt="logo">
                <h2>CodingNepal</h2>
            </a>
            <ul class="links">
                <span class="close-btn material-symbols-rounded">close</span>
                <li><a href="index.html" class="selected">Home</a></li>
                <li>
                    <a href="about.html">About</a>
                    <ul>
                        <li><a href="#about-us-container">About Us</a></li>
                        <li><a href="#recipe-container">Recipe</a></li>
                        <li><a href="#grid">Best Foods</a></li>
                        <li><a href="#templatemo_slider_wrapper">Goal</a></li>
                    </ul>
                </li>
                <li><a href="recipe.php">Recipe</a></li>
                <li><a href="blog.html">Blog</a></li>
                <li><a href="contact.html">Contact</a></li>
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
<div class="form-popup">
    <span class="close-btn material-symbols-rounded">close</span>
    <div class="form-box login">
        <div class="form-details">
            <h2>Welcome Back</h2>
            <p>Please log in using your personal information to stay connected with us.</p>
        </div>
        <div class="form-content">
            <h2>LOGIN</h2>
            <form action="login.php" method="POST">
                <div class="input-field">
                    <input type="email" name="email" required>
                    <label>Email</label>
                </div>
                <div class="input-field">
                    <input type="password" name="password" required>
                    <label>Password</label>
                </div>
                <a href="#" class="forgot-pass-link">Forgot password?</a>
                <button type="submit" name="login">Log In</button>
            </form>
            <div class="bottom-link">
                Don't have an account? <a href="#" id="signup-link">Signup</a>
            </div>
        </div>
    </div>
    <div class="form-box signup">
        <div class="form-details">
            <h2>Create Account</h2>
            <p>To become a part of our community, please sign up using your personal information.</p>
        </div>
        <div class="form-content">
            <h2>SIGNUP</h2>
            <form action="signup.php" method="POST">
                <div class="input-field">
                    <input type="email" name="email" required>
                    <label>Enter your email</label>
                </div>
                <div class="input-field">
                    <input type="password" name="password" required>
                    <label>Create password</label>
                </div>
                <div class="policy-text">
                    <input type="checkbox" id="policy" required>
                    <label for="policy">I agree to the <a href="#" class="option">Terms & Conditions</a></label>
                </div>
                <button type="submit" name="signup">Sign Up</button>
            </form>
            <div class="bottom-link">
                Already have an account? <a href="#" id="login-link">Login</a>
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
    
        <a href="recipe.html" title="view more" class="view-more-btn">View More</a>
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
    
        <div class="clear"></div>
        <div id="templatemo_slider_wrapper">
            <div id="templatemo_slider">
            <div id="carousel">
                <div class="panel">
                        
                        <div class="details_wrapper">
                            
                            <div class="details">
                            
                                <div class="detail">
                                    <h2>Discover a World of Flavor!</h2>
                                    <p>Explore thousands of delicious recipes for every taste, skill level, and dietary preference. Start your culinary adventure today!</p>
                                    <a href="#" title="Read more" class="more">Explore</a>
                                </div>
                                
                                <div class="detail">
                                    <h2>Easy-to-Follow Recipes</h2>
                                    <p>From simple meals to gourmet dishes, find recipes with step-by-step instructions, photos, and tips to make cooking easier and more fun!</p>
                                    <a href="#" title="Read more" class="more">Explore</a>
                                </div>
                                
                                <div class="detail">
                                    <h2>Find Recipes for Every Meal</h2>
                                    <p>Search by ingredients, cuisine, or difficulty level. Get inspired to cook something new today!</p>
                                    <a href="#" title="Read more" class="more">Explore</a>
                                </div>
                                                            
                            </div>
                            
                        </div>
                        
                      	<div class="paging">
                            <div id="numbers"></div>
                            <a href="javascript:void(0);" class="previous" title="Previous" >Previous</a>
                            <a href="javascript:void(0);" class="next" title="Next">Next</a>
                        </div>
                        
                        <a href="javascript:void(0);" class="play" title="Turn on autoplay">Play</a>
                        <a href="javascript:void(0);" class="pause" title="Turn off autoplay">Pause</a>
                        
                    </div>
                    
                    <div id="slider-image-frame">
                        <div class="backgrounds">
                            
                            <div class="item item_1">
                                <img src="image\slide1.jpg" alt="Slider 01" />
                            </div>
                            
                            <div class="item item_2">
                                <img src="image\slide2.jpg" alt="Slider 02" />
                            </div>
                            
                            <div class="item item_3">
                                <img src="image\slide3.jpg" alt="Slider 03" />
                            </div>
                            
                        </div>
                  </div>
                </div>
            </div> 
        </div> 
    </div> 
</div> 


</div> 
<div id="templatemo_footer_wrapper">
	<div id="templatemo_footer">
    
        <div class="col one_fourth">
            <h4>Recent Posts</h4>
            <ul class="no_bullet">
                <li>
                    <span class="header"><a href="#">Pellentesque nec tempus</a></span>
                    Phasellus porttitor lacus vel risus ullamcorper tempor.
                </li>
                <li>
                    <span class="header"><a href="#">Ipsum dolor sit amet</a></span>
                    Nunc facilisis auctor metus, at mollis urna dictum sit amet.
                </li>
                <li>
                    <span class="header"><a href="#">Morbi tincidunt dictum leo</a></span>
                    Aliquam aliquam lacus eros, ut cursus augue dolor. 
                </li>
            </ul>
        </div>
        <div class="col one_fourth">
            <h4>Twitter</h4>
            <ul class="no_bullet">
                <li><a href="#">@templatemo</a> at scelerisque urna in tellus varius ultricies.</li>
                <li>Suspendisse enean <a href="#">#FREE</a> tincidunt massa in tellus varius ultricies.</li>
                <li>Aenean tincidunt massa in tellus varius ultricies. <a href="#">http://bit.ly/13IwZO</a></li>
                <li>Vulputate odio sit amet adipiscing lacus. <a href="#">http://bit.ly/IyyUoO</a></li>
            </ul>
        </div>
        <div class="col one_fourth no_margin_right">
            <h4>About</h4>
            <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus porttitor lacus vel risus ullamcorper tempor. Pellentesque vestibulum vulputate odio sit amet adipiscing. Nunc facilisis auctor metus, at mollis urna dictum sit amet.</p>
                Copyright © 2048 <a href="#">Your Company Name</a> 
        </div>
        <div class="clear"></div>
    </div> 
</div> 



</body>
</html>