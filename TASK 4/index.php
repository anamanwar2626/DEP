//Develop a dynamic blog website  allowing
users to create, read, update, and delete
blog posts.
<?php
// Database Configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "blog";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Handle Create
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'create') {
  $title = $_POST['title'];
  $content = $_POST['content'];

  $stmt = $conn->prepare("INSERT INTO posts (title, content) VALUES (?, ?)");
  $stmt->bind_param("ss", $title, $content);
  $stmt->execute();
  $stmt->close();

  $stmt = $conn->prepare("INSERT INTO notifications (message) VALUES (?)");
  $stmt->bind_param("s", $message);
  $message = "New post created: " . $title;
  $stmt->execute();
  $stmt->close();

  header("Location: index.php");
  exit();
}

// Handle Update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update') {
  $id = $_POST['id'];
  $title = $_POST['title'];
  $content = $_POST['content'];

  $stmt = $conn->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ?");
  $stmt->bind_param("ssi", $title, $content, $id);
  $stmt->execute();
  $stmt->close();

  header("Location: index.php");
  exit();
}

// Handle Delete
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete') {
  $id = $_POST['id'];

  $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $stmt->close();

  header("Location: index.php");
  exit();
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Blog</title>
  <style>

body {
  font-family: 'Roboto', 'Helvetica Neue', Helvetica, Arial, sans-serif;
  margin: 0;
  padding: 0;
  background-color: #f8f9fa;
  color: #343a40;
  line-height: 1.6;
}


.container {
  max-width: 900px;
  margin: 30px auto;
  background: #fff;
  padding: 30px;
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
  border-radius: 10px;
}

h1 {
  color: #007bff;
  text-align: center;
  margin-bottom: 30px;
  font-size: 2.5em;
}


nav {
  background-color: #007bff;
  padding: 15px;
  text-align: center;
  border-radius: 10px;
  margin-bottom: 30px;
}

nav a {
  margin: 0 15px;
  text-decoration: none;
  color: white;
  padding: 12px 25px;
  border-radius: 5px;
  background-color: #0056b3;
  transition: background-color 0.3s, transform 0.3s;
}

nav a:hover {
  background-color: #003f7f;
  transform: scale(1.1);
}


hr {
  margin: 30px 0;
  border: none;
  border-top: 1px solid #ddd;
}


.post {
  margin-bottom: 30px;
  padding: 25px;
  border-bottom: 1px solid #ddd;
  border-left: 5px solid #007bff;
  background: #f8f9fa;
  border-radius: 5px;
}

.post h2 {
  margin: 0 0 15px;
  color: #007bff;
  font-size: 1.8em;
}

.post p {
  color: #495057;
}


form {
  margin-bottom: 30px;
}

form label {
  display: block;
  margin-top: 15px;
  color: #007bff;
  font-weight: bold;
}

form input[type="text"], form textarea {
  width: 100%;
  padding: 12px;
  margin-top: 8px;
  border: 1px solid #ddd;
  border-radius: 4px;
  background: #e9ecef;
}

form button {
  margin-top: 15px;
  padding: 12px 25px;
  background: #007bff;
  border: none;
  color: white;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s, transform 0.3s;
}

form button:hover {
  background: #0056b3;
  transform: scale(1.05);
}


a.button, form button {
  display: inline-block;
  padding: 12px 25px;
  margin-top: 15px;
  background-color: #007bff;
  color: white;
  text-decoration: none;
  border-radius: 4px;
  transition: background-color 0.3s, transform 0.3s;
}

a.button:hover, form button:hover {
  background-color: #0056b3;
  transform: scale(1.05);
}


.footer {
  text-align: center;
  margin-top: 30px;
  color: #6c757d;
  font-size: 0.9em;}
  /* Notification Styles */
.notification-container {
  background: #f0f2f5;
  padding: 10px;
  border-radius: 5px;
  margin-bottom: 20px;
}

.notification {
  background: #007BFF;
  color: white;
  padding: 10px;
  margin: 5px 0;
  border-radius: 5px;
}
/* Popup Notification Styles */
.notification-popup {
  position: fixed;
  top: 20px;
  right: 20px;
  background: #007BFF;
  color: white;
  padding: 15px;
  border-radius: 5px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  z-index: 1000;
  opacity: 1;
  transition: opacity 0.5s ease;
}

.notification-popup.fade-out {
  opacity: 0;
}
/* Chat Box Styles */
.chat-container {
  background: #f4f4f4;
  padding: 20px;
  border-radius: 10px;
  margin-top: 20px;
}

#chat-box {
  background: #fff;
  height: 300px;
  overflow-y: scroll;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 10px;
  margin-bottom: 10px;
}

.chat-message {
  margin-bottom: 10px;
}

#chat-form {
  display: flex;
  flex-direction: column;
}

#chat-form input, #chat-form textarea {
  margin-bottom: 10px;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
}

#chat-form button {
  background: #007BFF;
  color: #fff;
  padding: 10px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

