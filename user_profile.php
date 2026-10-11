<?php
// user_profile.php
include 'database.php';
include 'navbar2.php';

if (!isset($_GET['user_id'])) {
    echo "User not found.";
    exit();
}

$user_id = $_GET['user_id'];

// Fetch user details
$stmt = $conn->prepare("SELECT name, email, profile_picture, bio FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name, $email, $profile_picture, $bio);
$stmt->fetch();
$stmt->close();

if (!$name) {
    echo "User not found.";
    exit();
}

// Handle feedback submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['feedback_text'])) {
    $feedback_from = $_SESSION['user_id'];
    $feedback_to = $user_id;
    $feedback_text = $_POST['feedback_text'];
    $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;

    $feedback_stmt = $conn->prepare("INSERT INTO feedback (feedback_from, feedback_to, feedback_text, rating) VALUES (?, ?, ?, ?)");
    $feedback_stmt->bind_param("iisi", $feedback_from, $feedback_to, $feedback_text, $rating);
    $feedback_stmt->execute();
    $feedback_stmt->close();

    $success_message = "Feedback submitted successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css"> <!-- Custom CSS -->
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="text-center mb-4"><?php echo htmlspecialchars($name); ?>'s Profile</h2>

    <div class="card mb-4">
        <div class="card-body text-center">
            <?php if ($profile_picture): ?>
                <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture" class="img-fluid rounded-circle" style="width: 150px; height: 150px;">
            <?php endif; ?>
            <h5 class="card-title mt-3"><?php echo htmlspecialchars($name); ?></h5>
            <p class="card-text"><?php echo htmlspecialchars($bio); ?></p>
            <p class="card-text"><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
        </div>
    </div>
</div>

<!-- Feedback Section -->
<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-light text-primary text-center">
            <h3 class="mb-0" style="color:#6C757D;">Feedback</h3>
        </div>
        <div class="card-body">
            <?php if (isset($success_message)): ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php endif; ?>

            <!-- Feedback Form -->
            <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] != $user_id): ?>
            <div class="card mb-4 bg-light">
                <div class="card-body">
                    <h5 class="card-title">Leave Feedback</h5>
                    <form method="POST">
                        <div class="mb-3">
                            <label for="rating" class="form-label">Rating:</label>
                            <select class="form-control" id="rating" name="rating">
                                <option value="0">No Rating</option>
                                <option value="5">⭐⭐⭐⭐⭐ Excellent</option>
                                <option value="4">⭐⭐⭐⭐ Good</option>
                                <option value="3">⭐⭐⭐ Average</option>
                                <option value="2">⭐⭐ Poor</option>
                                <option value="1">⭐ Very Poor</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="feedback_text" class="form-label">Your Feedback:</label>
                            <textarea class="form-control" id="feedback_text" name="feedback_text" rows="3" placeholder="Write your feedback..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Feedback</button>
                    </form>
                </div>
            </div>
            <?php endif; ?>

            <!-- Display Feedback -->
            <h5 class="mb-3">All Feedback</h5>
            <?php
            $feedback_query = "SELECT f.*, u.name AS feedback_from_name FROM feedback f JOIN users u ON f.feedback_from = u.id WHERE f.feedback_to = ? ORDER BY f.created_at DESC";
            $feedback_stmt = $conn->prepare($feedback_query);
            $feedback_stmt->bind_param("i", $user_id);
            $feedback_stmt->execute();
            $feedback_result = $feedback_stmt->get_result();

            if ($feedback_result->num_rows > 0):
                while ($feedback_row = $feedback_result->fetch_assoc()): 
            ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-2 text-muted">
                                <a href="user_profile.php?user_id=<?php echo $feedback_row['feedback_from']; ?>" class="text-decoration-none">
                                    <?php echo htmlspecialchars($feedback_row['feedback_from_name']); ?>
                                </a>
                                <?php if ($feedback_row['rating'] > 0): ?>
                                    - <?php echo str_repeat('⭐', $feedback_row['rating']); ?>
                                <?php endif; ?>
                            </h6>
                            <p class="card-text"><?php echo htmlspecialchars($feedback_row['feedback_text']); ?></p>
                            <small class="text-muted"><?php echo htmlspecialchars($feedback_row['created_at']); ?></small>
                        </div>
                    </div>
            <?php 
                endwhile; 
            else: 
            ?>
                <p class="text-muted">No feedback yet.</p>
            <?php 
            endif; 
            $feedback_stmt->close();
            ?>
        </div>
    </div>
</div>

<?php
// Fetch user's projects
$query = "SELECT * FROM projects WHERE created_by = '$user_id'";
$result = $conn->query($query);
if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-light text-primary text-center">
            <h2 class="mb-0" style="color:#6C757D;">Posts</h2>
        </div>
        <div class="card-body">
            <table class="table table-hover table-bordered text-center">
                <thead style="background-color: #05548c; color: white;">
                    <tr>
                        <th>Category</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <a href="project_discussion.php?project_id=<?php echo $row['id']; ?>" class="text-decoration-none text-primary">
                                <?php echo htmlspecialchars($row['title']); ?>
                            </a>
                        </td>
                        <td><?php echo $row['description']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer text-muted text-center">
            Total Projects: <?php echo $result->num_rows; ?>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php
$conn->close();
?>
