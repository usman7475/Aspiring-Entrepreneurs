<?php
// vote.php
include 'database.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$discussion_id = $_GET['discussion_id'];
$type = $_GET['type'];
$project_id = $_GET['project_id']; // Get project ID from URL

// Check if the vote type is valid
if ($type !== 'upvote' && $type !== 'downvote') {
    die("Invalid vote type.");
}

// Check if the user has already voted on this discussion
$stmt = $conn->prepare("SELECT * FROM votes WHERE discussion_id = ? AND user_id = ?");
$stmt->bind_param("ii", $discussion_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // User has already voted, update the existing vote
    $stmt = $conn->prepare("UPDATE votes SET vote_type = ? WHERE discussion_id = ? AND user_id = ?");
    $stmt->bind_param("sii", $type, $discussion_id, $user_id);
    $stmt->execute();
} else {
    // User has not voted yet, insert a new vote
    $stmt = $conn->prepare("INSERT INTO votes (discussion_id, user_id, vote_type) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $discussion_id, $user_id, $type);
    $stmt->execute();
}

$stmt->close();
$conn->close();

// Redirect back to the discussion page with the project ID
header("Location: project_discussion.php?project_id=" . $project_id);
exit();
?>
