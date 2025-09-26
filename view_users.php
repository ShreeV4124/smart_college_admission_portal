<?php
require_once 'db_functions.php';
require_once 'session.php';
require_once 'layout.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$currentUser = $_SESSION['user'];   ?>

<link rel="stylesheet" href="styles/view.css">
<link rel="stylesheet" href="styles/dashboard.css">

<?php

renderPage("All Faculty", function() use ($currentUser) {
    $sql = "SELECT * FROM users";
    $users = getVal($sql,[],true);
?>


<table border="1" cellpadding="12">
    <tr>
        <th>User ID</th>
        <th>Faculty Name</th>
        <th>Email</th>
        <th>Role</th>
        <?php if ($currentUser['role'] == 'admin' || $currentUser['role'] == 'faculty') { ?>
            <th>Action</th>
        <?php } ?>
    </tr>

    <?php foreach ($users as $user): ?>
        <tr>
            <td><?= htmlspecialchars($user['user_id']) ?></td>
            <td><?= htmlspecialchars($user['username']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td><?= htmlspecialchars($user['role']) ?></td>
            <?php
            if($currentUser['role'] == 'admin' || $currentUser['role'] == 'faculty'){
                if ($currentUser['role'] == 'admin' || $currentUser['user_id'] == $user['user_id']) { ?>
                    <td>
                        <a href="edit.php?id=<?= $user['user_id'] ?>&type=user">Edit</a>


                        <a href="delete.php?id=<?= $user['user_id'] ?>">Delete</a>
                    </td>
            <?php 
                }        
            } ?>
        </tr>
    <?php endforeach; ?>
</table>

<?php
});
?>
