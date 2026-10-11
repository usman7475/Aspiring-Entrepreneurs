<?php
// add_project.php
include 'database.php';
include 'navbar2.php';

if (!isset($_SESSION['user_id'])) {
header('Location: index.php');
exit();
}

$user_id = $_SESSION['user_id'];

// Handle deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_post_id'])) {
$delete_id = intval($_POST['delete_post_id']);
$stmt = $conn->prepare("DELETE FROM projects WHERE id = ? AND created_by = ?");
$stmt->bind_param("ii", $delete_id, $user_id);
$stmt->execute();
$stmt->close();
// Refresh to reflect deletion
header("Location: project.php");
exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title']) && isset($_POST['description'])) 
{
$title = $_POST['title'];
$description = $_POST['description'];

$stmt = $conn->prepare("INSERT INTO projects (title, description, created_by) VALUES (?, ?, ?)");
$stmt->bind_param("ssi", $title, $description, $user_id);

if ($stmt->execute()) {
$success_message = "Project added successfully!";
} else {
$error_message = "Error: " . $stmt->error;
}

$stmt->close();
}

// Fetch all projects created by the user
$query = "SELECT * FROM projects WHERE created_by = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Post</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<script src="https://cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>
</head>
<body class="bg-light">

<div class="container mt-5">
<h2 class="text-center mb-4">Add New Post</h2>

<?php if (isset($success_message)): ?>
<div class="alert alert-success"><?php echo $success_message; ?></div>

<?php elseif (isset($error_message)): ?>
<div class="alert alert-danger"><?php echo $error_message; ?></div>
<?php endif; ?>

<div class="card shadow mb-4">
<div class="card-body">
<form method="POST">

<div class="mb-3">
<label for="title" class="form-label">Post Category:</label>

<input type="text" name="title" id="title" class="form-control" required>

</div>
<div class="mb-3">
<label for="description" class="form-label">Description:</label>

<textarea name="description" id="description" class="form-control" rows="4" required>

</textarea>
</div>
<button type="submit" class="btn btn-primary w-100">Add</button>
</form>
</div>
</div>

<!-- Display the projects created by the user -->
<h3 class="mb-4">Your Post</h3>
<div class="list-group">

<?php while ($row = $result->fetch_assoc()): ?>
<div class="list-group-item d-flex justify-content-between align-items-start">
<div class="me-auto">
<a href="project_discussion.php?project_id=<?php echo $row['id']; ?>" class="text-decoration-none text-dark">
<strong><?php echo htmlspecialchars($row['title']); ?></strong>
<p class="mb-0"><?php echo $row['description']; ?></p>
</a>
</div>

<form method="POST" onsubmit="return confirm('Are you sure you want to delete this post?');">
<input type="hidden" name="delete_post_id" value="<?php echo $row['id']; ?>">
<button class="btn btn-sm btn-danger ms-2"><i class="bi bi-trash"></i></button>
</form>
</div>
<?php endwhile; ?>
</div>
</div>

<script>
CKEDITOR.replace('description', {
filebrowserUploadUrl: 'upload.php',
filebrowserUploadMethod: 'form'
});
</script>
<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $stmt->close(); ?>
