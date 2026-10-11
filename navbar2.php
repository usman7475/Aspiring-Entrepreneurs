<?php  
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>An Interactive Web Portal for Aspiring Entrepreneurs</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">

    <style>
        /* Custom styles for title spacing */
        .page-title {
            margin: 100px 5px; /* Adjust margin as needed */
                    }
  
        .custom-navbar {

            box-shadow: 0 3px 0 rgba(0, 0, 0, 0.1);
            border-bottom: 1px darkgray; /* Border added here  */

                        }

img{
width: 96px !important;
height: 70px !important;
}

</style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light custom-navbar">
<div class="container-fluid">

<a class="navbar-brand  font-weight-bold" href="dashboard.php" style="font-size: 25px ;color: #05548c;" >An Interactive Web Portal for Aspiring Entrepreneurs</a>



<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
<span class="navbar-toggler-icon"></span>
</button>
<div class="collapse navbar-collapse" id="navbarNav">
<ul class="navbar-nav ms-auto"> <!-- Use 'ms-auto' for right alignment -->

              
<?php if (isset($_SESSION['user_id'])): ?>
  <li class="nav-item"><a class="nav-link" href="dashboard.php">Home</a></li>

<?php if (isset($_SESSION['name']) && $_SESSION['name'] === 'Admin'): ?>
    <li class="nav-item"><a class="nav-link" href="users.php">User Management</a></li>
<?php endif; ?>

                  
  <?php if (isset($_SESSION['role']) && $_SESSION['role'] !== 'User'): ?>
<li class="nav-item"><a class="nav-link" href="project.php">Post</a></li>
    <?php endif; ?>
<li class="nav-item"><a class="nav-link" href="discussion.php">Discussions</a></li>
<li class="nav-item"><a class="nav-link" href="update_profile.php">Profile</a></li>

<!-- Search Button -->
<form class="d-flex ms-auto" action="discussion.php" method="get">
    <input class="form-control me-2" type="search" name="search" placeholder="Search..." aria-label="Search" required>
    <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
</form>

<li class="nav-item"><a class="nav-link" href="logout.php" style="font-weight:bold;" >Logout</a></li>
            

    <?php else: ?>
        <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php">Login</a></li>
    <?php endif; ?>

            </ul>
        </div>
    </div>
</nav>



<!-- jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
