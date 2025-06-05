<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>CBC Tech Learn</title>
  <link rel="stylesheet" href="style.css"/>
  <link rel="icon" type="image/png" href="img/tech-learn-favicon.png">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet"/>
  <style>
.row-container {
  display: flex;
  justify-content: space-between;
  gap: 2rem;
  max-width: 800px;
  margin: auto;
  padding: 2rem 1rem;
  align-items: flex-start;
}

.column-box {
  flex: 1;
  min-width: 300px;
}

.column-box h3 {
  margin-bottom: 1rem;
  font-size: 1.2rem;
  height: 2.5rem; /* Ensures both headings align */
  line-height: 2.5rem; /* Aligns text vertically within that height */
}

/* Ensure responsiveness */
@media (max-width: 768px) {
  .row-container {
    flex-direction: column;
  }

  .column-box {
    width: 100%;
  }
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

  </style>
</head>
<body>
  <header>
    <h1>CBC Tech Learn</h1>
    <p>Enhancing CBC education through interactive technology</p>
  </header>

  <section class="hero">
    <h2>Empowering Learners Through Technology</h2>
    <p>A simple platform to support CBC with engaging, interactive tools.</p>
    <a href="explore.php" class="btn">Explore More</a>
  </section>

  <section id="about">
    <h3>About the Platform</h3>
    <p>
      CBC Tech Learn is a simple, interactive platform created to support the Competency-Based Curriculum by integrating technology in learning. It offers tools that make learning fun, practical, and accessible.
    </p>
    

  </section>

  <div class="row-container">
  <div class="column-box">
    <h3>Key Features</h3>
    <ul>
      <li>🧠 Interactive Learning Tools</li>
      <li>📱 Mobile-friendly Access</li>
      <li>👨‍🏫 Teacher & Parent Involvement</li>
      <li>📚 Curriculum-Aligned Content</li>
    </ul>
  </div>

  <div class="column-box">
    <h3>Benefits</h3>
    <ul>
      <li>Encourages critical thinking and creativity</li>
      <li>Makes learning more engaging</li>
      <li>Bridges the gap between home and school</li>
      <li>Supports learners at their own pace</li>
    </ul>
  </div>
</div>




  <section id="contact">
    <h3>Contact Us</h3>
    <p>Email: info@cbctechlearn.com</p>
    <p>Location: Nairobi, Kenya</p>
  </section>

  <footer>
    <p>© <a href="index.html" target="_self"  style="color: #4CAF50;text-decoration: none; ">CBC Tech Learn</a> <script type="text/javascript">document.write(new Date().getFullYear());</script> . All rights reserved.</p>
</footer>
<!-- WhatsApp Floating Button -->
<a href="https://wa.me/254740504734" target="_blank" class="whatsapp-float">
  <img src="img/whatsapp.png" alt="WhatsApp" />
  <span class="whatsapp-text">Hi, Chat with us here</span>
</a>
</body>
</html>
