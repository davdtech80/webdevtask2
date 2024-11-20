<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
  <head>
          <title>WeLoveParenting Malta - Home</title>
          
          <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
      <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

    
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link href="style.css?version=8" rel="stylesheet">
      <?php
      header("Cache-Control: no-cache, must-revalidate");
      header("Expires: Sat, 1 Jan 2040 00:00:00 GMT");
      ?>
  <div class="jumbotron text-center">
  
  </div>
</head>
<body>
  <header>
<div class="list-r">
                
       <nav>
         <ul class="nav-menu-r">
          <li><a href="index.php"><span>Home</span></a></li>                 
          <li><a href="about.php"><span>About US</span></a></li>
          <li><a href="services.php"><span>Register</span></a></li>
          <li><a href="contactus.php"><span>Contact US</span></a></li>
          <li><a href="login.php"><span>Log In</span></a></li>
          
          </ul>
     </nav>
  </div>
    </header>
     
    



<?php 
// Start the session
session_start();

// Connect to the database
$conn = new mysqli("localhost", "root", "", "dbwebdev");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Variable to store the message
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Simple password validation check
    if (strlen($password) < 8) {
        $message = '<div class="alert alert-danger">Password must be at least 8 characters long.</div>';
    } elseif (!preg_match('/[A-Z]/', $password)) {
        $message = '<div class="alert alert-danger">Password must include at least one uppercase letter.</div>';
    } elseif (!preg_match('/[a-z]/', $password)) {
        $message = '<div class="alert alert-danger">Password must include at least one lowercase letter.</div>';
    } elseif (!preg_match('/\d/', $password)) {
        $message = '<div class="alert alert-danger">Password must include at least one number.</div>';
    } else {
        // Check if email already exists in the database
        $checkEmailSql = "SELECT * FROM login WHERE email = ?";
        $checkEmailStmt = $conn->prepare($checkEmailSql);
        $checkEmailStmt->bind_param("s", $email);
        $checkEmailStmt->execute();
        $result = $checkEmailStmt->get_result();

        if ($result->num_rows > 0) {
            // If email already exists, display an error message
            $message = '<div class="alert alert-danger">Email already in use. Please use a different email.</div>';
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert user into the login table
            $sql = "INSERT INTO login (email, password) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $email, $hashed_password);

            if ($stmt->execute()) {
                // Success message
                $message = '<div class="alert alert-success">Registration successful. You can now <a href="login.php">login</a>.</div>';
            } else {
                // Error message
                $message = '<div class="alert alert-danger">Error: ' . $stmt->error . '</div>';
            }

            $stmt->close();
        }

        $checkEmailStmt->close();
    }
}

$conn->close();
?>

<title>Registration Form</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    margin: 50px;
    background-color: #f4f4f9; /* Slight background color for contrast */
}

.form-container {
    width: 100%;
    margin: auto;
    padding: 200px;
    border: 1px solid #ccc;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    background-color: white;
}

.form-container input {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 14px;
    box-sizing: border-box;
}

.form-container label {
    font-weight: bold;
    margin-bottom: 5px;
    display: block;
}

.form-container .submit-btn {
    width: 100%;
    padding: 12px;
    background-color: #28a745; /* Green color */
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.form-container .submit-btn:hover {
    background-color: #218838; /* Darker green when hovering */
}

.alert {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 5px;
    text-align: center;
    font-size: 14px;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

    </style>
</head>
<body>

    <div class="form-container">
        <h2>Register</h2>

        <?php echo $message; ?>

        <form method="POST" action="">
            <input type="email" name="email" required placeholder="Enter your email">
            <input type="password" name="password" required placeholder="Enter your password">
            <input type="submit" value="Register">
        </form>
    </div>





</body>
</html>





































	