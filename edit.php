<?php
require_once 'db_functions.php';
require_once 'session.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];
$type = $_GET['type'] ?? 'student';


// Edit for users
if($type === 'user' && $_SESSION['user']['role'] == 'admin' || $_SESSION['user']['role'] == 'faculty'){ 
    $sql = "SELECT username, password, email FROM users WHERE user_id = ?";
    $user = getVal($sql, [$id]);    
?>
    <link rel="stylesheet" href="styles/form.css">

<form method="post">
    <br><h2>Update Page:</h2><hr><br>
    Username: <input name="username" value="<?= htmlspecialchars($user['username']) ?>" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    Email: <input name="email" value="<?= htmlspecialchars($user['email']) ?>" required><br><br>
    
    <input type="submit" value="Update">
</form>
<a href="view.php">⬅ Back to View</a>

<?php

function updateFaculty($id, $username, $password, $email) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username AND user_id != :id");
    $stmt->execute([
        ':username' => $username,
        ':id' => $id
    ]);
    if ($stmt->rowCount() > 0) {
        return "Username already used.";
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email AND user_id != :id");
    $stmt->execute([
        ':email' => $email,
        ':id' => $id
    ]);
    if ($stmt->rowCount() > 0) {
        return "Email already used.";
    }
 
    $stmt = $conn->prepare("UPDATE users SET username = ?, password = ?, email = ? WHERE user_id = ?");
    $stmt->execute([$username, $password,$email, $id]);
    $conn = null;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $result = updateFaculty($id, $_POST['username'], $_POST['password'], $_POST['email']);
    if($result == "")
        echo "Updated successfully. <a href='view_users.php'>Back to List</a>";
    else
        echo $result;
    exit;
}

exit;
}


//for Student Edit
$user = getUserById($id);
if (!$user) { echo "User not found."; exit; }


function updateUser($id, $username, $password, $email, $mobile, $gender, $dob, $marks) {
    $conn = connectDB();
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $conn->prepare("SELECT * FROM students WHERE username = :username AND id != :id");
    $stmt->execute([
        ':username' => $username,
        ':id' => $id
    ]);
    if ($stmt->rowCount() > 0) {
        return "Username already used.";
    }

    $stmt = $conn->prepare("SELECT * FROM students WHERE email = :email AND id != :id");
    $stmt->execute([
        ':email' => $email,
        ':id' => $id
    ]);
    if ($stmt->rowCount() > 0) {
        return "Email already used.";
    }

    $stmt = $conn->prepare("SELECT * FROM students WHERE mobile = :mobile AND id != :id");
    $stmt->execute([
        ':mobile' => $mobile,
        ':id' => $id
    ]);
    if ($stmt->rowCount() > 0) {
        return "Mobile already used.";
    }
 
    $stmt = $conn->prepare("UPDATE students SET username = ?, password = ?, email = ?, mobile = ?, gender=?, dob=?, marks=? WHERE id = ?");
    $stmt->execute([$username, $hashed,$email,$mobile,$gender,$dob,$marks, $id]);
    $conn = null;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $result = updateUser($id, $_POST['username'], $_POST['password'], $_POST['email'], $_POST['mobile'], $_POST['gender'], $_POST['dob'], $_POST['marks']);
    if($result == "")
        echo "Updated successfully. <a href='view.php'>Back to List</a>";
    else
        echo $result;
    exit;
}
?>

<link rel="stylesheet" href="styles/form.css">

<form method="post">
    <br><h2>Update Page:</h2><hr><br>
    Username: <input name="username" value="<?= htmlspecialchars($user['username']) ?>" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    Email: <input name="email" value="<?= htmlspecialchars($user['email']) ?>" required><br><br>
    mobile: <input name="mobile" value="<?= htmlspecialchars($user['mobile']) ?>" required><br><br>
    Gender: <select name="gender" required>
        <option value="<?= htmlspecialchars($user['gender']) ?>">Select</option>
        <option>Male</option>
        <option>Female</option>
        <option>Others</option>
    </select><br><br>   
     dob: <input name="dob" value="<?= htmlspecialchars($user['dob']) ?>" required><br><br>
     Marks: <input name="marks" value="<?= htmlspecialchars($user['marks']) ?>" required><br><br> 

    <input type="submit" value="Update">
</form>
<a href="view.php">⬅ Back to View</a>
