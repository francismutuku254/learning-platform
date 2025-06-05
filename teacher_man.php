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
  <title>CBC Tech Learn | Teacher Manage Dashboard</title>
  <link rel="icon" type="image/png" href="img/tech-learn-favicon.png">
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet"/>
  <style>
body {
    /* font-family: Arial, sans-serif;
    margin: 40px;
    background: #f9f9f9; */
    overflow-x: hidden;
  }
  .explore-content h1 {
    color: #333;
    text-align: center;
    justify-content: center;

  }
  section {
    background: white;
    padding: 20px;
    margin-bottom: 30px;
    border-radius: 8px;
    box-shadow: 0 0 8px rgba(0,0,0,0.1);
    width: 1100px;
  }
  h2 {
    margin-bottom: 15px;
    color: #2a5d84;
  }
  table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 10px;
  }
  th, td {
    padding: 10px;
    border-bottom: 1px solid #ddd;
    text-align: left;
  }
  th {
    background: #eee;
  }
  button.delete-btn {
    background: #e74c3c;
    color: white;
    border: none;
    padding: 7px 15px;
    border-radius: 5px;
    cursor: pointer;
  }
  button.delete-btn:hover {
    background: #c0392b;
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
    <h1>Teacher Manage Dashboard</h1>

<section id="quizzes-section">
  <h2>Quizzes</h2>
  <table id="quizzes-table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <!-- Quizzes will load here -->
    </tbody>
  </table>
</section>

<section id="notes-section">
  <h2>Notes</h2>
  <table id="notes-table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Title</th>
        <th>File</th>
        <th>Uploaded At</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <!-- Notes will load here -->
    </tbody>
  </table>
</section>

<section id="activities-section">
  <h2>Upcoming Activities</h2>
  <table id="activities-table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Event Date</th>
        <th>Created At</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <!-- Activities will load here -->
    </tbody>
  </table>
</section>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    loadData();

    // Load all data from server
    function loadData() {
      fetch('fetch_all_teacher_data.php')
      .then(res => res.json())
      .then(data => {
        if(data.success) {
          populateQuizzes(data.quizzes);
          populateNotes(data.notes);
          populateActivities(data.activities);
        } else {
          alert('Failed to load data');
        }
        
      })
      .catch(err => alert('Error fetching data: ' + err));
    }

    // Populate quizzes table
    function populateQuizzes(quizzes) {
      const tbody = document.querySelector('#quizzes-table tbody');
      tbody.innerHTML = '';
      quizzes.forEach(q => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td>${q.id}</td>
          <td>${q.title}</td>
          <td><button class="delete-btn" data-type="quiz" data-id="${q.id}">Delete</button></td>
        `;
        tbody.appendChild(tr);
      });
    }

    // Populate notes table
    function populateNotes(notes) {
      const tbody = document.querySelector('#notes-table tbody');
      tbody.innerHTML = '';
      notes.forEach(note => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td>${note.id}</td>
          <td>${note.title}</td>
          <td><a href="${note.file_path}" target="_blank">View File</a></td>
          <td>${note.uploaded_at}</td>
          <td><button class="delete-btn" data-type="note" data-id="${note.id}">Delete</button></td>
        `;
        tbody.appendChild(tr);
      });
    }

    // Populate activities table
    function populateActivities(activities) {
      const tbody = document.querySelector('#activities-table tbody');
      tbody.innerHTML = '';
      activities.forEach(act => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td>${act.id}</td>
          <td>${act.title}</td>
          <td>${act.event_date}</td>
          <td>${act.created_at}</td>
          <td><button class="delete-btn" data-type="activity" data-id="${act.id}">Delete</button></td>
        `;
        tbody.appendChild(tr);
      });
    }

    // Handle delete button clicks using event delegation
    document.body.addEventListener('click', e => {
      if(e.target.classList.contains('delete-btn')) {
        const type = e.target.getAttribute('data-type');
        const id = e.target.getAttribute('data-id');

        if(confirm(`Are you sure you want to delete this ${type}?`)) {
          deleteItem(type, id);
        }
      }
    });

    // Send delete request to server
    function deleteItem(type, id) {
      fetch('delete_item.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ type, id })
      })
      .then(res => res.json())
      .then(data => {
        if(data.success) {
          alert(`${type.charAt(0).toUpperCase() + type.slice(1)} deleted successfully.`);
          loadData();
        } else {
          alert('Failed to delete: ' + data.message);
        }
      })
      .catch(err => alert('Error deleting item: ' + err));
    }
    setInterval(loadData, 10000); // refresh every 10,000ms (10 seconds)
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
