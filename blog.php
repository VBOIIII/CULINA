<?php
include 'connect.php';
session_start();

$userLoggedIn = isset($_SESSION['email']);
$userEmail = $userLoggedIn ? $_SESSION['email'] : 'Guest';

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "recipe_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';

// Handling Blog Post Submission
if (isset($_POST['submitBlogPost'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category_id = $_POST['category'];
    $image = $_FILES['blog-image'];

    // Handle file upload
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($image["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    
    // Check if image file is a valid image
    $check = getimagesize($image["tmp_name"]);
    if($check !== false) {
        // Validate file size (max 5MB)
        if ($image["size"] > 5000000) {
            $message = "Sorry, your file is too large.";
        } else {
            // Allow only certain file formats
            if (in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                if (move_uploaded_file($image["tmp_name"], $targetFile)) {
                    // Insert into database
                    $stmt = $conn->prepare("INSERT INTO blogs (title, content, category_id, image) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("ssss", $title, $content, $category_id, $targetFile);
                    if ($stmt->execute()) {
                        
                        $blogId = $conn->insert_id;  
                        
                        
                        header("Location: view_blogs.php?id=" . $blogId);
                        exit(); 
                    } else {
                        $message = "Error: " . $stmt->error;
                    }
                } else {
                    $message = "Sorry, there was an error uploading your image.";
                }
            } else {
                $message = "Only JPG, JPEG, PNG & GIF files are allowed.";
            }
        }
    } else {
        $message = "File is not an image.";
    }
}


// Pagination Variables
$blogs_per_page = 18;  // Adjust this value as needed
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $blogs_per_page;

// Fetch Blogs with Pagination
$blogResult = $conn->query("SELECT blogs.*, categories.name AS category_name FROM blogs JOIN categories ON blogs.category_id = categories.id ORDER BY created_at DESC LIMIT $blogs_per_page OFFSET $offset");
$blogs = [];
while ($row = $blogResult->fetch_assoc()) {
    $blogs[] = $row;
}

// Get Total Blogs Count
$total_blogs_result = $conn->query("SELECT COUNT(*) AS count FROM blogs");
$total_blogs = $total_blogs_result->fetch_assoc()['count'];
$total_pages = ceil($total_blogs / $blogs_per_page);

// Fetch Recipe Highlights
$recipeHighlightsResult = $conn->query("SELECT * FROM recipe_highlights ORDER BY created_at DESC");
$recipeHighlights = [];
while ($row = $recipeHighlightsResult->fetch_assoc()) {
    $recipeHighlights[] = $row;
}

// Fetch Cooking Tips
$cookingTipsResult = $conn->query("SELECT * FROM cooking_tips ORDER BY created_at DESC");
$cookingTips = [];
while ($row = $cookingTipsResult->fetch_assoc()) {
    $cookingTips[] = $row;
}

// Fetch Community Spotlight
$communitySpotlightResult = $conn->query("SELECT * FROM community_spotlight ORDER BY created_at DESC");
$communitySpotlight = [];
while ($row = $communitySpotlightResult->fetch_assoc()) {
    $communitySpotlight[] = $row;
}

// Fetch Categories
$categoriesResult = $conn->query("SELECT * FROM categories");
$categories = [];
while ($row = $categoriesResult->fetch_assoc()) {
    $categories[] = $row;
}

// Fetch Blog Posts by Category
$blogPostsByCategory = [];
foreach ($categories as $category) {
    $categoryId = $category['id'];
    $categoryResult = $conn->query("SELECT blogs.*, categories.name AS category_name 
                                    FROM blogs 
                                    JOIN categories ON blogs.category_id = categories.id 
                                    WHERE category_id = $categoryId 
                                    ORDER BY created_at DESC LIMIT $blogs_per_page OFFSET $offset");
    
    $blogPostsByCategory[$category['name']] = [];
    while ($row = $categoryResult->fetch_assoc()) {
        $blogPostsByCategory[$category['name']][] = $row;
    }
}


$highlightQuery = "
    SELECT r.*, COUNT(c.id) AS comment_count
    FROM recipes r
    LEFT JOIN comments c ON r.id = c.recipe_id
    GROUP BY r.id
    ORDER BY comment_count DESC
    LIMIT 6
";

$highlightResult = $conn->query($highlightQuery);

$highlights = [];
while ($row = $highlightResult->fetch_assoc()) {
    $highlights[] = $row;
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - Culina</title>
    <link href="css/main.css" rel="stylesheet" type="text/css" />
    <link href="css/blog.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,0,0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="js/login.js" defer></script>
    <script src="js/blog.js" defer></script>
    <script src="js/activepage.js" defer></script>

    

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

                <section class="hero-section">
                    <div class="video-container">
                        <video autoplay loop muted>
                            <source src="blog-background.mp4" type="video/mp4">
                        </video>
                    </div>
                </section>

   
                <section class="blog-section">


    <!-- Display Blogs for Each Category -->
    <?php foreach ($blogPostsByCategory as $categoryName => $blogPosts): ?>
    <div class="category-section">
        <h2><?php echo htmlspecialchars($categoryName); ?></h2>
        <div class="category-blogs">
            <?php foreach ($blogPosts as $post): ?>
                <div class="blog-post">
                    <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
                    <p><strong>Title:</strong> <?php echo htmlspecialchars($post['title']); ?></p>
                    <p><strong>Content:</strong> <?php echo substr(htmlspecialchars($post['content']), 0, 100); ?>...</p>
                    
                    <!-- Fetch the number of comments for this post -->
                    <?php
                    $postId = $post['id'];
                    $commentQuery = "SELECT COUNT(*) AS comment_count FROM comments_blog WHERE blog_id = ?";
                    $stmt = $conn->prepare($commentQuery);
                    $stmt->bind_param("i", $postId);
                    $stmt->execute();
                    $commentResult = $stmt->get_result();
                    $commentData = $commentResult->fetch_assoc();
                    $commentCount = $commentData['comment_count'];
                    ?>

                    <p>
                        <a href="view_blogs.php?id=<?php echo $post['id']; ?>" class="read-more-btn">Read More</a>
                    </p>

                    <!-- Comment icon and count on the right side -->
                    <div class="comment-info">
                        <span class="material-icons">comment</span> <?php echo $commentCount; ?> Comments
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endforeach; ?>

<div class="pagination">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?php echo $i; ?>" class="page-link <?php if ($i == $page) echo 'active'; ?>"><?php echo $i; ?></a>
        <?php endfor; ?>
    </div>



    <?php if ($userLoggedIn): ?>
        <div class="post-btn-container">
            <button class="add-blog-btn">Add Blog Post</button>
        </div>
    <?php endif; ?>
</section>

<!-- Add Blog Post Form (popup) -->

<div class="blog-blur-bg-overlay"></div>
<div class="blog-popup-container">
    <div class="blog-popup-box">
        <span class="close-btn blog-close-btn" id="close-btn">close</span>
        <h2>Add Blog Post</h2>
        <?php echo $message; ?>
        <form id="blog-form" action="blog.php" method="POST" enctype="multipart/form-data">
            <div class="blog-form-row">
                <label for="title">Blog Title</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div class="blog-form-row">
                <label for="content">Content</label>
                <textarea id="content" name="content" required></textarea>
            </div>
            <div class="blog-form-row">
                <label for="category">Category</label>
                <select id="category" name="category" required>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="blog-form-row">
                <label for="blog-image">Upload Image</label>
                <input type="file" id="blog-image" name="blog-image" accept="image/*" required>
            </div>
            <button type="submit" name="submitBlogPost">Submit Blog Post</button>
        </form>
    </div>
</div>

   

    <!-- Recipe Highlights Section -->
    <section id="recipe-highlights">
    <h2>Recipe Highlights</h2>
    <div id="highlight-container">
        <?php if (count($highlights) > 0): ?>
            <?php foreach ($highlights as $recipe): ?>
                <div class="highlight-recipe">
                    <img src="<?php echo htmlspecialchars($recipe['image']); ?>" alt="<?php echo htmlspecialchars($recipe['name']); ?>" />
                    <div class="recipe-info">
                        <h3><?php echo htmlspecialchars($recipe['name']); ?></h3>
                        <p><strong>Comments:</strong> <?php echo htmlspecialchars($recipe['comment_count']); ?></p>
                        <p><strong>Time to Cook:</strong> <?php echo htmlspecialchars($recipe['time_to_cook']); ?></p>
                        <a href="view_recipe.php?id=<?php echo $recipe['id']; ?>" class="view-recipe-btn">View Recipe</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No popular recipes found!</p>
        <?php endif; ?>
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
