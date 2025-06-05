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
  <title>Resources | CBC Tech Learn</title>
  <link rel="icon" type="image/png" href="img/tech-learn-favicon.png">
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    h1, h2 {
    color: #2a3f54;
  }
  section {
    margin-bottom: 40px;
    border: 1px solid #ccc;
    padding: 15px;
    border-radius: 8px;
  }
  .quiz {
    margin-bottom: 20px;
  }
  .question {
    margin-bottom: 10px;
  }
  label {
    margin-left: 10px;
  }
  button {
    margin-top: 10px;
    padding: 8px 15px;
    background-color: #2a8fbd;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
  }
  button:hover {
    background-color: #1e6f91;
  }
  .result {
    font-weight: bold;
    margin-top: 10px;
  }
  ul.notes-list, ul.activities-list {
    list-style: none;
    padding-left: 0;
  }
  ul.notes-list li, ul.activities-list li {
    padding: 5px 0;
  }
  a.note-link {
    text-decoration: none;
    color: #2a8fbd;
  }
  a.note-link:hover {
    text-decoration: underline;
  }
  label {
  margin-left: 10px;
  display: inline-block;
  padding: 5px;
  transition: background-color 0.3s ease, border 0.3s ease;
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
.resources-content h1{
  text-align: center;
  margin-bottom: 20px;
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

  
  <main class="resources-content">
    <h1>Student Learning Dashboard</h1>
    <section id="quizzes-section">
        <h2>Available Quizzes</h2>
        <div id="quizzes-container">
          Loading quizzes...
        </div>
      </section>
      
      <section id="notes-section">
        <h2>Notes</h2>
        <ul id="notes-list" class="notes-list">
          Loading notes...
        </ul>
      </section>
      
      <section id="activities-section">
        <h2>Upcoming Activities</h2>
        <ul id="activities-list" class="activities-list">
          Loading activities...
        </ul>
      </section>
    
  </main>

  <footer>
    <p>© <a href="index.php" target="_blank"  style="color: #4CAF50;text-decoration: none; ">CBC Tech Learn</a> <script type="text/javascript">document.write(new Date().getFullYear());</script> . All rights reserved.</p>
</footer>

  <script>
    let selectedAnswers = {}; // Track selected answers per quiz
  
    // Fetch JSON utility
    async function fetchJSON(url) {
      const res = await fetch(url);
      if (!res.ok) throw new Error('Network response was not ok');
      return res.json();
    }
  
    // Render quizzes
    function renderQuizzes(quizzes) {
      const container = document.getElementById('quizzes-container');
      if (!quizzes.length) {
        container.innerHTML = '<p>No quizzes available.</p>';
        return;
      }
      container.innerHTML = '';
  
      quizzes.forEach(quiz => {
        const quizDiv = document.createElement('div');
        quizDiv.classList.add('quiz');
        quizDiv.innerHTML = `<h3>${quiz.title}</h3>`;
  
        quiz.questions.forEach((q, idx) => {
          const qDiv = document.createElement('div');
          qDiv.classList.add('question');
  
          const key = `quiz${quiz.id}_q${q.id}`;
          const selected = selectedAnswers[key] || '';
  
          qDiv.innerHTML = `
            <p><strong>Q${idx + 1}:</strong> ${q.question}</p>
            <label><input type="radio" name="${key}" value="A" ${selected === 'A' ? 'checked' : ''}> A. ${q.option_a}</label><br>
            <label><input type="radio" name="${key}" value="B" ${selected === 'B' ? 'checked' : ''}> B. ${q.option_b}</label><br>
            <label><input type="radio" name="${key}" value="C" ${selected === 'C' ? 'checked' : ''}> C. ${q.option_c}</label><br>
            <label><input type="radio" name="${key}" value="D" ${selected === 'D' ? 'checked' : ''}> D. ${q.option_d}</label><br>
          `;
  
          quizDiv.appendChild(qDiv);
        });
  
        const submitBtn = document.createElement('button');
        submitBtn.textContent = 'Submit Quiz';
        submitBtn.onclick = () => gradeQuiz(quiz);
        quizDiv.appendChild(submitBtn);
  
        const resultDiv = document.createElement('div');
        resultDiv.classList.add('result');
        quizDiv.appendChild(resultDiv);
  
        // Track selected answers
        quizDiv.querySelectorAll('input[type="radio"]').forEach(radio => {
          radio.addEventListener('change', () => {
            selectedAnswers[radio.name] = radio.value;
          });
        });
  
        container.appendChild(quizDiv);
      });
    }
  
    // Grade quiz and save result
    function gradeQuiz(quiz) {
      const quizDiv = event.target.parentElement;
      let correctCount = 0;
      const total = quiz.questions.length;
  
      for (const q of quiz.questions) {
        const radios = quizDiv.querySelectorAll(`input[name="quiz${quiz.id}_q${q.id}"]`);
        let answered = false;
        radios.forEach(radio => {
          if (radio.checked) answered = true;
        });
        if (!answered) {
          alert(`Please answer all questions before submitting the quiz.`);
          return;
        }
      }
  
      // Grade
      quiz.questions.forEach(q => {
        const radios = quizDiv.querySelectorAll(`input[name="quiz${quiz.id}_q${q.id}"]`);
        let selected = null;
  
        radios.forEach(radio => {
          const label = radio.parentElement;
          label.style.backgroundColor = '';
          radio.disabled = true;
  
          if (radio.checked) {
            selected = radio.value;
            if (selected === q.correct_option) {
              label.style.backgroundColor = '#d4edda';
            } else {
              label.style.backgroundColor = '#f8d7da';
            }
            label.style.borderRadius = '4px';
          }
  
          if (radio.value === q.correct_option) {
            label.style.border = '2px solid green';
            label.style.borderRadius = '4px';
          } else {
            label.style.border = '';
          }
        });
  
        if (selected === q.correct_option) correctCount++;
      });
  
      // Calculate result
      const percentage = Math.round((correctCount / total) * 100);
      let feedback = '';
      let stars = '';
  
      if (percentage === 100) {
        feedback = '🌟 Perfect! Excellent work!';
        stars = '⭐️⭐️⭐️⭐️⭐️';
      } else if (percentage >= 80) {
        feedback = '🎉 Great job!';
        stars = '⭐️⭐️⭐️⭐️';
      } else if (percentage >= 60) {
        feedback = '👍 Good effort.';
        stars = '⭐️⭐️⭐️';
      } else if (percentage >= 40) {
        feedback = '📘 Needs improvement.';
        stars = '⭐️⭐️';
      } else {
        feedback = '🔄 Try again!';
        stars = '⭐️';
      }
  
      const resultText = `
        <p>You scored <strong>${correctCount}</strong> out of <strong>${total}</strong></p>
        <p>Percentage: <strong>${percentage}%</strong></p>
        <p>Rating: ${stars}</p>
        <p><em>${feedback}</em></p>
      `;
      quizDiv.querySelector('.result').innerHTML = resultText;
  
      // 🔄 Send result to backend
      fetch('save_result.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
          quiz_id: quiz.id,
          quiz_title: quiz.title,
          score: correctCount,
          total: total,
          percentage: percentage
        })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          console.log("Result saved successfully.");
        } else {
          console.warn("Result save failed:", data.error);
        }
      })
      .catch(err => console.error("Error saving result:", err));
    }
  
    // Render notes
    function renderNotes(notes) {
      const ul = document.getElementById('notes-list');
      if (!notes.length) {
        ul.innerHTML = '<li>No notes available.</li>';
        return;
      }
      ul.innerHTML = '';
      notes.forEach(note => {
        const li = document.createElement('li');
        const link = document.createElement('a');
        link.href = note.file_path;
        link.textContent = note.title;
        link.className = 'note-link';
        link.target = '_blank';
        li.appendChild(link);
        ul.appendChild(li);
      });
    }
  
    // Render activities
    function renderActivities(activities) {
      const ul = document.getElementById('activities-list');
      if (!activities.length) {
        ul.innerHTML = '<li>No upcoming activities.</li>';
        return;
      }
      ul.innerHTML = '';
      activities.forEach(activity => {
        const li = document.createElement('li');
        li.textContent = `${activity.title} — ${activity.event_date}`;
        ul.appendChild(li);
      });
    }
  
    // Polling
    async function pollData() {
      try {
        const [quizzes, notes, activities] = await Promise.all([
          fetchJSON('get_quizzes.php'),
          fetchJSON('get_notes.php'),
          fetchJSON('get_activities.php')
        ]);
        renderQuizzes(quizzes);
        renderNotes(notes);
        renderActivities(activities);
      } catch (e) {
        console.error("Failed to fetch data:", e);
      }
    }
  
    // Initial load and refresh
    pollData();
    setInterval(pollData, 10000);


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
