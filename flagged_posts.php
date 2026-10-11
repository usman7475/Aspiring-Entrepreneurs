<?php 
include 'database.php';
include 'navbar2.php';

$is_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'Admin';
if (!$is_admin) {
    echo "Access denied.";
    exit();
}

// Handle deletion of flagged post and related discussion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_flagged_id'])) {
    $discussion_id = intval($_POST['delete_flagged_id']);

    // First delete from flagged_posts
    $stmt1 = $conn->prepare("DELETE FROM flagged_posts WHERE discussion_id = ?");
    $stmt1->bind_param("i", $discussion_id);
    $stmt1->execute();
    $stmt1->close();

    // Then delete from discussions
    $stmt2 = $conn->prepare("DELETE FROM discussions WHERE id = ?");
    $stmt2->bind_param("i", $discussion_id);
    $stmt2->execute();
    $stmt2->close();
}

// Get flagged posts
$query = "
    SELECT f.id AS flag_id, d.id AS discussion_id, d.message, u.name AS flagged_by
    FROM flagged_posts f
    JOIN discussions d ON f.discussion_id = d.id
    JOIN users u ON f.flagged_by = u.id
    ORDER BY f.flagged_at DESC";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Flagged Posts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h3 class="mb-4">Flagged Posts</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Message</th>
                <th>Flagged By</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['message']; ?></td>
                    
                    <td><?php echo htmlspecialchars($row['flagged_by']); ?></td>
                    <td>
                        <form method="POST" onsubmit="return confirm('Are you sure you want to delete this discussion?');">
                            <input type="hidden" name="delete_flagged_id" value="<?php echo $row['discussion_id']; ?>">
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php  include 'footer.php'; ?>
</body>
</html>

<?php $conn->close(); ?>