#chat-form button:hover {
  background: #0056b3;
}
}

  </style>
</head>
<body>
  <div class="container">
    <h1>Blog</h1>
    <nav>
      <a href="index.php">Home</a>
      <a href="index.php?action=create">Create New Post</a>
      <a href="#" id="notification-link">Notifications</a>
    </nav>
    <hr>
    <?php
    if (isset($_GET['action']) && $_GET['action'] == 'create'): ?>
      <h2>Create New Post</h2>
      <form method="POST" action="index.php">
        <input type="hidden" name="action" value="create">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title">
        <label for="content">Content:</label>
        <textarea id="content" name="content"></textarea>
        <button type="submit">Create</button>
      </form>
      <a href="index.php" class="button">Back to Posts</a>
    <?php elseif (isset($_GET['action']) && $_GET['action'] == 'edit'):
      $id = $_GET['id'];
      $stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
      $stmt->bind_param("i", $id);
      $stmt->execute();
      $result = $stmt->get_result();
      $post = $result->fetch_assoc();
      $stmt->close();
      ?>
      <h2>Edit Post</h2>
      <form method="POST" action="index.php">
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" value="<?php echo $post['title']; ?>">
        <label for="content">Content:</label>
        <textarea id="content" name="content"><?php echo $post['content']; ?></textarea>
        <button type="submit">Update</button>
      </form>
      <a href="index.php" class="button">Back to Posts</a>
    <?php elseif (isset($_GET['id'])):
      $id = $_GET['id'];
      $stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
      $stmt->bind_param("i", $id);
      $stmt->execute();
      $result = $stmt->get_result();
      $post = $result->fetch_assoc();
      $stmt->close();
      ?>
      <h2><?php echo $post['title']; ?></h2>
      <p><?php echo $post['content']; ?></p>
      <a href="index.php?action=edit&id=<?php echo $post['id']; ?>" class="button">Edit</a>
      <form method="POST" action="index.php" style="display:inline;">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
        <button type="submit" class="button">Delete</button>
      </form>
      <a href="index.php" class="button">Back to Posts</a>
    <?php else:
      $result = $conn->query("SELECT * FROM posts ORDER BY created_at DESC");
      while ($row = $result->fetch_assoc()):
        ?>
        <div class="post">
          <h2><?php echo $row['title']; ?></h2>
          <p><?php echo $row['content']; ?></p>
          <a href="index.php?id=<?php echo $row['id']; ?>" class="button">Read More</a>
        </div>
      <?php endwhile; ?>
    <?php endif; ?>
  </div>
</div>

  <div class="chat-container">
    <h2>Chat</h2>
    <div id="chat-box"></div>
    <form id="chat-form">
      <input type="text" id="username" placeholder="Username">
      <textarea id="chat-message" placeholder="Type your message here..."></textarea>
      <button type="submit">Send</button>
    </form>
  </div>

  <script>
  document.addEventListener("DOMContentLoaded", function() {
    function fetchMessages() {
      fetch('get_messages.php')
        .then(response => response.json())
        .then(data => {
          const chatBox = document.getElementById('chat-box');
          chatBox.innerHTML = '';
          data.forEach(message => {
            const div = document.createElement('div');
            div.className = 'chat-message';
            div.innerHTML = `<strong>${message.username}:</strong> ${message.message} <em>${message.created_at}</em>`;
            chatBox.appendChild(div);
          });
          chatBox.scrollTop = chatBox.scrollHeight;
        });
    }

    document.getElementById('chat-form').addEventListener('submit', function(event) {
      event.preventDefault();
      const username = document.getElementById('username').value;
      const message = document.getElementById('chat-message').value;

      fetch('send_message.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ username, message })
      }).then(response => response.json())
        .then(data => {
          fetchMessages();
          document.getElementById('chat-message').value = '';
        });
    });

    // Fetch messages initially
    fetchMessages();

    // Fetch messages every 5 seconds
    setInterval(fetchMessages, 5000);
  });
  </script>
  <script>
  document.addEventListener("DOMContentLoaded", function() {
    function fetchNotifications() {
      fetch('get_notifications.php')
        .then(response => response.json())
        .then(data => {
          data.forEach(notification => {
            showNotification(notification.message);
          });
        });
    }

    function showNotification(message) {
      const notificationPopup = document.createElement('div');
      notificationPopup.className = 'notification-popup';
      notificationPopup.textContent = message;

      document.body.appendChild(notificationPopup);

      setTimeout(() => {
        notificationPopup.classList.add('fade-out');
        setTimeout(() => {
          notificationPopup.remove();
        }, 500); // Match this to the CSS transition duration
      }, 3000); // Show notification for 3 seconds
    }

    // Fetch notifications initially
    fetchNotifications();

    // Fetch notifications every 5 seconds
    setInterval(fetchNotifications, 5000);
  });
  </script>
</body>
</html>


</body>
</html>
