<form method="post" action="submit.php" enctype="multipart/form-data">
  <label for="name">Name:</label>
  <input type="text" name="name" required>

  <label for="email">Email:</label>
  <input type="email" name="email" required>

  <label for="password">Password:</label>
  <input type="password" name="password" required>

  <label for="profile-pic">Profile Picture:</label>
  <input type="file" name="profile-pic" accept="image/*" required>

  <input type="submit" value="Submit">
</form>



<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  $name = $_POST['name'];
  $email = $_POST['email'];
  $password = $_POST['password'];

  if (empty($name) || empty($email) || empty($password)) {
    die('Please fill out all fields.');
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die('Invalid email format.');
  }

  $uploads_dir = 'uploads/';
  $profile_pic_name = uniqid() . '-' . $_FILES['profile-pic']['name'];
  $profile_pic_tmp_name = $_FILES['profile-pic']['tmp_name'];
  move_uploaded_file($profile_pic_tmp_name, $uploads_dir . $profile_pic_name);

  $user_data = [$name, $email, $profile_pic_name];
  $fp = fopen('users.csv', 'a');
  fputcsv($fp, $user_data);
  fclose($fp);


  setcookie('user_name', $name, time() + 3600);

  header('Location: success.php');
  exit();
}
?>


<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Profile Picture</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $fp = fopen('users.csv', 'r');
        while (($user = fgetcsv($fp)) !== false) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($user[0]) . '</td>';
            echo '<td>' . htmlspecialchars($user[1]) . '</td>';
            echo '<td><img src="uploads/' . htmlspecialchars($user[2]) . '"></td>';
            echo '<td>' . htmlspecialchars($user[3]) . '</td>';
            echo '</tr>';
        }
        fclose($fp);
        ?>
    </tbody>
</table>

