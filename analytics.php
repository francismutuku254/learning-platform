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
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


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
h2 {
      text-align: center;
      margin-bottom: 1rem;
    }

    label {
      font-weight: bold;
      margin-left: 300px;
    }

    select {
      padding: 0.4rem 0.6rem;
      font-size: 1rem;
      margin-bottom: 1.5rem;
      width: 100%;
      max-width: 300px;
      display: block;
      margin-left: auto;
      margin-right: auto;
      border-radius: 4px;
      border: 1px solid #ccc;
    }

    #chart-container {
  background: white;
  padding: 1rem;
  border-radius: 6px;
  box-shadow: 0 0 8px rgba(0,0,0,0.1);
  max-width: 800px; /* Reduce this value as needed */
  margin: 0 auto;   /* Center the chart container */
}

    canvas {
  display: block;
  max-width: 100%;
  height: 250px;
}

    .loading {
      text-align: center;
      margin-top: 1rem;
      font-style: italic;
      color: #666;
    }
    @media (max-width: 768px) {
        label{
            margin-left: 25px;
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
        <h2>Quiz Scores Over Time</h2>

  <label for="quizFilter">Select Quiz:</label>
  <select id="quizFilter">
    <option value="">-- Loading quizzes... --</option>
  </select>

  <div id="chart-container">
    <canvas id="scoreChart"></canvas>
    <div class="loading" id="loadingText">Loading data...</div>
  </div>

      <script>
const quizFilter = document.getElementById('quizFilter');
const loadingText = document.getElementById('loadingText');
const ctx = document.getElementById('scoreChart').getContext('2d');

let chart; // Chart.js instance
let allResults = []; // Store all results fetched from the server

async function fetchResults() {
  try {
    const res = await fetch('get_results.php');
    const data = await res.json();
    return data;
  } catch (err) {
    console.error('Error fetching results:', err);
    return [];
  }
}

function populateQuizFilter(data) {
  const uniqueQuizzes = [...new Map(data.map(item => [item.quiz_title, item])).values()];
  quizFilter.innerHTML = '<option value="">-- Select a Quiz --</option>';
  uniqueQuizzes.forEach(q => {
    const option = document.createElement('option');
    option.value = q.quiz_title;
    option.textContent = q.quiz_title;
    quizFilter.appendChild(option);
  });
}

function filterResultsByQuiz(title) {
  if (!title) return [];
  return allResults
    .filter(r => r.quiz_title === title)
    .sort((a, b) => new Date(a.result_date) - new Date(b.result_date));
}

function formatDate(dateStr) {
  const d = new Date(dateStr);
  return d.toLocaleString(undefined, {
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
    hour12: true
  });
}

function createChart(data) {
  if (!data.length) {
    loadingText.textContent = 'No data available for selected quiz.';
    return null;
  }
  loadingText.textContent = '';

  const labels = data.map(item => formatDate(item.result_date));
  const percentages = data.map(item => Number(item.percentage));

  return new Chart(ctx, {
    type: 'line',
    data: {
      labels,
      datasets: [{
        label: 'Score Percentage',
        data: percentages,
        borderColor: '#4CAF50',
        backgroundColor: 'rgba(76, 175, 80, 0.2)',
        fill: true,
        tension: 0.3,
        pointRadius: 5,
        pointHoverRadius: 7,
      }]
    },
    options: {
      responsive: true,
      animation: {
        duration: 500,
      },
      scales: {
        x: {
          title: {
            display: true,
            text: 'Date & Time'
          },
          ticks: {
            autoSkip: true,
            maxRotation: 45,
            minRotation: 0,
          }
        },
        y: {
          suggestedMin: 0,
          suggestedMax: 100,
          ticks: {
            callback: val => val + '%'
          },
          title: {
            display: true,
            text: 'Percentage'
          }
        }
      },
      plugins: {
        legend: {
          display: true,
          position: 'top',
        },
        tooltip: {
          callbacks: {
            label: context => `${context.parsed.y}%`
          }
        }
      }
    }
  });
}

function updateChartData(chart, data) {
  if (!data.length) {
    loadingText.textContent = 'No data available for selected quiz.';
    chart.data.labels = [];
    chart.data.datasets[0].data = [];
    chart.update();
    return;
  }

  loadingText.textContent = '';
  chart.data.labels = data.map(item => formatDate(item.result_date));
  chart.data.datasets[0].data = data.map(item => Number(item.percentage));
  chart.update();
}

quizFilter.addEventListener('change', () => {
  const selectedQuiz = quizFilter.value;
  const filtered = filterResultsByQuiz(selectedQuiz);
  if (chart) {
    updateChartData(chart, filtered);
  } else {
    chart = createChart(filtered);
  }
});

async function init() {
  loadingText.textContent = 'Loading data...';
  allResults = await fetchResults();

  if (allResults.length === 0) {
    loadingText.textContent = 'No quiz data found.';
    quizFilter.innerHTML = '<option value="">-- No quizzes available --</option>';
    if(chart) chart.destroy();
    return;
  }

  populateQuizFilter(allResults);

  if (quizFilter.options.length > 1) {
    quizFilter.selectedIndex = 1;
    const filtered = filterResultsByQuiz(quizFilter.value);
    chart = createChart(filtered);
  } else {
    loadingText.textContent = 'No quizzes to select.';
  }
}

// Poll for new data every 10 seconds
setInterval(async () => {
  const prevQuiz = quizFilter.value;
  const newResults = await fetchResults();

  if (JSON.stringify(newResults) !== JSON.stringify(allResults)) {
    allResults = newResults;
    populateQuizFilter(allResults);
    if ([...quizFilter.options].some(o => o.value === prevQuiz)) {
      quizFilter.value = prevQuiz;
      const filtered = filterResultsByQuiz(prevQuiz);
      if (chart) {
        updateChartData(chart, filtered);
      } else {
        chart = createChart(filtered);
      }
    } else {
      quizFilter.selectedIndex = 0;
      if (chart) updateChartData(chart, []);
    }
  }
}, 10000);

init();

      

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