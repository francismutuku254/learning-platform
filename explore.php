<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login_page.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="icon" type="image/png" href="img/tech-learn-favicon.png">
  <title>Explore | CBC Tech Learn</title>
  <link rel="stylesheet" href="style.css" />
   <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet"/>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
      footer p{
    text-align: center;
    color: white;
    justify-content: center;
    margin-top: 2rem;
    font-size: 0.9rem;
    }
    /* Hamburger icon styles */
.menu-toggle {
  display: none;
  font-size: 2rem;
  color: white;
  cursor: pointer;
}

/* Mobile nav - hidden by default */
@media (max-width: 768px) {
  .menu-toggle {
    display: block;
  }

  .nav-links {
    position: absolute;
    top: 70px;
    right: 0;
    background-color: green;
    width: 40%;
    flex-direction: column;
    align-items: center;
    display: none;
  }

  .nav-links.show {
    display: flex;
  }

  .nav-links li {
    margin: 1rem 0;
  }
}
.logo-container {
  display: flex;
  align-items: center;
  gap: 10px;
  
}

.nav-logo {
  height: 45px;
  width: 45px;
  object-fit: contain;
 
}
.logout-link i {
  margin-right: 6px; /* space between icon and text */
}

.logout-link {
  color: white;
  font-weight: bold;
  display: flex;
  align-items: center;
}
.logout-link:hover {
  text-decoration: underline;
}

    
 

  </style>
 
</head>
<body>
  <nav>
  <div class="nav-container">
    <div class="logo-container">
      <a href="explore.php">
        <img src="img/tech-learn-favicon.png" alt="Logo" class="nav-logo">
      </a>
      <h1 class="logo">CBC Tech Learn</h1>
    </div>
    <div class="menu-toggle" id="menu-toggle">&#9776;</div>
    <ul class="nav-links" id="nav-links">
      <li><a href="explore.php">Home</a></li>
      <li><a href="resources.php">Resources</a></li>
      <li><a href="results_dashboard.php">Results</a></li>
      <li><a href="analytics.php">Analytics</a></li>
      <li><a href="contact.php">Contact</a></li>
      <li><a href="logout.php" class="logout-link">
    <i class="fas fa-right-from-bracket"></i> Logout
      </a></li>
    </ul>
  </div>
</nav>

  <main class="explore-content">
    <div class="content" id="content">
        <div class="content-wrapper">
            <!-- Left Section: Text -->
            <div class="content-text">
                <h1> <span id="new" class="auto" >Welcome to CBC Tech Learn! </span></h1>
                <br>
                <h3> <span id="new" class="auto" >Empowering Competence Through Technology </span></h3>

                <!-- <script src="https://unpkg.com/typed.js@2.1.0/dist/typed.umd.js"></script> -->
       <!-- <script>
           var typed= new Typed(".auto",{
               strings :
               [ "Welcome to CBC Tech Learn","Empowering Competence Through Technology!","Thank you for your time!"],
               typeSpeed: 100,
               backSpeed: 70,
               loop: true,
           })
       </script> -->
            </div>
            <!-- Right Section: Animation -->
            <div class="content2">
                <div class="sliderframe">
                    <div class="slider">
                        <img src="img/image5.webp">
                    </div>
                    <div class="slider">
                        <img src="img/image2.jpg">
                    </div>
                    <div class="slider">
                        <img src="img/image3.jpg">
                    </div>
                    <div class="slider">
                        <img src="img/image4.jpg">
                    </div>
                </div>
            </div>
        </div>
    </div>
  </main>

<footer>      
  <p >© <a href="index.php" target="_blank"  style="color: #4CAF50;text-decoration: none; ">CBC Tech Learn</a> <script type="text/javascript">document.write(new Date().getFullYear());</script> . All rights reserved.</p>   
</footer>
<script>
const toggleBtn = document.getElementById('menu-toggle');
const navLinks = document.getElementById('nav-links');

// Toggle menu on click
toggleBtn.addEventListener('click', (e) => {
  e.stopPropagation(); // Prevent the click from bubbling to document
  navLinks.classList.toggle('show');
});

// Close menu if click is outside of menu and toggle button
document.addEventListener('click', (e) => {
  if (!navLinks.contains(e.target) && !toggleBtn.contains(e.target)) {
    navLinks.classList.remove('show');
  }
});

// Prevent clicks inside navLinks from bubbling up
navLinks.addEventListener('click', (e) => {
  e.stopPropagation();
});

</script>

</body>
</html>
