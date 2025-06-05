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
  <title>Contact Us | CBC Tech Learn</title>
  <link rel="icon" type="image/png" href="img/tech-learn-favicon.png">
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
.contact-container {
  max-width: 600px;
  margin: 50px auto;
  padding: 25px;
  background-color: #ffffff;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  border-radius: 8px;
}

h1 {
  text-align: center;
  color: #2a3f54;
}

p {
  text-align: center;
  color: #555;
  margin-bottom: 30px;
}

form .form-group {
  margin-bottom: 20px;
}

form label {
  display: block;
  margin-bottom: 8px;
  color: #333;
}

form input[type="text"],
form input[type="email"],
form textarea {
  width: 100%;
  padding: 10px 12px;
  border: 1px solid #ccc;
  border-radius: 5px;
  font-size: 1rem;
  box-sizing: border-box;
}

form textarea {
  resize: vertical;
}

button {
  display: block;
  width: 100%;
  background-color: #2a8fbd;
  color: white;
  font-size: 1rem;
  padding: 12px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background 0.3s ease;
}

button:hover {
  background-color: #1e6f91;
}
footer p{
    text-align: center;
    color: white;
    justify-content: center;
    margin-top: 2rem;
    font-size: 0.9rem;
}
/* WhatsApp Floating Button - Responsive */
.whatsapp-float {
  position: fixed;
  bottom: 20px;
  right: 20px;
  background-color: #25D366;
  color: white;
  padding: 10px 16px;
  border-radius: 30px;
  text-decoration: none;
  display: flex;
  align-items: center;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
  z-index: 10000;
  transition: background 0.3s ease;
}

.whatsapp-float:hover {
  background-color: #1ebc59;
}

.whatsapp-float img {
  width: 24px;
  height: 24px;
  margin-right: 8px;
}

.whatsapp-text {
  font-size: 0.95rem;
  font-weight: 600;
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


/* Make responsive on smaller screens */
@media (max-width: 600px) {
  .whatsapp-float {
    padding: 8px 12px;
    bottom: 15px;
    right: 15px;
  }

  .whatsapp-float img {
    width: 20px;
    height: 20px;
  }

  .whatsapp-text {
    font-size: 0.85rem;
  }
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



  </style>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet"/>
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
    <div class="contact-container">
        <h1>Contact CBC Tech Learn</h1>
        <p>Have a question or suggestion? We'd love to hear from you.</p>
    
        <form id="contactForm">
          <div class="form-group">
            <label for="name">Your Name</label>
            <input type="text" id="name" name="name" placeholder="Enter your full name" required>
          </div>
    
          <div class="form-group">
            <label for="email">Your Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
          </div>
    
          <div class="form-group">
            <label for="subject">Subject</label>
            <input type="text" id="subject" name="subject" placeholder="Enter subject" required>
          </div>
    
          <div class="form-group">
            <label for="message">Message</label>
            <textarea id="message" name="message" rows="6" placeholder="Write your message here..." required></textarea>
          </div>
    
          <button type="submit">Send Message</button>
        </form>
        <p id="responseMsg" style="text-align: center; margin-top: 10px; color: green;"></p>
        <script>
            document.getElementById("contactForm").addEventListener("submit", function(e) {
              e.preventDefault();
              
              const formData = new FormData(this);
            
              fetch("contact-submit.php", {
                method: "POST",
                body: formData
              })
              .then(res => res.text())
              .then(data => {
                document.getElementById("responseMsg").textContent = data;
                document.getElementById("contactForm").reset();
              })
              .catch(err => {
                document.getElementById("responseMsg").textContent = "Error sending message.";
              });
            });

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
      </div>
  </main>

  <footer>
    <p>© <a href="index.php" target="_blank"  style="color: #4CAF50;text-decoration: none; ">CBC Tech Learn</a> <script type="text/javascript">document.write(new Date().getFullYear());</script> . All rights reserved.</p>
</footer>
<!-- WhatsApp Floating Button -->
<a href="https://wa.me/254740504734" target="_blank" class="whatsapp-float">
  <img src="img/whatsapp.png" alt="WhatsApp" />
  <span class="whatsapp-text">How can I help you?</span>
</a>

</body>
</html>
