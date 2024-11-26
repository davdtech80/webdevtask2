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
  
</head>
<body>
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
     <div class="form-contact-l">
    <form class="form" method="POST" action="login.php">
        <div class="form-group">
            <p class="p-log"> LOG IN </p>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Email" required>
    </div>

        <div class="form-group">
            <label for="password">password</label>
            <input type="password" id="password" name="password" placeholder="password" required>
        </div>

        
        

        <div>  

        <button type="submit">Submit</button>
    
        </div>

    </form>



<?php
// Start the session (only once)
session_start();

// Connect to the database
$conn = new mysqli("localhost", "root", "", "dbwebdev");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission for login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare a SQL query to check user credentials
    $sql = "SELECT * FROM login WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Fetch the user data
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Password matches, set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];

            // Set a cookie after login verification
            setcookie("user_email", $user['email'], time() + 3600, "/"); // Cookie expires in 1 hour

            // Redirect to the dashboard or profile page
            header("Location: dashboard.php"); // Or index.php where the search box appears
            exit;
        } else {
            echo "Invalid email or password.";
        }
    } else {
        echo "Invalid email or password.";
    }

    $stmt->close();
}

$conn->close();
?>



    
         </div>

            <a href="https://www.facebook.com/profile.php?id=61567094392961" target="_blank">
             <button>👍 Like us on Facebook</button>
        </div>

         

 
</body>
</html>