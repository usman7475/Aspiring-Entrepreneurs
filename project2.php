<?php

include 'database.php';
include 'navbar2.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Fetch the user's role
$user_id = $_SESSION['user_id'];
$query = "SELECT role FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($role);
$stmt->fetch();
$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $created_by = $_SESSION['user_id']; // Assuming the user ID is stored in the session

    // Insert the project into the database
    $stmt = $conn->prepare("INSERT INTO projects (title, description, created_by) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $title, $description, $created_by);
    
    if ($stmt->execute()) {
        $success_message = "Project added successfully!";
    } else {
        $error_message = "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Handle project deletion
if (isset($_GET['delete_id']) && $role === 'Admin') {
    $project_id = $_GET['delete_id'];

    // Delete the project from the database
    $stmt = $conn->prepare("DELETE FROM projects WHERE id = ?");
    $stmt->bind_param("i", $project_id);
    
    if ($stmt->execute()) {
        $success_message = "Project deleted successfully!";
    } else {
        $error_message = "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch all projects depending on user role
if ($role === 'Admin') {
    // Admin can see all projects
    $query = "SELECT * FROM projects";
} else {
    // Regular users can only see their own projects
    $query = "SELECT * FROM projects WHERE created_by = ?";
}

$stmt = $conn->prepare($query);
if ($role === 'Admin') {
    $stmt->execute();
} else {
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
}
$result = $stmt->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Project</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="text-center mb-4">Add New Project</h2>

    <?php if (isset($success_message)): ?>
        <div class="alert alert-success" role="alert">
            <?php echo $success_message; ?>
        </div>
    <?php elseif (isset($error_message)): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label for="title" class="form-label">Project Title:</label>
                    <input type="text" name="title" id="title" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description:</label>
                    <textarea name="description" id="description" class="form-control" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary w-100">Add Project</button>
            </form>
        </div>
    </div>

    <!-- Display the projects -->
    <h3 class="mb-4">Projects</h3>
    <div class="list-group">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <a href="project_discussion.php?project_id=<?php echo $row['id']; ?>" class="text-decoration-none">
                    <strong><?php echo htmlspecialchars($row['title']); ?></strong>
                    <p class="mb-0"><?php echo htmlspecialchars($row['description']); ?></p>
                </a>
                
                <!-- Admin Delete Option -->
                <?php if ($role === 'Admin'): ?>
                    <a href="?delete_id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this project?');">Delete</a>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php
$stmt->close();
?>
