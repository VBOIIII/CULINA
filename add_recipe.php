<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "recipe_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $recipeName = $_POST['recipe-name'] ?? '';
    $timeToCook = $_POST['time-to-cook'] ?? '';
    $ingredients = $_POST['ingredients'] ?? '';
    $instructions = $_POST['instructions'] ?? '';
    $category = $_POST['category'] ?? '';
    
    if (isset($_FILES["recipe-image"]) && $_FILES["recipe-image"]["error"] == 0) {
        $targetDir = "uploads/";
        $targetFile = $targetDir . uniqid() . "-" . basename($_FILES["recipe-image"]["name"]);
        
        if (move_uploaded_file($_FILES["recipe-image"]["tmp_name"], $targetFile)) {
            $stmt = $conn->prepare("INSERT INTO recipes (name, time_to_cook, ingredients, instructions, image, category) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $recipeName, $timeToCook, $ingredients, $instructions, $targetFile, $category);

            if ($stmt->execute()) {
                echo "Recipe added successfully!";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "No image file uploaded.";
    }
}
