<?php
include 'connect.php';
session_start();

$userLoggedIn = isset($_SESSION['email']);

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "recipe_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';

// Determine current category from GET, default to first category if not set
$categoriesResult = $conn->query("SELECT DISTINCT category FROM recipes");
$categories = [];
while ($row = $categoriesResult->fetch_assoc()) {
    $categories[] = $row['category'];
}

$currentCategory = isset($_GET['category']) && in_array($_GET['category'], $categories) ? $_GET['category'] : $categories[0];

// Pagination variables for current category
$recipes_per_page = 6;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $recipes_per_page;

// Fetch recipes for current category with pagination
$recipes = [];
$stmt = $conn->prepare("SELECT * FROM recipes WHERE category = ? LIMIT ? OFFSET ?");
$stmt->bind_param("sii", $currentCategory, $recipes_per_page, $offset);
$stmt->execute();
$result = $stmt->get_result();
while ($recipe = $result->fetch_assoc()) {
    $recipes[] = $recipe;
}
$stmt->close();

// Calculate total pages for current category
$count_stmt = $conn->prepare("SELECT COUNT(*) AS count FROM recipes WHERE category = ?");
$count_stmt->bind_param("s", $currentCategory);
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$count_data = $count_result->fetch_assoc();
$total_recipes = $count_data['count'];
$count_stmt->close();

$total_pages = ceil($total_recipes / $recipes_per_page);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Recipes - Bookstore Template</title>
    <link href="css/main.css" rel="stylesheet" type="text/css" />
    <link href="css/recipe.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,0,0" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet" />

    <script src="js/pagination.js" type="text/javascript"></script>
    <script src="js/recipes.js" type="text/javascript"></script>
    <script src="js/login.js" defer></script>
    <script src="js/activepage.js" defer></script>  
    <script src="js/addrecipe.js" defer></script>
</head>
<body>

<header>
    <nav class="navbar">
        <span class="hamburger-btn material-symbols-rounded">menu</span>
        <a href="#" class="logo">
            <img src="logo/logo-header.png" alt="logo" />
        </a>
        <ul class="links">
            <span class="close-btn material-symbols-rounded">close</span>
            <li id="home-link"><a href="home.php">Home</a></li>
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
                    <input type="email" name="email" id="loginEmail" placeholder="Enter your email" required />
                </div>
                <div class="input-field">
                    <input type="password" name="password" id="loginPassword" placeholder="Enter your password" required />
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
                    <input type="text" name="username" id="signupUsername" placeholder="Enter your username" required />
                </div>
                <div class="input-field">
                    <input type="email" name="email" id="signupEmail" placeholder="Enter your email" required />
                </div>
                <div class="input-field">
                    <input type="password" name="password" id="signupPassword" placeholder="Create a password" required />
                </div>
                <div class="policy-text">
                    <input type="checkbox" id="policy" required />
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

