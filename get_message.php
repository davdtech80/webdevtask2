<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbwebdev";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user ID is set
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $userId = $_GET['id'];

    // Sanitize the user ID
    $userId = (int)$userId;

    // Prepare the query to get the message of the selected user
    $sql = "SELECT message FROM submit WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $response = [];
    if ($result->num_rows > 0) {
        // Fetch the message
        $row = $result->fetch_assoc();
        $response['message'] = $row['message'];
    } else {
        // No message found
        $response['message'] = 'No message found for this user.';
    }

    // Return the message as JSON
    echo json_encode($response);

    // Close the statement
    $stmt->close();
} else {
    // Return an error if the user ID is not provided
    echo json_encode(['error' => 'No user ID provided.']);
}

// Close the database connection
$conn->close();
?>
