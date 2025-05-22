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
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $category_id = $_POST['category_id'] ?? ''; // Get category_id from the form
    $image = $_FILES['blog-image'];

    if (empty($title) || empty($content) || empty($category_id)) {
        $message = '<div class="alert alert-danger">All fields are required.</div>';
    } elseif (isset($image) && $image['error'] == 0) {
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($image["name"]);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        $validImageTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($imageFileType, $validImageTypes)) {
            $message = '<div class="alert alert-danger">Only JPG, JPEG, PNG & GIF files are allowed.</div>';
        } else {
            if (move_uploaded_file($image["tmp_name"], $targetFile)) {
                // Prepare and execute the SQL to insert the blog post
                $stmt = $conn->prepare("INSERT INTO blogs (title, content, category_id, image) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $title, $content, $category_id, $targetFile);

                if ($stmt->execute()) {
                    $message = '<div class="alert alert-success">Blog post added successfully!</div>';
                } else {
                    $message = '<div class="alert alert-danger">Error: ' . htmlspecialchars($stmt->error) . '</div>';
                }

                $stmt->close();
            } else {
                $message = '<div class="alert alert-danger">Sorry, there was an error uploading your image.</div>';
            }
        }
    } else {
        $message = '<div class="alert alert-danger">Invalid image file.</div>';
    }
}

$conn->close();
?>
