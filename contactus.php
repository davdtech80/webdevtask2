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

<div class="form-contact">
    <form class="form" method="POST" action="">
        <p class="p-us"> CONTACT US</p>
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" placeholder="Name" required>
        </div>

        <div class="form-group">
            <label for="surname">Surname</label>
            <input type="text" id="surname" name="surname" placeholder="Surname" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
        </div>

        <div class="form-group">
            <label for="mobile">Mobile No</label>
            <input type="number" id="mobile" name="mobile" placeholder="Enter your mobile" required>
        </div>

        <div class="form-group">
            <label for="message">Message</label>
            <textarea id="message" name="message" rows="4" placeholder="Write your message here"></textarea>
        </div>

        

        <button type="submit">Submit</button>
    </form>
</div>

<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect the form data
    $name = htmlspecialchars($_POST['name']);
    $surname = htmlspecialchars($_POST['surname']);
    $email = htmlspecialchars($_POST['email']);
    $mobile = htmlspecialchars($_POST['mobile']);
    $message = htmlspecialchars($_POST['message']);

    // Database connection details
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "dbwebdev";

    // Create a connection to the database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check for a connection error
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL statement to avoid SQL injection
    $stmt = $conn->prepare("INSERT INTO submit (name, surname, email, mobile, message) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssis", $name, $surname, $email, $mobile, $message); // 'sssis' means 4 strings and 1 integer

    // Execute the statement
    if ($stmt->execute()) {
        echo "Message received";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
 <a href="https://www.facebook.com/profile.php?id=61567094392961" target="_blank">
      <button>👍 Like us on Facebook</button>
    </a>

</body>
</html>














	