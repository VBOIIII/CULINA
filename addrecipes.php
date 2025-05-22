<?php
session_start();

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "recipe_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $recipeName = $_POST['recipe-name'] ?? '';
    $timeToCook = $_POST['time-to-cook'] ?? '';
    $ingredients = $_POST['ingredients'] ?? '';
    $instructions = $_POST['instructions'] ?? '';
    $category = $_POST['category'] ?? '';

    if (empty($recipeName) || empty($timeToCook) || empty($ingredients) || empty($instructions) || empty($category)) {
        $message = '<div class="alert alert-danger">All fields are required.</div>';
    } elseif (isset($_FILES["recipe-image"]) && $_FILES["recipe-image"]["error"] == 0) {
        $targetDir = "uploads/recipe_img/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $targetFile = $targetDir . uniqid() . "-" . basename($_FILES["recipe-image"]["name"]);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        if (getimagesize($_FILES["recipe-image"]["tmp_name"]) === false) {
            $message = '<div class="alert alert-danger">File is not an image.</div>';
        } elseif ($_FILES["recipe-image"]["size"] > 5000000) {
            $message = '<div class="alert alert-danger">Sorry, your file is too large. Max allowed size is 5MB.</div>';
        } elseif (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            $message = '<div class="alert alert-danger">Only JPG, JPEG, PNG & GIF files are allowed.</div>';
        } else {
            if (move_uploaded_file($_FILES["recipe-image"]["tmp_name"], $targetFile)) {
                // Insert recipe into database with the image path
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
                $message = '<div class="alert alert-danger">Sorry, there was an error uploading your file.</div>';
            }
        }
    } else {
        $message = '<div class="alert alert-danger">No image file uploaded or there was an error with the upload.</div>';
    }
}
?>
