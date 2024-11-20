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


<div class="jumbotron text-center">
  <h1>We Love Parenting</h1>
</div>
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

          <li><input id="search" type="text" placeholder="Search articles, topics, or services..." onkeyup="searchSuggestions()">

            <div id="suggestions"></div>
          </li>
        </ul>
      </nav>
    </div>
  </header>

  <div class="image">
    <img class="parent-img" src="/assets/images/genituri.jpg" alt="parents">
  </div>

  <div class="text">
    <p class="front-text">
      Parenting Challenges (ADD/ADHD, discipline and behaviour management)
      Foster and Adoptive Family Support
      School Challenges and Academic Pressure
      Trauma-Informed Therapeutic Parenting
      Blended Family Dynamics
      Siblings’ Rivalry
      Separation, Divorce, and Stress Management
      Parent-Child Communication
      Sleep/Routines/Schedules
      Media and Digital Parenting
      Child Development (pregnancy through teen years)
      Stress and anxiety
      Parental Well-Being and Self Care
    </p>
  </div>

  <a href="https://www.facebook.com/profile.php?id=61567094392961" target="_blank">
    <button>👍 Like us on Facebook</button>
  </div>

  <footer>
    <p class="copyright">Copyright 2020 &copy; All rights reserved</p>
  </footer>
  
  <!-- Live Search JavaScript -->
  <script>
    // Function to handle search suggestions
   function searchSuggestions() {
    var query = document.getElementById('search').value;

    if (query.length < 3) {
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
