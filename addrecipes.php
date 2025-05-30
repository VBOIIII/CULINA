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

// Banned words list
$badWords = [
    'adult', 'anal', 'ass', 'asshole', 'balls', 'bastard', 'bdsm', 'bitch',
    'blowjob', 'boobs', 'bomba', 'bullshit', 'butt', 'clickbait', 'clitoris', 'cock',
    'crap', 'cum', 'cumshot', 'cunt', 'deepthroat', 'dick', 'dildo', 'domination',
    'erotic', 'erotica', 'eroticism', 'fake', 'fetish', 'fetishes', 'fuck', 'fucked',
    'fucking', 'funny', 'gangbang', 'handjob', 'hentai', 'incest', 'jerk off',
    'kinky', 'laugh', 'lewd', 'lmao', 'lmfao', 'masturbate', 'masturbation',
    'marijuana', 'meme', 'memes', 'milf', 'motherfucker', 'naked', 'nude', 'orgasm',
    'orgy', 'penis', 'porn', 'pornography', 'porno', 'prank', 'pussy', 'rape',
    'rick astley', 'rickroll', 'rofl', 'scam', 'sex', 'sexually', 'sexy', 'shabu',
    'shit', 'shitpost', 'shitposting', 'slut', 'slutty', 'spam', 'spank', 'spanking',
    'sperm', 'stupid', 'strip', 'stripper', 'tae', 'tits', 'troll', 'twat',
    'vagina', 'vibrator', 'whore', 'xxx',
];

function containsBadWords($text, $badWords) {
    $text = strtolower($text);
    foreach ($badWords as $word) {
        // Use word boundaries and case-insensitive match
        if (preg_match('/\b' . preg_quote($word, '/') . '\b/i', $text)) {
            return true;
        }
    }
    return false;
}

// Ensure the input isn't too short or meaningless
function isValidRecipeText($text, $minWords = 5) {
    $text = strip_tags($text); // Remove HTML tags, keep all characters
    $wordCount = str_word_count($text);

    // Optional: Debug alert (remove or comment in production)
    // echo "<script>alert('Word count: $wordCount'); window.history.back();</script>";

    return $wordCount >= $minWords;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $recipeName = trim($_POST['recipe-name'] ?? '');
    $timeToCook = trim($_POST['time-to-cook'] ?? '');
    $ingredients = trim($_POST['ingredients'] ?? '');
    $instructions = trim($_POST['instructions'] ?? '');
    $category = trim($_POST['category'] ?? '');

    // Check for empty fields
    if (empty($recipeName) || empty($timeToCook) || empty($ingredients) || empty($instructions) || empty($category)) {
        echo "<script>alert('All fields are required.'); window.history.back();</script>";
        exit();
    }

    // Check for inappropriate or insufficient content
    if (
        containsBadWords($recipeName, $badWords) ||
        containsBadWords($ingredients, $badWords) ||
        containsBadWords($instructions, $badWords) ||
        !isValidRecipeText($ingredients) ||
        !isValidRecipeText($instructions)
    ) {
        echo "<script>alert('Recipe rejected: avoid bad words or overly short descriptions.'); window.history.back();</script>";
        exit();
    }

    // Handle image
    if (isset($_FILES["recipe-image"]) && $_FILES["recipe-image"]["error"] == 0) {
        $targetDir = "uploads/recipe_img/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $fileName = uniqid() . "-" . basename($_FILES["recipe-image"]["name"]);
        $targetFile = $targetDir . $fileName;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $validTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (!getimagesize($_FILES["recipe-image"]["tmp_name"])) {
            $message = '<div class="alert alert-danger">File is not a valid image.</div>';
        } elseif ($_FILES["recipe-image"]["size"] > 5000000) {
            $message = '<div class="alert alert-danger">Image too large. Max size: 5MB.</div>';
        } elseif (!in_array($imageFileType, $validTypes)) {
            $message = '<div class="alert alert-danger">Only JPG, JPEG, PNG & GIF are allowed.</div>';
        } else {
            if (move_uploaded_file($_FILES["recipe-image"]["tmp_name"], $targetFile)) {
                $stmt = $conn->prepare("INSERT INTO recipes (name, time_to_cook, ingredients, instructions, image, category) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssss", $recipeName, $timeToCook, $ingredients, $instructions, $targetFile, $category);

                if ($stmt->execute()) {
                    $_SESSION['message'] = '<div class="alert alert-success">Recipe added successfully!</div>';
                    header('Location: recipe.php');
                    exit;
                } else {
                    $message = '<div class="alert alert-danger">Database error: ' . $stmt->error . '</div>';
                }

                $stmt->close();
            } else {
                $message = '<div class="alert alert-danger">Image upload failed.</div>';
            }
        }
    } else {
        $message = '<div class="alert alert-danger">Please upload an image.</div>';
    }

    // Show message if not redirected
    if (!empty($message)) {
        echo "<script>alert(`" . strip_tags($message) . "`); window.history.back();</script>";
    }
}
?>
