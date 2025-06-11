<?php
$conn = new mysqli("localhost", "root", "", "csit_events");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM events ORDER BY date ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Events - CSIT Events Hub</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0; padding: 0;
      background: #f8f9fa;
    }
    header {
      background: #343a40;
      color: #fff;
      padding: 1rem 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    nav a {
      color: white;
      margin: 0 1rem;
      text-decoration: none;
    }
    nav a:hover, nav a.active {
      text-decoration: underline;
    }
    section {
      max-width: 1000px;
      margin: 2rem auto;
      padding: 0 1.5rem;
    }
    .event-box {
      background: url('https://media.istockphoto.com/id/1488114900/photo/modern-digital-background-with-code-and-pcb-empty-space-for-text-or-brand-placement.jpg?s=612x612&w=0&k=20&c=3r__Vo8akIx9BCL6RfeHG5DTu8u8H8PXSf8ZqoWlx7M=') no-repeat center center/cover;
      padding: 2rem;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(255, 255, 255);
      margin-bottom: 2rem;
      color: white;
    }
    .event-box h2, .event-box p, .event-box ul {
      color: white;
    }
    button {
      padding: 0.7rem 1.5rem;
      background: #007bff;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      margin-top: 1rem;
    }
    button:hover {
      background: #0056b3;
    }
    .footer {
      background: #343a40;
      color: white;
      text-align: center;
      padding: 1rem;
      margin-top: 2rem;
    }
  </style>
</head>
<body>
  <header>
    <div><strong>CSIT Events Hub</strong></div>
    <nav>
      <a href="home.html">Home</a>
      <a href="events.php" class="active">Events</a>
      <a href="about.html">About</a>
      <a href="contact.html">Contact</a>
    </nav>
  </header>

  <section>
    <?php if ($result->num_rows > 0): ?>
      <?php while($row = $result->fetch_assoc()): ?>
        <div class="event-box">
          <h2><?= htmlspecialchars($row['title']) ?></h2>
          <p><strong>Date:</strong> <?= htmlspecialchars($row['date']) ?></p>
          <p><strong>Time:</strong> <?= htmlspecialchars($row['time']) ?></p>
          <p><strong>Venue:</strong> <?= htmlspecialchars($row['venue']) ?></p>
          <p><?= nl2br(htmlspecialchars($row['description'])) ?></p>
          <button onclick="window.location.href='register.html'">Register Now</button>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p style="text-align:center;">No events found.</p>
    <?php endif; ?>
  </section>

  <footer class="footer">
    <p>&copy; 2025 CSIT Events Hub | Developed by BSc CSIT 5th Semester</p>
  </footer>
</body>
</html>
