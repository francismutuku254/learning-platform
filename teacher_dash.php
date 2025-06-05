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
  <title>CBC Tech Learn | Teacher Dashboard</title>
  <link rel="icon" type="image/png" href="img/tech-learn-favicon.png">
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet"/>
  <style>
    body {
      /* font-family: Arial, sans-serif;
      margin: 50px;
      background-color: #f7f7f7; */
      overflow-x: hidden;
    }

    .explore-content h1 {
      text-align: center;
      color: #2c3e50;
    }

    h2 {
      color: #1e88e5;
    }

    section {
      background: #fff;
      border-radius: 8px;
      padding: 20px;
      margin-bottom: 30px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      width: 1100px;
    }

    label {
      display: inline-block;
      width: 150px;
      vertical-align: top;
      margin-top: 6px;
    }

    input[type="text"],
    input[type="date"],
    input[type="file"] {
      width: 300px;
      padding: 5px;
      margin-bottom: 10px;
    }

    input[type="submit"],
    button {
      padding: 10px 20px;
      margin-top: 10px;
      background-color: green;
      color: white;
      border-radius: 5px;
      border: none;
      cursor: pointer;
    }

    .question {
      margin-bottom: 20px;
      border-bottom: 1px solid #ddd;
      padding-bottom: 10px;
    }
    footer p{
    text-align: center;
    color: white;
    justify-content: center;
    margin-top: 2rem;
    font-size: 0.9rem;
}
/* Responsive Styles */
@media (max-width: 768px) {
    section{
        width:100% ;
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

  </style>

 
  
</head>
<body>
  <nav>
    <div class="nav-container">
      <div class="logo-container">
      <img src="img/tech-learn-favicon.png" alt="Logo" class="nav-logo">
      <h1 class="logo">CBC Tech Learn</h1>
      </div>
      <div class="menu-toggle" id="menu-toggle">&#9776;</div>
      <ul class="nav-links" id="nav-links">
        <li><a href="teacher_dash.php">Dashboard</a></li>
        <li><a href="teacher_man.php">Manage</a></li>
        <li><a href="teacher_message.php">Messages</a></li>
        <li><a href="admin_panel.php">Admin</a></li>
        <li><a href="logout.php" class="logout-link">
    <i class="fas fa-right-from-bracket"></i> Logout
        </a></li>
      </ul>
    </div>
  </nav>

  <main class="explore-content">
    <h1>Teacher Dashboard</h1>

  <!-- Upload Quiz Form -->
  <section>
    <h2>Upload Quiz</h2>
    <form id="quizForm" autocomplete="off">
      <label for="quiz_title">Quiz Title:</label>
      <input type="text" id="quiz_title" name="quiz_title" required><br><br>

      <div id="questions">
        <div class="question">
          <label>Question:</label>
          <input type="text" name="questions[]" required><br>
          <label>Option A:</label>
          <input type="text" name="option_a[]" required><br>
          <label>Option B:</label>
          <input type="text" name="option_b[]" required><br>
          <label>Option C:</label>
          <input type="text" name="option_c[]" required><br>
          <label>Option D:</label>
          <input type="text" name="option_d[]" required><br>
          <label>Correct Option (A/B/C/D):</label>
          <input type="text" name="correct_option[]" required><br><br>
        </div>
      </div>

      <button type="button" onclick="addQuestion()">Add Another Question</button><br><br>
      <input type="submit" value="Upload Quiz">
    </form>
  </section>

  <!-- Upload Note Form -->
  <section>
    <h2>Upload Note</h2>
    <form id="noteForm" enctype="multipart/form-data" autocomplete="off">
      <label for="note_title">Note Title:</label>
      <input type="text" id="note_title" name="note_title" required><br><br>

      <label for="note_file">Select File:</label>
      <input type="file" id="note_file" name="note_file" required><br><br>

      <input type="submit" value="Upload Note">
    </form>
  </section>

  <!-- Post Activity Form -->
  <section>
    <h2>Post Upcoming Activity</h2>
    <form id="activityForm" autocomplete="off">
      <label for="activity_title">Activity Title:</label>
      <input type="text" id="activity_title" name="activity_title" required><br><br>

      <label for="event_date">Event Date:</label>
      <input type="date" id="event_date" name="event_date" required><br><br>

      <input type="submit" value="Post Activity">
    </form>
  </section>

  <script>
    // Add More Questions
    function addQuestion() {
      const questionDiv = document.querySelector('.question').cloneNode(true);
      // Clear input fields in the new question block
      questionDiv.querySelectorAll('input').forEach(input => input.value = '');
      document.getElementById('questions').appendChild(questionDiv);
    }

    // Handle Quiz Form Submission
    document.getElementById('quizForm').addEventListener('submit', function (e) {
      e.preventDefault();
      const formData = new FormData(this);
      fetch('upload_quiz.php', {
        method: 'POST',
        body: formData
      })
      .then(res => res.text())
      .then(msg => alert(msg))
      .catch(err => alert("Error uploading quiz."));
    });

    // Handle Note Upload
    document.getElementById('noteForm').addEventListener('submit', function (e) {
      e.preventDefault();
      const formData = new FormData(this);
      fetch('upload_note.php', {
        method: 'POST',
        body: formData
      })
      .then(res => res.text())
      .then(msg => alert(msg))
      .catch(err => alert("Error uploading note."));
    });

    // Handle Activity Posting
    document.getElementById('activityForm').addEventListener('submit', function (e) {
      e.preventDefault();
      const formData = new FormData(this);
      fetch('post_activity.php', {
        method: 'POST',
        body: formData
      })
      .then(res => res.text())
      .then(msg => alert(msg))
      .catch(err => alert("Error posting activity."));
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
  </main>

<footer>
    <p>© <a href="index.php" target="_blank"  style="color: #4CAF50;text-decoration: none; ">CBC Tech Learn</a> <script type="text/javascript">document.write(new Date().getFullYear());</script> . All rights reserved.</p>
</footer>
</body>
</html>
