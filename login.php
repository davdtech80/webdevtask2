<!DOCTYPE html>
<html lang="en">

<head>  
    <title>WeLoveParenting Malta - Home</title>
    <meta charset="utf-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css?version=8" rel="stylesheet">
    <?php
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Sat, 1 Jan 2040 00:00:00 GMT");

        // Initialize message variable
        $message = "";

        // Define constants for brute-force mitigation
        define('MAX_FAILED_ATTEMPTS', 5); // Max failed login attempts
        define('LOCKOUT_DURATION', 1800); // Lockout duration in seconds (e.g., 30 minutes)

        // Check if the form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Collect the form data
            $email = htmlspecialchars($_POST['email']);
            $password = htmlspecialchars($_POST['password']);

            // Database connection details
            $servername = "localhost";
            $username = "root";
            $password_db = "";
            $dbname = "dbwebdev";

            // Create a connection to the database
            $conn = new mysqli($servername, $username, $password_db, $dbname);

            // Check for a connection error
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Prepare the SQL statement to check user credentials
            $sql = "SELECT * FROM login WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                // Fetch the user data
                $user = $result->fetch_assoc();

                // Check if account is locked
                if ($user['lockout_time'] && strtotime($user['lockout_time']) > time()) {
                    // Account is locked, notify the user
                    $message = '<div class="alert alert-danger">Your account is locked due to too many failed login attempts. Please try again after ' . 
                                date('H:i:s', strtotime($user['lockout_time'])) . '.</div>';
                } else {
                    // Account is not locked, verify the password
                    if (password_verify($password, $user['password'])) {
                        // Reset failed attempts and lockout time on successful login
                        $stmt = $conn->prepare("UPDATE login SET failed_attempts = 0, lockout_time = NULL WHERE email = ?");
                        $stmt->bind_param("s", $email);
                        $stmt->execute();

                        // Set session variables
                        session_start();
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['user_email'] = $user['email'];

                        // Set a cookie for the user email
                        setcookie('user_email', $user['email'], time() + (86400 * 30), "/"); // Cookie expires in 30 days

                        // Redirect to the dashboard or profile page
                        header("Location: dashboard.php");
                        exit;
                    } else {
                        // Incorrect password, increase failed attempts
                        $failed_attempts = $user['failed_attempts'] + 1;
                        $stmt = $conn->prepare("UPDATE login SET failed_attempts = ?, last_failed_attempt = NOW() WHERE email = ?");
                        $stmt->bind_param("is", $failed_attempts, $email);
                        $stmt->execute();

                        if ($failed_attempts >= MAX_FAILED_ATTEMPTS) {
                            // Lock the account for the specified duration
                            $lockout_time = date('Y-m-d H:i:s', time() + LOCKOUT_DURATION);
                            $stmt = $conn->prepare("UPDATE login SET failed_attempts = ?, lockout_time = ? WHERE email = ?");
                            $stmt->bind_param("iss", $failed_attempts, $lockout_time, $email);
                            $stmt->execute();
                            // Show lockout message in an alert
                            $message = '<div class="alert alert-danger">Your account has been locked due to too many failed login attempts. Please try again after 30 minutes.</div>';
                        } else {
                            // Display the remaining attempts
                            $message = '<div class="alert alert-warning">Incorrect email or password. You have ' . (MAX_FAILED_ATTEMPTS - $failed_attempts) . ' attempts remaining.</div>';
                        }
                    }
                }
            } else {
                // User not found, show error message
                $message = '<div class="alert alert-danger">Invalid email or password.</div>';
            }

            // Close the statement and connection
            $stmt->close();
            $conn->close();
        }
    ?>
</head>

<body>
 <div class="jumbotron text-center">
  
  </div>
<header>
    <div class="list">
        <nav>
            <ul class="nav-menu">
                <li><a href="index.php"><span>Home</span></a></li>                 
                <li><a href="about.php"><span>About US</span></a></li>
                <li><a href="services.php"><span>Register</span></a></li>
                <li><a href="contactus.php"><span>Contact US</span></a></li>
                <li><a href="login.php"><span>Log In</span></a></li>
            </ul>
        </nav>
    </div>
</header>

<!-- Display message at the top -->
<?php
    // Show message if available
    if (!empty($message)) {
        echo $message;
    }
?>

<div class="form-contact-l">
    <form class="form" method="POST" action="">
        <div class="form-group">
            <p class="p-log">LOG IN</p>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter Email" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter Password" required>
        </div>

        <div>
            <button type="submit">Submit</button>
        </div>
    </form>

    <a href="https://www.facebook.com/profile.php?id=61567094392961" target="_blank">
        <button>👍 Like us on Facebook</button>
    </a>
</div>

</body>
</html>
