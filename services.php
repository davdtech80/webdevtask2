<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>WeLoveParenting Malta - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css?version=9" rel="stylesheet">
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
                    <li><input id="search" type="text" placeholder="Search, adhd, family, schools" onkeyup="searchSuggestions()"></li>
                    
                </ul>
            </nav>
        </div>
        <div id="suggestions"></div>
    </header>

    <!-- PHP Registration Form Section -->
    <div class="form-contact-l">
        <h2>Register</h2>

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
            // Sanitize user input
            $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
            $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');

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

        <?php echo $message; ?>

       <form class="form" method="POST" action="services.php" onsubmit="return validateEmail()">
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Create Email" required>
    </div>

    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Create Password" required>
    </div>

    <div>
        <button type="submit">Submit</button>
    </div>
</form>

<a href="https://www.facebook.com/profile.php?id=61567094392961" target="_blank">
    <button>👍 Like us on Facebook</button>
</a>

<script>
function validateEmail() {
    var email = document.getElementById("email").value;
    var atSymbol = "@";
    
    if (email.indexOf(atSymbol) === -1) {
        alert("Please enter a valid email address with '@'.");
        return false; // Prevent form submission
    }

    // You can add further checks if needed, like validating domain name structure.
    return true; // Allow form submission
}
</script>


    <!-- JavaScript for Search Suggestions -->
    <script>
        // Function to handle search suggestions
        function searchSuggestions() {
            var query = document.getElementById('search').value;

            if (query.length < 2) {
                document.getElementById('suggestions').innerHTML = '';  // Hide suggestions if input is too short
                return;
            }

            // AJAX request to fetch suggestions from search.php
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "search.php?query=" + encodeURIComponent(query), true);

            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    try {
                        var suggestions = JSON.parse(xhr.responseText);

                        var suggestionsContainer = document.getElementById('suggestions');
                        suggestionsContainer.innerHTML = ''; // Clear previous suggestions

                        if (suggestions.length > 0) {
                            // Loop through suggestions and display them
                            suggestions.forEach(function (item) {
                                var div = document.createElement('div');
                                div.classList.add('suggestion-item');
                                div.innerHTML = item.title + ' - ' + item.category;  // Display article title and category

                                div.onclick = function () {
                                    // When the user clicks on a suggestion, fetch the full article or message
                                    viewArticle(item.id);
                                    document.getElementById('search').value = item.title; // Populate input with clicked suggestion
                                    document.getElementById('suggestions').innerHTML = ''; // Hide suggestions
                                };

                                suggestionsContainer.appendChild(div);
                            });
                        } else {
                            suggestionsContainer.innerHTML = '<div class="suggestion-item">No results found</div>';
                        }
                    } catch (e) {
                        console.error("Error parsing JSON response:", e);
                    }
                }
            };

            xhr.send();
        }

        // Function to view the full article based on category
        function viewArticle(articleId, category) {
            // Redirect to the appropriate page based on the category
            if (category === 'adhd') {
                window.location.href = 'article.php?id=' + articleId; // Redirect to tech-specific page
            } else if (category === 'family') {
                window.location.href = 'family.php?id=' + articleId; // Redirect to health-specific page
            } else {
                window.location.href = 'article.php?id=' + articleId; // Default article page
            }
        }
    </script>
</body>
</html>
