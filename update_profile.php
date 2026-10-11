<?php
// update_profile.php
include 'database.php';
include 'navbar2.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch current user data
$stmt = $conn->prepare("SELECT name, email, bio, profile_picture FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $bio = $_POST['bio'];

    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $profile_picture = "uploads/" . basename($_FILES['profile_picture']['name']);
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], $profile_picture);

        $stmt = $conn->prepare("UPDATE users SET name = ?, bio = ?, profile_picture = ? WHERE id = ?");
        $stmt->bind_param("sssi", $name, $bio, $profile_picture, $user_id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET name = ?, bio = ? WHERE id = ?");
        $stmt->bind_param("ssi", $name, $bio, $user_id);
    }

    if ($stmt->execute()) {
        $success_message = "Profile updated successfully!";
    } else {
        $error_message = "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-body">
            <h2 class="card-title text-center">Update Profile</h2>

            <?php if (isset($success_message)): ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php elseif (isset($error_message)): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" class="mt-4">
                <div class="mb-3">
                    <label for="name" class="form-label">Name:</label>
                    <input type="text" class="form-control" name="name" id="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                </div>

                <div class="mb-3">
                    <label for="bio" class="form-label">Bio:</label>
                    <textarea name="bio" id="bio" class="form-control" rows="4" required><?php echo htmlspecialchars($user['bio']); ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Current Profile Picture:</label><br>
                    <?php if (!empty($user['profile_picture'])): ?>
                        <img src="<?php echo htmlspecialchars($user['profile_picture']); ?>" class="img-fluid rounded" style="max-width: 200px;">
                    <?php else: ?>
                        <p>No profile picture uploaded.</p>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="profile_picture" class="form-label">Upload New Profile Picture:</label>
                    <input type="file" class="form-control" name="profile_picture" id="profile_picture" accept="image/*">
                </div>

                <button type="submit" class="btn btn-primary w-100">Update Profile</button>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
