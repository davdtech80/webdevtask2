<?php
// Connect to the database
$conn = new mysqli("localhost", "root", "", "dbwebdev");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the search query from the request
if (isset($_GET['query']) && !empty($_GET['query'])) {
    $query = $_GET['query'];
    
    // Sanitize the input to prevent SQL injection
    $query = "%" . $conn->real_escape_string($query) . "%";
    
    // SQL to search in article titles, content, and categories
    $sql = "SELECT * FROM articles WHERE title LIKE ? OR content LIKE ? OR category LIKE ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $query, $query, $query);
    
    // Execute the query and get results
    $stmt->execute();
    $result = $stmt->get_result();
    
    $response = [];
    while ($row = $result->fetch_assoc()) {
        $response[] = $row;
    }

    // Return the search results as JSON
    echo json_encode($response);
} else {
    // Return an empty response if no query is provided
    echo json_encode([]);
}

// Close the database connection
$conn->close();
?>
