<?php 
include 'database.php';
include 'navbar2.php';

$user_id = $_SESSION['user_id'];
$is_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'Admin';

$project_id = isset($_GET['project_id']) ? (int)$_GET['project_id'] : 0;
if ($project_id <= 0) {
echo "Invalid project ID.";
exit();
}

$project_query = "SELECT p.title, p.created_by, u.name AS creator_name FROM projects p JOIN users u ON p.created_by = u.id WHERE p.id = ?";
$project_stmt = $conn->prepare($project_query);
$project_stmt->bind_param("i", $project_id);
$project_stmt->execute();
$project_stmt->bind_result($project_title, $project_creator_id, $project_creator_name);
$project_stmt->fetch();
$project_stmt->close();

if (!$project_title) {
echo "Project not found.";
exit();
}

// New post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
$message = $_POST['message'];
$stmt = $conn->prepare("INSERT INTO discussions (project_id, user_id, message) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $project_id, $user_id, $message);
$stmt->execute();
$stmt->close();
}

// Admin delete discussion (with foreign key constraint handling)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_discussion_id']) && $is_admin) {
$discussion_id = intval($_POST['delete_discussion_id']);

// Delete flagged posts first
$delete_flags_stmt = $conn->prepare("DELETE FROM flagged_posts WHERE discussion_id = ?");
$delete_flags_stmt->bind_param("i", $discussion_id);
$delete_flags_stmt->execute();
$delete_flags_stmt->close();

// Then delete discussion
$delete_stmt = $conn->prepare("DELETE FROM discussions WHERE id = ?");
$delete_stmt->bind_param("i", $discussion_id);
$delete_stmt->execute();
$delete_stmt->close();

$success_message = "Discussion and its flags deleted successfully.";
}

// Handle flag action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['flag_discussion_id'])) {
$discussion_id = intval($_POST['flag_discussion_id']);

$check_stmt = $conn->prepare("SELECT id FROM flagged_posts WHERE discussion_id = ? AND flagged_by = ?");
$check_stmt->bind_param("ii", $discussion_id, $user_id);
$check_stmt->execute();
$check_stmt->store_result();

if ($check_stmt->num_rows === 0) {
$flag_stmt = $conn->prepare("INSERT INTO flagged_posts (discussion_id, flagged_by) VALUES (?, ?)");
$flag_stmt->bind_param("ii", $discussion_id, $user_id);
$flag_stmt->execute();
$flag_stmt->close();
$success_message = "Post flagged successfully.";
} else {
$error_message = "You've already flagged this post.";
}
$check_stmt->close();
}

// Fetch discussions with votes
$query = "
SELECT d.*, u.name, u.id AS user_id,
SUM(CASE WHEN v.vote_type = 'upvote' THEN 1 ELSE 0 END) AS upvotes,
SUM(CASE WHEN v.vote_type = 'downvote' THEN 1 ELSE 0 END) AS downvotes
FROM discussions d 
JOIN users u ON d.user_id = u.id 
LEFT JOIN votes v ON d.id = v.discussion_id
WHERE d.project_id = ?
GROUP BY d.id";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $project_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Discussion Forum</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<script src="https://cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>
<style>
.icon-btn {
border: none;
background: none;
padding: 5px;
color: #555;
}
.icon-btn:hover {
color: #000;
}
.message-card {
border: 1px solid #ddd;
border-radius: 10px;
margin-bottom: 15px;
padding: 15px;
background-color: #fff;
}
</style>
</head>
<body class="bg-light">

<div class="container mt-5">
<h2 class="text-center mb-4">Discussion Forum: <?php echo htmlspecialchars($project_title); ?> - <a href="user_profile.php?user_id=<?php echo $project_creator_id; ?>" class="text-decoration-none">by <?php echo htmlspecialchars($project_creator_name); ?></a></h2>

<?php if (isset($success_message)): ?>
<div class="alert alert-success"><?php echo $success_message; ?></div>
<?php endif; ?>
<?php if (isset($error_message)): ?>
<div class="alert alert-danger"><?php echo $error_message; ?></div>
<?php endif; ?>

<!-- Display discussions -->
<?php while ($row = $result->fetch_assoc()): ?>
<div class="message-card">
<p><strong><a href="user_profile.php?user_id=<?php echo $row['user_id']; ?>"><?php echo htmlspecialchars($row['name']); ?></a>:</strong></p>
<p><?php echo $row['message']; ?></p>

<!-- Icons Section -->
<div class="d-flex align-items-center gap-3 mt-2">
<a href="vote.php?discussion_id=<?php echo $row['id']; ?>&type=upvote&project_id=<?php echo $project_id; ?>" class="icon-btn" title="Upvote">
<i class="fa-solid fa-thumbs-up text-success"></i> <?php echo $row['upvotes'] ?? 0; ?>
</a>
<a href="vote.php?discussion_id=<?php echo $row['id']; ?>&type=downvote&project_id=<?php echo $project_id; ?>" class="icon-btn" title="Downvote">
<i class="fa-solid fa-thumbs-down text-primary"></i> <?php echo $row['downvotes'] ?? 0; ?>
</a>

<!-- Flag Icon (Always visible) -->
<form method="POST" class="d-inline">
<input type="hidden" name="flag_discussion_id" value="<?php echo $row['id']; ?>">
<button type="submit" class="icon-btn" title="Flag">
<i class="fa-solid fa-flag text-danger"></i>
</button>
</form>

<!-- Delete Icon (Only for admin) -->
<?php if ($is_admin): ?>
<form method="POST" class="d-inline">
<input type="hidden" name="delete_discussion_id" value="<?php echo $row['id']; ?>">
<button type="submit" class="icon-btn" title="Delete">
<i class="fa-solid fa-trash text-warning"></i>
</button>
</form>
<?php endif; ?>
</div>
</div>
<?php endwhile; ?>

<!-- Post new message -->
<div class="card mb-5">
<div class="card-body">
<form method="POST">
<div class="mb-3">
<textarea name="message" id="message" class="form-control" placeholder="Post a message" required></textarea>
</div>
<button type="submit" class="btn btn-primary">Post</button>
</form>
</div>
</div>
</div>

<script>
CKEDITOR.replace('message', {
filebrowserUploadUrl: 'upload.php',
filebrowserUploadMethod: 'form'
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<?php include 'footer.php'; ?>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