<div id="recipe_page">
    <section
        id="section1"
        style="background: url('image/recipe_bg.jpg') no-repeat center center; background-size: cover; height: 60vh; display: flex; justify-content: center; align-items: center; text-align: center;"
    >
        <h1>Delicious Recipes</h1>
        <h2>"A recipe is a story that ends with a good meal."</h2>
    </section>

    <section id="section2">
        <aside id="sidebar">
            <h2>Categories</h2>
            <ul>
                <?php foreach ($categories as $cat): ?>
                    <li>
                        <a href="?category=<?php echo urlencode($cat); ?>"><?php echo ucfirst($cat); ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </aside>

        <main id="recipe-content">
            <div id="search-bar">
                <input type="text" id="search-input" placeholder="Search recipes..." />
                <button id="search-btn">Search</button>
            </div>

            <section class="category-content">
                <h2><?php echo ucfirst($currentCategory); ?> Recipes</h2>
                <div id="recipe-container">
                    <?php foreach ($recipes as $recipe): ?>
                        <div class="recipe">
                            <img src="<?php echo htmlspecialchars($recipe['image']); ?>" alt="<?php echo htmlspecialchars($recipe['name']); ?>" />
                            <div class="recipe-info">
                                <h3><?php echo htmlspecialchars($recipe['name']); ?></h3>
                                <p><strong>Time to Cook:</strong> <?php echo htmlspecialchars($recipe['time_to_cook']); ?></p>
                                <p><strong>Ingredients:</strong> <?php echo htmlspecialchars($recipe['ingredients']); ?></p>

                                <?php
                                // Fetch comment count for the current recipe
                                $recipeId = $recipe['id'];
                                $commentQuery = "SELECT COUNT(*) AS comment_count FROM comments WHERE recipe_id = ?";
                                $stmt = $conn->prepare($commentQuery);
                                $stmt->bind_param("i", $recipeId);
                                $stmt->execute();
                                $commentResult = $stmt->get_result();
                                $commentData = $commentResult->fetch_assoc();
                                $commentCount = $commentData['comment_count'];
                                $stmt->close();
                                ?>

                                <a href="view_recipe.php?id=<?php echo $recipe['id']; ?>" class="view-recipe-btn">View Recipe</a>

                                <div class="comment-info">
                                    <span class="material-icons">comment</span> <?php echo $commentCount; ?> Comments
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="pagination">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="?category=<?php echo urlencode($currentCategory); ?>&page=<?php echo $i; ?>" class="page-link <?php if ($i === $page) echo 'active'; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                </div>
            </section>
        </main>

        <div class="recipe-btn-container">
            <?php if ($userLoggedIn): ?>
                <button class="recipe-add-btn">Add Recipe</button>
            <?php endif; ?>
        </div>

        <div class="recipe-popup-container">
            <div class="recipe-popup-box">
                <span class="close-btn material-symbols-rounded" id="close-btn">close</span>
                <h2>Recipe Submission</h2>
                <?php echo $message; ?>
                <form id="recipe-form" action="addrecipes.php" method="POST" enctype="multipart/form-data">
                    <div class="recipe-form-row">
                        <div class="recipe-input-field">
                            <label for="recipe-name">Recipe Name</label>
                            <input type="text" id="recipe-name" name="recipe-name" required />
                        </div>
                        <div class="recipe-input-field">
                            <label for="cook-time">Time to Cook</label>
                            <input type="text" id="cook-time" name="time-to-cook" required />
                        </div>
                    </div>
                    <div class="recipe-form-row">
                        <div class="recipe-input-field">
                            <label for="ingredients">Ingredients</label>
                            <textarea id="ingredients" name="ingredients" required></textarea>
                        </div>
                        <div class="recipe-input-field">
                            <label for="instructions">Instructions</label>
                            <textarea id="instructions" name="instructions" required></textarea>
                        </div>
                    </div>
                    <div class="recipe-form-row">
                        <div class="recipe-input-field">
                            <label for="category">Category</label>
                            <select id="category" name="category" required>
                                <option value="breakfast">Breakfast</option>
                                <option value="lunch">Lunch</option>
                                <option value="dinner">Dinner</option>
                                <option value="desserts">Desserts</option>
                                <option value="drinks">Drinks</option>
                            </select>
                        </div>
                        <div class="recipe-input-field">
                            <label for="file-upload">Upload Image</label>
                            <input type="file" id="file-upload" name="recipe-image" accept="image/*" />
                        </div>
                    </div>
                    <div class="recipe-input-field">
                        <button type="submit">Submit Recipe</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Your JavaScript for toggling login/signup popup forms etc.
    function closePopup() {
        document.getElementById('formPopup').style.display = 'none';
    }
    function showSignupForm() {
        document.getElementById('loginForm').style.display = 'none';
        document.getElementById('signupForm').style.display = 'block';
    }
    function showLoginForm() {
        document.getElementById('signupForm').style.display = 'none';
        document.getElementById('loginForm').style.display = 'block';
    }
</script>

</body>
</html>
