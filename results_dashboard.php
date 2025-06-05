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
  <title>Quiz Results | CBC Tech Learn</title>
  <link rel="icon" type="image/png" href="img/tech-learn-favicon.png">
  <link rel="stylesheet" href="style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


  <style>
html, body {
    height: 100%;
    margin: 0;
}

body {
    display: flex;
    flex-direction: column;
}

.wrapper {
    flex: 1;
    display: flex;
    flex-direction: column;
}

main.explore-content {
    flex: 1;
    padding: 20px;
}

  .explore-content h1 {
    text-align: center;
    margin-bottom: 20px;
  }

  #results-table {
    width: 100%;
    max-width: 800px;
    margin: 0 auto;
    border-collapse: collapse;
    background: white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  }

  #results-table th, #results-table td {
    padding: 12px 15px;
    border: 1px solid #ddd;
    text-align: center;
  }

  #results-table th {
    background-color: #007bff;
    color: white;
    font-weight: bold;
  }

  #results-table tbody tr:nth-child(even) {
    background-color: #f9f9f9;
  }

  #last-updated {
    max-width: 800px;
    margin: 10px auto;
    text-align: right;
    font-size: 0.9em;
    color: #666;
  }

  .percentage {
    font-weight: bold;
  }

  footer {
    background-color: #2c3e50;
    color: white;
    text-align: center;
    padding: 1rem 0;
  }

  footer p {
    font-size: 0.9rem;
    margin: 0;
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
.table-container {
  overflow-x: auto;
  max-width: 100%;
  margin: 0 auto;
}

#results-table {
  width: 100%;
  min-width: 600px; /* Prevent table from collapsing on small screens */
  border-collapse: collapse;
  background: white;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

@media (max-width: 600px) {
  #results-table th, #results-table td {
    padding: 10px;
    font-size: 0.85rem;
  }

  .explore-content h1 {
    font-size: 1.2rem;
  }
}


  </style>
  
</head>
<body>
  <div class="wrapper">
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
      <h1>Quiz Results Dashboard</h1>
      <div id="last-updated">Last updated: --</div>

      <div class="table-container">
  <table id="results-table" aria-label="Quiz Results Table">
    <thead>
      <tr>
        <th>Quiz ID</th>
        <th>Quiz Title</th>
        <th>Score</th>
        <th>Total Questions</th>
        <th>Percentage</th>
        <th>Date</th>
      </tr>
    </thead>
    <tbody>
      <tr><td colspan="5">Loading results...</td></tr>
    </tbody>
  </table>
</div>

      <script>
      async function fetchResults() {
        try {
          const response = await fetch('get_results.php');
          if (!response.ok) throw new Error('Network response was not ok');
          const data = await response.json();

          const tbody = document.querySelector('#results-table tbody');
          if (!data.length) {
            tbody.innerHTML = '<tr><td colspan="5">No results found.</td></tr>';
            return;
          }

          tbody.innerHTML = ''; // Clear old rows

          data.forEach(row => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
              <td>${row.quiz_id}</td>
              <td>${row.quiz_title}</td>
              <td>${row.score}</td>
              <td>${row.total}</td>
              <td class="percentage">${row.percentage}%</td>
              <td>${new Date(row.result_date).toLocaleString()}</td>
            `;
            tbody.appendChild(tr);
          });

          // Update last updated time
          document.getElementById('last-updated').textContent = 'Last updated: ' + new Date().toLocaleTimeString();

        } catch (err) {
          console.error('Failed to fetch quiz results:', err);
          const tbody = document.querySelector('#results-table tbody');
          tbody.innerHTML = '<tr><td colspan="5">Failed to load results.</td></tr>';
        }
      }

      // Initial load
      fetchResults();
      // Auto refresh every 10 seconds
      setInterval(fetchResults, 10000);

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
  </div>
</body>
</html>