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
  <title>CBC Tech Learn | Student Messages</title>
  <link rel="icon" type="image/png" href="img/tech-learn-favicon.png">
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet"/>
  <style>
html, body {
  height: 100%;
  margin: 0;
  padding: 0;
  display: flex;
  flex-direction: column;
}
body {
      /* font-family: Arial, sans-serif;
      padding: 20px;
      background-color: #f4f4f4; */
      overflow-x: hidden;
    }
main.explore-content {
  flex: 1;
}

  .explore-content  h1 {
      color: #333;
      text-align: center;
    }

    table {
  width: 90%;
  max-width: 1000px;
  margin: 0 auto;
  border-collapse: collapse;
  background: #fff;
  margin-bottom: 20px;
}

    th, td {
      padding: 10px;
      border: 1px solid #ccc;
      text-align: left;
      font-size: 14px;
    }

    th {
      background-color: #4CAF50;
      color: white;
    }

    .unread {
      background-color: #ffffcc;
    }

    .actions button {
      margin: 3px 2px;
      padding: 6px 12px;
      font-size: 13px;
      cursor: pointer;
    }

    .reply-form {
      display: none;
      margin-top: 10px;
      background: #fff;
      padding: 10px;
      border: 1px solid #ccc;
    }

    /* Responsive Styles */
    @media (max-width: 768px) {
      table, thead, tbody, th, td, tr {
        display: block;
      }

      thead {
        display: none;
      }

      tr {
        margin-bottom: 15px;
        background: #fff;
        border: 1px solid #ccc;
        padding: 10px;
      }

      td {
        padding: 10px;
        border: none;
        position: relative;
        padding-left: 50%;
      }

      td::before {
        position: absolute;
        top: 10px;
        left: 10px;
        width: 45%;
        padding-right: 10px;
        white-space: nowrap;
        font-weight: bold;
        color: #555;
      }

      td:nth-of-type(1)::before { content: "Name"; }
      td:nth-of-type(2)::before { content: "Email"; }
      td:nth-of-type(3)::before { content: "Subject"; }
      td:nth-of-type(4)::before { content: "Message"; }
      td:nth-of-type(5)::before { content: "Submitted"; }
      td:nth-of-type(6)::before { content: "Actions"; }
    }

    textarea {
      width: 100%;
      max-width: 100%;
      padding: 8px;
      font-size: 14px;
      box-sizing: border-box;
    }
footer p{
    text-align: center;
    color: white;
    justify-content: center;
    margin-top: 2rem;
    font-size: 0.9rem;
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
    <h1>Student Messages</h1>

  <table>
    <thead>
      <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Subject</th>
        <th>Message</th>
        <th>Submitted</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody id="messageBody">
      <!-- AJAX content -->
    </tbody>
  </table>

  <script>
    let replyOpen = false;

    function loadMessages() {
      if (!replyOpen) {
        fetch('fetch_messages.php')
          .then(response => response.text())
          .then(data => {
            document.getElementById('messageBody').innerHTML = data;
          });
      }
    }

    function deleteMessage(id) {
      if (confirm("Are you sure you want to delete this message?")) {
        fetch(`delete_message.php?id=${id}`)
          .then(() => loadMessages());
      }
    }

    function toggleReadStatus(id, action) {
      fetch(`mark_read.php?id=${id}&action=${action}`)
        .then(() => loadMessages());
    }

    function showReplyForm(id) {
      document.querySelectorAll('.reply-form').forEach(form => form.style.display = 'none');
      const form = document.getElementById(`reply-form-${id}`);
      if (form) {
        form.style.display = 'block';
        replyOpen = true;
      }
    }

    function sendReply(id) {
      const form = document.getElementById(`reply-form-${id}`);
      const formData = new FormData(form);

      fetch('send_reply.php', {
        method: 'POST',
        body: formData
      }).then(res => res.text())
        .then(msg => {
          alert(msg);
          form.reset();
          form.style.display = 'none';
          replyOpen = false;
          loadMessages();
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
      loadMessages();
      setInterval(loadMessages, 10000);
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
