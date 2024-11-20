<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch the user's email from the session
$user_email = $_SESSION['user_email'];

// Connect to the database
$conn2 = new mysqli("localhost", "root", "", "dbwebdev");

// Check connection to the database
if ($conn2->connect_error) {
    die("Connection to the database failed: " . $conn2->connect_error);
}

// Prepare a query to get user details from the 'submit' table using the email from session
$sql = "SELECT * FROM submit WHERE email = ?";
$stmt = $conn2->prepare($sql);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();

// Initialize error message variable
$error_message = '';

// Check if user details were found
if ($result->num_rows == 1) {
    // Fetch the user details
    $user_details = $result->fetch_assoc();
} else {
    // Set the error message if user details are not found
    $error_message = "User details not found.";
}

// Handle password change
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_password'])) {
    $new_password = $_POST['new_password'];

    // Password validation check
    if (strlen($new_password) < 8) {
        echo "Password must be at least 8 characters long.";
    } elseif (!preg_match('/[A-Z]/', $new_password)) {
        echo "Password must include at least one uppercase letter.";
    } elseif (!preg_match('/[a-z]/', $new_password)) {
        echo "Password must include at least one lowercase letter.";
    } elseif (!preg_match('/\d/', $new_password)) {
        echo "Password must include at least one number.";
    } else {
        // Hash the password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update password in 'login' table
        $update_sql = "UPDATE login SET password = ? WHERE email = ?";
        $update_stmt = $conn2->prepare($update_sql);
        $update_stmt->bind_param("ss", $hashed_password, $user_email);

        if ($update_stmt->execute()) {
            echo "Password updated successfully!";
        } else {
            echo "Error updating password: " . $update_stmt->error;
        }
    }
}

// Handle message update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_message'])) {
    $new_message = $_POST['new_message'];

    // Update message in 'submit' table
    $update_message_sql = "UPDATE submit SET message = ? WHERE email = ?";
    $update_message_stmt = $conn2->prepare($update_message_sql);
    $update_message_stmt->bind_param("ss", $new_message, $user_email);

    if ($update_message_stmt->execute()) {
        echo "Message updated successfully!";
    } else {
        echo "Error updating message.";
    }
}

// Handle account deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_account'])) {
    // Delete user from 'login' table
    $delete_login_sql = "DELETE FROM login WHERE email = ?";
    $delete_login_stmt = $conn2->prepare($delete_login_sql);
    $delete_login_stmt->bind_param("s", $user_email);

    // Delete user from 'submit' table
    $delete_submit_sql = "DELETE FROM submit WHERE email = ?";
    $delete_submit_stmt = $conn2->prepare($delete_submit_sql);
    $delete_submit_stmt->bind_param("s", $user_email);

    // Execute the deletion queries
    if ($delete_login_stmt->execute() && $delete_submit_stmt->execute()) {
        echo "Account deleted successfully!";
        session_destroy(); // Log out the user
        header("Location: login.php");
        exit;
    } else {
        echo "Error deleting account.";
    }
}

// Close the database connection
$conn2->close();
?>

<!-- HTML Part -->
<!-- HTML Part -->
<title>Dashboard</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f9f9f9;
        margin: 0;
        padding: 0;
    }

    /* Adjust the form container to create space for error message */
    .container {
        max-width: 600px;
        margin: 50px auto;
        padding: 20px;
        background-color: #fff;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-radius: 8px;
        position: relative;
        margin-top: 80px;
    }

    h1 {
        color: #333;
        text-align: center;
    }

    h2 {
        color: #555;
        margin-bottom: 20px;
    }

    p {
        font-size: 16px;
        color: #333;
        line-height: 1.6;
        margin-bottom: 10px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    label {
        font-size: 14px;
        color: #333;
    }

    input[type="password"], input[type="text"] {
        width: 100%;
        padding: 8px;
        margin-top: 5px;
        box-sizing: border-box;
    }

    .update-btn, .toggle-btn, .delete-btn {
        display: inline-block;
        background-color: #007bff;
        color: white;
        padding: 10px 20px;
        text-align: center;
        border-radius: 5px;
        text-decoration: none;
        margin-top: 10px;
        font-size: 16px;
        cursor: pointer;
    }

    .delete-btn {
        background-color: #dc3545;
    }

    .delete-btn:hover {
        background-color: #c82333;
    }

    .update-btn:hover, .toggle-btn:hover {
        background-color: #0056b3;
    }

    .hidden {
        display: none;
    }

    /* Styles for the "User not found" box */
    .error-message {
        position: fixed;
        top: 10px;
        right: 10px;
        background-color: #f8d7da;
        color: #721c24;
        padding: 15px;
        border-radius: 5px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        font-size: 16px;
        z-index: 1000;
    }
</style>

</head>
<body>

    <!-- Display error message if user is not found -->
    <?php if (!empty($error_message)): ?>
        <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
    <?php endif; ?>

    <div class="container">
        <h1>Welcome to your Dashboard!</h1>

        <!-- Display the Search Box if the user is logged in -->
        <h2>Search Users</h2>
        <input id="search" type="text" placeholder="Enter name or surname" onkeyup="searchSuggestions()">
        <button onclick="searchUserMessage()">Get Message</button>
        <div id="suggestions"></div>

        <h2>Your Details:</h2>
        <?php if (!empty($user_details)): ?>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($user_details['name']); ?></p>
            <p><strong>Surname:</strong> <?php echo htmlspecialchars($user_details['surname']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user_details['email']); ?></p>
            <p><strong>Mobile:</strong> <?php echo htmlspecialchars($user_details['mobile']); ?></p>
            <p><strong>Message:</strong> <?php echo htmlspecialchars($user_details['message']); ?></p>
        <?php endif; ?>

        <!-- Button to toggle password form visibility -->
        <button class="toggle-btn" onclick="togglePasswordForm()">Click to change password</button>

        <!-- Hidden form to change password -->
        <form method="POST" class="hidden" id="passwordForm">
            <h2>Change Password</h2>
            <div class="form-group">
                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>
            <button type="submit" class="update-btn">Update Password</button>
        </form>

        <!-- Form to change message -->
        <h2>Update Message</h2>
        <form method="POST">
            <div class="form-group">
                <label for="new_message">New Message:</label>
                <input type="text" id="new_message" name="new_message" required>
            </div>
            <button type="submit" class="update-btn">Save</button>
        </form>

        <!-- Form to delete account -->
        <h2>Delete Account</h2>
        <form method="POST">
            <button type="submit" name="delete_account" class="delete-btn" onclick="return confirm('Are you sure you want to delete your account?');">Delete My Account</button>
        </form>

        <a href="login.php" class="update-btn">Log Out</a>
    </div>

    <script>
        // JavaScript to toggle password form visibility
        function togglePasswordForm() {
            var form = document.getElementById("passwordForm");
            if (form.classList.contains("hidden")) {
                form.classList.remove("hidden");
            } else {
                form.classList.add("hidden");
            }
        }
    </script>

</body>
</html>
