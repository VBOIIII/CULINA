<?php
$conn = new mysqli("localhost", "root", "", "recipe_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM comments WHERE recipe_id = $id");
    $conn->query("DELETE FROM recipes WHERE id = $id");
    header("Location: admin_dashboard.php");
    exit;
}

$filter = "";
if (isset($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
    $filter = "WHERE name LIKE '%$search%' OR ingredients LIKE '%$search%' OR instructions LIKE '%$search%'";
}

$result = $conn->query("SELECT * FROM recipes $filter ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Culina Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Montserrat', sans-serif;
            background-color: #f0ede6;
            color: #333;
        }

        .hero {
            position: relative;
            background: #2e3437;
            height: 240px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            font-size: 3em;
            font-weight: bold;
            text-shadow: 2px 2px 6px #000;
        }

        .hero .logo {
            position: absolute;
            left: 350px;
            top: 50px;
            height: 130px;
            width: auto;
            object-fit: contain;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.08);
        }

        .back-btn {
            background-color: transparent;
            color: #2e3437;
            text-decoration: none;
            border: 1px solid #2e3437;
            padding: 8px 14px;
            border-radius: 6px;
            font-weight: 600;
            transition: 0.2s;
        }

        .back-btn:hover {
            background-color: #2e3437;
            color: #fff;
        }

        .search-bar {
            margin: 30px 0 20px;
            display: flex;
            gap: 10px;
        }

        .search-bar input {
            flex: 1;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 1em;
        }

        .search-bar button {
            background-color: #2e3437;
            color: #fff;
            border: none;
            padding: 12px 18px;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
        }

        .search-bar button:hover {
            background-color: #f4a261;
        }

        .section-title {
            margin-top: 60px;
            font-size: 1.8em;
            font-weight: 600;
            color: #2e3437;
            border-bottom: 2px solid #ddd;
            padding-bottom: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 0.95em;
        }

        thead {
            background-color: #faf7f2;
        }

        th, td {
            padding: 14px 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            vertical-align: middle;
        }

        tbody tr:hover {
            background-color: #fdf4ea;
        }

        .btn-danger {
            background-color: #c0392b;
            color: #fff;
            border: none;
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 0.9em;
            cursor: pointer;
        }

        .btn-danger:hover {
            background-color: #e74c3c;
        }

        /* Optional: Responsive table */
        @media screen and (max-width: 768px) {
            .search-bar {
                flex-direction: column;
            }

            .search-bar button {
                width: 100%;
            }

            table, thead, tbody, th, td, tr {
                display: block;
            }

            thead {
                display: none;
            }

            tr {
                margin-bottom: 15px;
                background-color: #fff;
                padding: 12px;
                box-shadow: 0 2px 6px rgba(0,0,0,0.05);
                border-radius: 6px;
            }

            td {
                border: none;
                position: relative;
                padding-left: 50%;
                font-size: 0.95em;
            }

            td::before {
                position: absolute;
                left: 15px;
                width: 45%;
                white-space: nowrap;
                font-weight: bold;
            }

            td:nth-of-type(1)::before { content: "ID"; }
            td:nth-of-type(2)::before { content: "Name"; }
            td:nth-of-type(3)::before { content: "Category"; }
            td:nth-of-type(4)::before { content: "Time to Cook"; }
            td:nth-of-type(5)::before { content: "Created"; }
            td:nth-of-type(6)::before { content: "Action"; }
        }
    </style>
</head>
<body>

<div class="hero">
    <img src="logo/logo.png" alt="Culina Logo" class="logo">
    MANAGE RECIPES
</div>

<div class="container">
   

    <form method="get" class="search-bar mt-4">
        <input type="text" name="search" placeholder="Search recipes..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        <button type="submit">Search</button>
    </form>

    <h2 class="section-title">Recipe List</h2>
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Category</th>
            <th>Time to Cook</th>
            <th>Created</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['category']) ?></td>
                <td><?= htmlspecialchars($row['time_to_cook']) ?></td>
                <td><?= htmlspecialchars($row['created_at']) ?></td>
                <td>
                    <?php if (!empty($row['image'])): ?>
                        <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>" style="max-width: 120px; height: auto; border-radius: 8px; margin-bottom: 6px;">
                    <?php else: ?>
                        No image
                    <?php endif; ?>
                    <br>
                    <a href="?delete=<?= $row['id'] ?>" class="btn-danger" onclick="return confirm('Are you sure you want to delete this recipe?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <!-- User Messages Section -->
    <h2 class="section-title">User Messages</h2>

    <?php
    $msg_conn = new mysqli("localhost", "root", "", "recipe_platform");
    if ($msg_conn->connect_error) {
        echo "<p style='color: red;'>Failed to load messages: " . $msg_conn->connect_error . "</p>";
    } else {
        $msg_result = $msg_conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
        if ($msg_result && $msg_result->num_rows > 0): ?>
            <table>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Submitted At</th>
                </tr>
                </thead>
                <tbody>
                <?php while ($msg = $msg_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $msg['message_id'] ?></td>
                        <td><?= htmlspecialchars($msg['name']) ?></td>
                        <td><?= htmlspecialchars($msg['email']) ?></td>
                        <td><?= nl2br(htmlspecialchars($msg['message'])) ?></td>
                        <td><?= htmlspecialchars($msg['created_at']) ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No user messages found.</p>
        <?php endif;
        $msg_conn->close();
    }
    ?>
</div>

</body>
</html>
