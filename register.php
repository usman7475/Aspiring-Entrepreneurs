<?php

// Include database connection
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') 

{
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];
  
    
    // Set status based on role
    $status = ($role === 'User') ? 'active' : 'Pending';

    // Prepare the SQL statement to insert the user into the database
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, status) VALUES (?, ?, ?, ?, ?)");

    $stmt->bind_param("sssss", $name, $email, $password, $role, $status);

    if ($stmt->execute()) {
 

        // $success_message = "Registration successful! You can now log in.";
        // JavaScript to show a success alert and redirect
        echo "<script>
                alert('Registration successful!');
                window.location.href = 'register.php'; // Redirect to the register
              </script>";
    } else {
        // If an error occurs during registration
        $error_message = "Error: " . $stmt->error;
    }
    
    $stmt->close(); 
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/styles.css"> <!-- Custom CSS -->
</head>
<body class="bg-light">

<!-- Navigation Bar -->
<?php include 'navbar2.php'; ?>

<div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="card shadow" style="width: 400px;">
        <div class="card-body">
            <h5 class="card-title text-center">Register</h5>
            
            <?php if (isset($success_message)): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $success_message; ?>
                </div>
            <?php elseif (isset($error_message)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

<form method="POST" action="">


<div class="mb-3">
<label for="name" class="form-label">Name:</label>
<div class="input-group">

<span class="input-group-text"><i class="bi bi-person-fill"></i></span>

<input type="text" class="form-control" id="name" name="name" required>
</div>
</div>


<div class="mb-3">
<label for="email" class="form-label">Email:</label>
<div class="input-group">
<span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
<input type="email" class="form-control" id="email" name="email" required>
</div>
</div>


<div class="mb-3">
<label for="password" class="form-label">Password:</label>
<div class="input-group">
<span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
<input type="password" class="form-control" id="password" name="password" required>
</div>
</div>


<div class="mb-3">
<label for="role" class="form-label">Register As:</label>
<div class="input-group">
<span class="input-group-text"><i class="bi bi-person-badge-fill"></i></span>
<select class="form-control" id="role" name="role" required>
<option value="">-- Select Role --</option>
<option value="User">User</option>
<option value="Agent">Agent</option>
</select>
</div>
</div>


<button type="submit" class="btn btn-primary w-100">Register</button>
</form>

<p class="mt-3 text-center">Already have an account? <a href="index.php">Login here</a>.</p>
</div>
</div>
</div>


<?php  include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
