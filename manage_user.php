<?php
session_start();
include "includes/db_connect.php";

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != "Admin"){
    header("Location: login.php");
    exit();
}

if(!isset($_GET['id'])){
    header("Location: manage_patients.php");
    exit();
}

$target_id = (int)$_GET['id'];

/* FETCH USER */
$stmt = $conn->prepare("SELECT name, email, phone, address, role FROM users WHERE user_id=? AND role!='Admin'");
$stmt->bind_param("i", $target_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0){
    $_SESSION['toast'] = ['type' => 'error', 'message' => 'User not found or cannot be edited.'];
    header("Location: manage_patients.php");
    exit();
}

$user = $result->fetch_assoc();

/* UPDATE USER DETAILS */
if(isset($_POST['update_user'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $update = $conn->prepare("UPDATE users SET name=?,email=?,phone=?,address=? WHERE user_id=?");
    $update->bind_param("ssssi", $name, $email, $phone, $address, $target_id);
    
    if($update->execute()){
        $_SESSION['toast'] = ['type' => 'success', 'message' => 'User profile updated.'];
        // Refresh local array
        $user['name'] = $name;
        $user['email'] = $email;
        $user['phone'] = $phone;
        $user['address'] = $address;
    } else {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'Failed to update. Email/Phone may exist.'];
    }
}

/* CHANGE PASSWORD */
if(isset($_POST['change_password'])){
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    $pass = $conn->prepare("UPDATE users SET password=? WHERE user_id=?");
    $pass->bind_param("si", $new_password, $target_id);
    
    if($pass->execute()){
        $_SESSION['toast'] = ['type' => 'success', 'message' => 'User password successfully reset.'];
    } else {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'Failed to change password.'];
    }
}

/* DELETE USER */
if(isset($_POST['delete_user'])){
    // Manually cascading the deletion to fully clear database constraints

    // 1. Delete associated appointments (whether they are a doctor or patient)
    $conn->query("DELETE FROM appointments WHERE doctor_id=$target_id OR patient_id=$target_id");

    // 2. Delete doctor record if applicable
    $conn->query("DELETE FROM doctors WHERE user_id=$target_id");

    // 3. Purge user
    $del = $conn->prepare("DELETE FROM users WHERE user_id=?");
    $del->bind_param("i", $target_id);
    
    if($del->execute()){
        $_SESSION['toast'] = ['type' => 'success', 'message' => 'User account permanently deleted.'];
        header("Location: manage_patients.php");
        exit();
    } else {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'Failed to delete user account.'];
    }
}

$page_title = "Manage User";
include "includes/header.php";
?>

<div class="dashboard">
    <?php include "includes/sidebar.php"; ?>

<div class="main-content">

<div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
    <a href="manage_patients.php" class="btn btn-secondary" style="padding: 10px 15px;"><i class="fa-solid fa-arrow-left"></i> Back</a>
    <h2 style="margin: 0;">Managing <?php echo htmlspecialchars($user['name']); ?> (<span class="role-badge <?php echo strtolower($user['role']); ?>"><?php echo $user['role']; ?></span>)</h2>
</div>

<div class="dashboard-cards">

    <!-- EDIT PROFILE -->
    <div class="card">
        <h3>Edit Account Information</h3>
        <form method="POST">
            <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" placeholder="Full Name" required><br><br>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" placeholder="Email Address" required><br><br>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" placeholder="Phone Number" required><br><br>
            <textarea name="address" placeholder="Address"><?php echo htmlspecialchars($user['address']); ?></textarea><br><br>
            <button name="update_user" class="btn btn-primary" style="width: 100%;">Save Changes</button>
        </form>
    </div>

    <!-- SECURITY & DANGEROUS ACTIONS -->
    <div class="card">
        <h3>Security Options</h3>
        <form method="POST" style="margin-bottom: 30px;">
            <input type="password" name="new_password" placeholder="New Password" required><br><br>
            <button name="change_password" class="btn btn-secondary" style="width: 100%;">Reset Password</button>
        </form>

        <hr style="border: 0; border-top: 1px solid var(--border); margin: 25px 0;">

        <h3 style="color: #EF4444;"><i class="fa-solid fa-triangle-exclamation"></i> Danger Zone</h3>
        <p style="font-size: 14px; color: var(--text-light); margin-bottom: 15px;">
            Deleting a user is permanent and will wipe their entire appointment history from the database.
        </p>
        <form method="POST" onsubmit="return confirm('WARNING: Are you sure you want to completely erase this user? This cannot be undone.');">
            <button name="delete_user" class="btn" style="width: 100%; background: #EF4444; color: white;">Permanently Delete Account</button>
        </form>
    </div>

</div>

</div>
</div>

<?php include "includes/footer.php"; ?>
