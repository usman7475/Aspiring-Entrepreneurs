<?php

include 'database.php';
include 'navbar2.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php'); // Redirect to login if not logged in
    exit();
}

// Retrieve user information
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-body">

            <h5 class="card-title text-center">Welcome, <?php echo htmlspecialchars($user['name']); ?>!</h5>
            <!-- <p class="card-text text-center">You are now logged in. Here are your options:</p> -->
            <p class="card-text text-center fst-italic">You are now logged in. Here are your options:</p>


            <ul class="list-group">
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] !== 'User'): ?>
                    <li class="list-group-item"><a href="project.php">Add Post</a></li>
                <?php endif; ?>

                <li class="list-group-item"><a href="discussion.php">Join Discussions</a></li>
                
                <li class="list-group-item"><a href="update_profile.php">Edit Your Profile</a></li>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Admin'): ?>
                    <li class="list-group-item"><a href="users.php">Manage Users</a></li>

                    <li class="list-group-item"><a href="flagged_posts.php">Flag Content</a></li>

                <?php endif; ?>
                <li class="list-group-item"><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</div>
<?php  include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
