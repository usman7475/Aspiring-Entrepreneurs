<?php
// discussion.php
include 'database.php';
include 'navbar2.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Search functionality
$searchTerm = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

if (!empty($searchTerm)) {
    $query = "SELECT p.*, u.id AS creator_id, u.name AS creator_name 
              FROM projects p 
              JOIN users u ON p.created_by = u.id 
              WHERE p.title LIKE '%$searchTerm%' OR p.description LIKE '%$searchTerm%' OR u.name LIKE '%$searchTerm%'";
} else {
    $query = "SELECT p.*, u.id AS creator_id, u.name AS creator_name FROM projects p JOIN users u ON p.created_by = u.id";
}

$result = $conn->query($query);
if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discussion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
 
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-light text-primary text-center">
            <h2 class="mb-0" style="color:#6C757D;">Discussions</h2>
            <?php if (!empty($searchTerm)): ?>
                <h6 class="mt-2 text-muted fst-italic">Search Results for: "<?php echo htmlspecialchars($searchTerm); ?>"</h6>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <table class="table table-hover table-bordered text-center">
                <thead style="background-color: #05548c; color: white;">
                    <tr>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Created By</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <a href="project_discussion.php?project_id=<?php echo $row['id']; ?>" class="text-decoration-none text-primary">
                                    <?php echo htmlspecialchars($row['title']); ?>
                                </a>
                            </td>
                            <td><?php echo $row['description']; ?></td>
                            <td><a href="user_profile.php?user_id=<?php echo $row['creator_id']; ?>" class="text-decoration-none text-primary"><?php echo htmlspecialchars($row['creator_name']); ?></a></td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="3" class="text-danger">No results found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer text-muted text-center">
            Total Posts: <?php echo $result->num_rows; ?>
        </div>
    </div>
</div>

<?php
include 'footer.php';
$conn->close();
?>
