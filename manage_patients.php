<?php
session_start();
include "includes/db_connect.php";

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != "Admin"){
    header("Location: login.php");
    exit();
}

$users = $conn->query("SELECT user_id,name,email,role FROM users WHERE role!='Admin'");

$page_title = "Manage Users";
include "includes/header.php";
?>

<div class="dashboard">
    <?php include "includes/sidebar.php"; ?>

<div class="main-content">
<h2>Manage User Accounts <span class="reminder-dot" id="notificationDot" title="Click to view notifications" style="cursor: pointer;" onclick="dismissNotification()"></span></h2>

<div class="table-wrapper">
<table class="styled-table">

    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Manage</th>
        </tr>
    </thead>

    <tbody>
    <?php while($row = $users->fetch_assoc()){ ?>
        <tr>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td>
                <span class="role-badge <?php echo strtolower($row['role']); ?>">
                    <?php echo $row['role']; ?>
                </span>
            </td>
            <td>
                <a href="manage_user.php?id=<?php echo $row['user_id']; ?>" class="action-btn" style="text-decoration:none; display:inline-block; padding: 6px 16px;">Manage</a>
            </td>
        </tr>
    <?php } ?>
    </tbody>

</table>
</div>

</div>
</div>

<script>
// Check if the notification was already dismissed in this session
document.addEventListener("DOMContentLoaded", function() {
    if(sessionStorage.getItem("hideNotificationDot") === "true") {
        document.getElementById("notificationDot").style.display = "none";
    }
});

function dismissNotification() {
    // Show the built-in browser alert box
    alert("System Notification: A user has recently changed their password.");
    
    // Hide the dot visually
    document.getElementById("notificationDot").style.display = "none";
    
    // Save to session storage so it doesn't reappear until next login
    sessionStorage.setItem("hideNotificationDot", "true");
}
</script>

<?php include "includes/footer.php"; ?>