<?php
// Connect to database
$conn = new mysqli("localhost", "root", "", "csit_events");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Insert event
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["save_event"])) {
  $title = $_POST["title"];
  $description = $_POST["description"];
  $date = $_POST["date"];
  $time = $_POST["time"];
  $venue = $_POST["venue"];

  if (isset($_POST["event_id"]) && $_POST["event_id"] != "") {
    // Update
    $id = $_POST["event_id"];
    $sql = "UPDATE events SET title='$title', description='$description', date='$date', time='$time', venue='$venue' WHERE id=$id";
  } else {
    // Create
    $sql = "INSERT INTO events (title, description, date, time, venue)
            VALUES ('$title', '$description', '$date', '$time', '$venue')";
  }

  $conn->query($sql);
  header("Location: admin_dashboard.php");
  exit();
}

// Delete event
if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  $conn->query("DELETE FROM events WHERE id=$id");
  header("Location: admin_dashboard.php");
  exit();
}

// Fetch all events
$events = $conn->query("SELECT * FROM events");

// Fetch single event for editing
$edit_event = null;
if (isset($_GET['edit'])) {
  $id = $_GET['edit'];
  $result = $conn->query("SELECT * FROM events WHERE id=$id");
  $edit_event = $result->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard - CSIT Events Hub</title>
  <style>
    body { margin: 0; font-family: Arial, sans-serif; background: #f1f1f1; }
    header {
      background: #343a40; color: white; padding: 1rem 2rem;
      display: flex; justify-content: space-between; align-items: center;
    }
    .dashboard {
      display: grid; grid-template-columns: 250px 1fr;
      min-height: calc(100vh - 70px);
    }
    .sidebar {
      background: #495057; color: white; padding: 2rem 1rem;
    }
    .sidebar h2 { margin-top: 0; }
    .sidebar a {
      color: white; display: block; margin: 1rem 0;
      text-decoration: none;
    }
    .sidebar a:hover { text-decoration: underline; }
    .main {
      padding: 2rem;
    }
    .form-container {
      background: white; padding: 1.5rem; border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 2rem;
    }
    input, textarea {
      width: 100%; padding: 0.6rem; margin-bottom: 1rem;
      border: 1px solid #ccc; border-radius: 5px;
    }
    button {
      padding: 0.6rem 1.2rem; background: #007bff; color: white;
      border: none; border-radius: 5px; cursor: pointer;
    }
    button:hover { background: #0056b3; }
    table {
      width: 100%; border-collapse: collapse; background: white;
    }
    th, td {
      padding: 0.8rem; border: 1px solid #ddd; text-align: left;
    }
    th { background: #007bff; color: white; }
    .actions button {
      margin-right: 0.5rem; background: #ffc107; color: black;
    }
    .actions .delete {
      background: #dc3545; color: white;
    }
  </style>
</head>
<body>
  <header>
    <h1>Admin Dashboard</h1>
    <a href="logout.php" style="color: white; text-decoration: underline;">Logout</a>
  </header>
  <div class="dashboard">
    <aside class="sidebar">
      <h2>Menu</h2>
      <a href="#create-event">Create/Update Event</a>
      <a href="#event-list">Manage Events</a>
    </aside>
    <main class="main">
      <section id="create-event" class="form-container">
        <h3><?php echo $edit_event ? "Edit Event" : "Create Event"; ?></h3>
        <form method="POST" action="admin_dashboard.php">
          <input type="hidden" name="event_id" value="<?php echo $edit_event['id'] ?? ''; ?>" />
          <input type="text" placeholder="Event Title" name="title" required value="<?php echo $edit_event['title'] ?? ''; ?>" />
          <textarea placeholder="Event Description" name="description" rows="4" required><?php echo $edit_event['description'] ?? ''; ?></textarea>
          <input type="date" name="date" required value="<?php echo $edit_event['date'] ?? ''; ?>" />
          <input type="time" name="time" required value="<?php echo $edit_event['time'] ?? ''; ?>" />
          <input type="text" name="venue" placeholder="Venue" required value="<?php echo $edit_event['venue'] ?? ''; ?>" />
          <button type="submit" name="save_event"><?php echo $edit_event ? "Update" : "Save"; ?> Event</button>
        </form>
      </section>

      <section id="event-list">
        <h3>Event List</h3>
        <table>
          <thead>
            <tr>
              <th>Title</th>
              <th>Date</th>
              <th>Time</th>
              <th>Venue</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $events->fetch_assoc()): ?>
            <tr>
              <td><?php echo htmlspecialchars($row['title']); ?></td>
              <td><?php echo $row['date']; ?></td>
              <td><?php echo date('h:i A', strtotime($row['time'])); ?></td>
              <td><?php echo htmlspecialchars($row['venue']); ?></td>
              <td class="actions">
                <a href="admin_dashboard.php?edit=<?php echo $row['id']; ?>"><button>Edit</button></a>
                <a href="admin_dashboard.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?');">
                  <button class="delete">Delete</button>
                </a>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </section>
    </main>
  </div>
</body>
</html>
