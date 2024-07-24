//Develop a dynamic blog website  allowing
users to create, read, update, and delete
blog posts.


<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "blog";


$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'create') {
  $title = $_POST['title'];
  $content = $_POST['content'];

  $stmt = $conn->prepare("INSERT INTO posts (title, content) VALUES (?, ?)");
  $stmt->bind_param("ss", $title, $content);
  $stmt->execute();
  $stmt->close();

  header("Location: index.php");
  exit();
}


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
  font-size: 0.9em;
}

  </style>
</head>
<body>
  <div class="container">
    <h1>Blog</h1>
    <nav>
      <a href="index.php">Home</a>
      <a href="index.php?action=create">Create New Post</a>
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
      <a href="index.php">Back to Posts</a>
    <?php
    
    elseif (isset($_GET['action']) && $_GET['action'] == 'edit'):
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
      <a href="index.php">Back to Posts</a>
    <?php
  
    elseif (isset($_GET['id'])):
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
      <a href="index.php?action=edit&id=<?php echo $post['id']; ?>">Edit<br></a>
      <form method="POST" action="index.php" style="display:inline;">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
        <button type="submit">Delete</button>
      </form>
      <a href="index.php"><br><br>Back to Posts</a>
    <?php
    
    else:
      $result = $conn->query("SELECT * FROM posts ORDER BY created_at DESC");
      while ($row = $result->fetch_assoc()):
        ?>
        <div class="post">
          <h2><a href="index.php?id=<?php echo $row['id']; ?>"><?php echo $row['title']; ?></a></h2>
          <p><?php echo substr($row['content'], 0, 100); ?>...</p>
        </div>
      <?php endwhile; ?>
    <?php endif; ?>
    <hr>
    <div class="footer">
      <p>&copy; Blog</p>
    </div>
  </div>
</body>
</html>
