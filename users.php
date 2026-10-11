<?php

include 'database.php';
include 'navbar2.php';

// Fetch users for moderation, excluding those with the role 'Admin'
$query = "SELECT * FROM users WHERE role != 'Admin'";
$result = $conn->query($query);

// Handle status update
if (isset($_GET['update_status'])) {
$user_id = $_GET['update_status'];
$new_status = $_GET['status'];

$stmt = $conn->prepare("UPDATE users SET status = ? WHERE id = ? AND role != 'Admin'");
$stmt->bind_param("si", $new_status, $user_id);
$stmt->execute();
$stmt->close();

header("Location: users.php");
exit();
}

// Handle deletion of a user
if (isset($_GET['delete_id'])) {
$user_id = $_GET['delete_id'];

// Prepare the delete statement
$stmt = $conn->prepare("DELETE FROM users WHERE id = ? AND role != 'Admin'");
$stmt->bind_param("i", $user_id);
$stmt->execute();


// Redirect back to the admin dashboard
header("Location: users.php");
exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    img{
        width: 50px !important;
        height: 50px !important;
        border-radius: 50%;
        object-fit: cover;
    }
</style>
</head>
<body class="bg-light">
<div class="container mt-5">

<h3 class="text-center mb-4">Website Users</h3>
<div class="card">
<div class="card-body">
<table class="table table-striped table-hover">
<thead>
<tr>
<th>#</th>
<th>Name</th>
<th>Email</th>

<th>Status</th>
<th>Profile Picture</th>
<th>Bio</th>
<th>Role</th>
<th>Created At</th>
<th>Action</th>
</tr>
</thead>
<tbody>
<?php while ($row = $result->fetch_assoc()): ?>
<tr>
<td><?php echo htmlspecialchars($row['id']); ?></td>
<td><?php echo htmlspecialchars($row['name']); ?></td>
<td><?php echo htmlspecialchars($row['email']); ?></td>

<td>
<span class="badge <?php echo ($row['status'] === 'active') ? 'bg-success' : 'bg-warning'; ?>">
<?php echo htmlspecialchars($row['status']); ?>
</span>
</td>
<td>
<?php if (!empty($row['profile_picture'])): ?>
<img src="<?php echo htmlspecialchars($row['profile_picture']); ?>" alt="Profile" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
<?php else: ?>
<span class="text-muted">No Picture</span>
<?php endif; ?>
</td>
<td><?php echo htmlspecialchars(substr($row['bio'] ?? '', 0, 50)); ?></td>
<td><?php echo htmlspecialchars($row['role']); ?></td>
<td><?php echo htmlspecialchars($row['created_at'] ?? 'N/A'); ?></td>
<td>
<?php if ($row['status'] === 'active'): ?>
<a href="?update_status=<?php echo $row['id']; ?>&status=disable" class="btn btn-warning btn-sm">Disable</a>
<?php else: ?>
<a href="?update_status=<?php echo $row['id']; ?>&status=active" class="btn btn-success btn-sm">Activate</a>
<?php endif; ?>
<a href="?delete_id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>
</div>
</div>
<?php  include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
