<?php
session_start();

// DB config
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "recipe_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';

// List of banned words
$badWords = [
    'porn', 'porno', 'sex', 'xxx', 'nude', 'naked', 'adult', 'erotic', 'erotica',
    'pussy', 'dick', 'cock', 'asshole', 'bitch', 'slut', 'whore', 'fetish',
    'fetishes', 'anal', 'cum', 'boobs', 'breasts', 'penis', 'vagina', 'tits',
    'rape', 'incest', 'fuck', 'fucking', 'fucked', 'motherfucker', 'blowjob',
    'cunt', 'dildo', 'hentai', 'gangbang', 'orgy', 'milf', 'bdsm', 'domination',
    'kinky', 'lewd', 'masturbate', 'masturbation', 'sexy', 'porno', 'sexually',
    'eroticism', 'pornography', 'strip', 'stripper', 'twat', 'jerk off', 'handjob',
    'cumshot', 'butt', 'spank', 'spanking', 'deepthroat', 'clitoris', 'vibrator',
    'sperm', 'orgasm', 'ass', 'balls', 'bastard', 'slutty', 'twat', 'meme',
    'memes', 'shitpost', 'shitposting', 'troll', 'spam', 'advertisement',
    'ad', 'scam', 'clickbait', 'fake', 'prank', 'shit', 'bullshit', 'crap',
    'stupid', 'rickroll', 'rick astley', 'funny', 'laugh', 'lol', 'rofl', 'lmao',
    'lmfao', 'tae', 'bomba', 'marijuana', 'shabu'
];

// Check for bad words
function containsBadWords($text, $badWords) {
    $text = strtolower($text);
    foreach ($badWords as $word) {
        if (strpos($text, $word) !== false) {
            return true;
        }
    }
    return false;
}

// Check for numbers in text
function containsNumbers($text) {
    return preg_match('/\d/', $text);
}

// Check if input is likely valid (not random garbage)
function isValidRecipeText($text, $minWords = 5) {
    $text = strip_tags($text);
    $wordCount = str_word_count($text);
    return $wordCount >= $minWords;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and fetch form data
    $recipeName = trim($_POST['recipe-name'] ?? '');
    $timeToCook = trim($_POST['time-to-cook'] ?? '');
    $ingredients = trim($_POST['ingredients'] ?? '');
    $instructions = trim($_POST['instructions'] ?? '');
    $category = trim($_POST['category'] ?? '');

    // Validate content
    if (
        empty($recipeName) || empty($timeToCook) || empty($ingredients) || empty($instructions) || empty($category) ||
        containsBadWords($recipeName, $badWords) ||
        containsBadWords($ingredients, $badWords) ||
        containsBadWords($instructions, $badWords) ||
        containsNumbers($recipeName) ||
        !isValidRecipeText($ingredients) ||
        !isValidRecipeText($instructions)
    ) {
        echo "<script>
            alert('Invalid recipe: avoid numbers in title, bad words, and make sure ingredients/instructions are detailed.');
            window.history.back();
        </script>";
        exit();
    }

    // Handle image
    if (isset($_FILES["recipe-image"]) && $_FILES["recipe-image"]["error"] == 0) {
        $targetDir = "uploads/recipe_img/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $targetFile = $targetDir . uniqid() . "-" . basename($_FILES["recipe-image"]["name"]);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        if (getimagesize($_FILES["recipe-image"]["tmp_name"]) === false) {
            $message = '<div class="alert alert-danger">File is not an image.</div>';
        } elseif ($_FILES["recipe-image"]["size"] > 5000000) {
            $message = '<div class="alert alert-danger">File is too large. Max size: 5MB.</div>';
        } elseif (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            $message = '<div class="alert alert-danger">Only JPG, JPEG, PNG & GIF formats are allowed.</div>';
        } else {
            if (move_uploaded_file($_FILES["recipe-image"]["tmp_name"], $targetFile)) {
                $stmt = $conn->prepare("INSERT INTO recipes (name, time_to_cook, ingredients, instructions, image, category) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssss", $recipeName, $timeToCook, $ingredients, $instructions, $targetFile, $category);

                if ($stmt->execute()) {
                    $_SESSION['message'] = '<div class="alert alert-success">Recipe added successfully!</div>';
                    header('Location: recipe.php');
                    exit;
                } else {
                    $message = '<div class="alert alert-danger">Error: ' . $stmt->error . '</div>';
                }

                $stmt->close();
            } else {
                $message = '<div class="alert alert-danger">Error uploading image.</div>';
            }
        }
    } else {
        $message = '<div class="alert alert-danger">No image uploaded or upload failed.</div>';
    }
}
?>
