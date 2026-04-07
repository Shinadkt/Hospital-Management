<?php
session_start();
include "includes/db_connect.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* FETCH USER DATA */
$stmt = $conn->prepare("SELECT name,email,phone,address,role FROM users WHERE user_id=?");
$stmt->bind_param("i",$user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

/* UPDATE PROFILE */
if(isset($_POST['update_profile'])){

    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $update = $conn->prepare("UPDATE users SET name=?,email=?,phone=?,address=? WHERE user_id=?");
    $update->bind_param("ssssi",$name,$email,$phone,$address,$user_id);
    if($update->execute()){
        $_SESSION['toast'] = ['type' => 'success', 'message' => 'Profile updated successfully.'];
        // Refresh session name if updated
        $_SESSION['name'] = $name;
    } else {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'Error updating profile. Phone might already be in use.'];
    }
}

/* CHANGE PASSWORD */
if(isset($_POST['change_password'])){

    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    // Retrieve active hash
    $stmt = $conn->prepare("SELECT password FROM users WHERE user_id=?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $hash = $stmt->get_result()->fetch_assoc()['password'];

    if(password_verify($current, $hash)){
        if($new === $confirm){
            $new_hash = password_hash($new, PASSWORD_DEFAULT);
            $pass = $conn->prepare("UPDATE users SET password=? WHERE user_id=?");
            $pass->bind_param("si", $new_hash, $user_id);
            $pass->execute();
            $_SESSION['toast'] = ['type' => 'success', 'message' => 'Password changed successfully.'];
        } else {
            $_SESSION['toast'] = ['type' => 'error', 'message' => 'New passwords do not match.'];
        }
    } else {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'Current password is incorrect.'];
    }
}

$page_title = "My Profile";
include "includes/header.php";
?>

<div class="dashboard">
    <?php include "includes/sidebar.php"; ?>

<div class="main-content">

<h2>My Profile</h2>

<div class="dashboard-cards">

<div class="card">

<h3>Profile Information</h3>

<form method="POST">

<input type="text" name="name"
value="<?php echo $user['name']; ?>"
placeholder="Full Name" required>

<br><br>

<input type="email" name="email"
value="<?php echo $user['email']; ?>"
required>

<br><br>

<input type="text" name="phone"
value="<?php echo $user['phone']; ?>"
placeholder="Phone Number" required>

<br><br>

<textarea name="address"
placeholder="Address"><?php echo $user['address']; ?></textarea>

<br><br>

<button name="update_profile" class="login-btn">
Update Profile
</button>

</form>

</div>


<div class="card">

<h3>Change Password</h3>

<form method="POST">

<input type="password" name="current_password" placeholder="Current Password" required>

<br><br>

<input type="password" id="password" name="new_password" placeholder="New Password" required>

<br><br>

<input type="password" id="confirm_password" name="confirm_password" placeholder="Re-type New Password" required>

<br><br>

<label>
<input type="checkbox" onclick="togglePassword()">
Show Password
</label>

<br><br>

<button name="change_password"
class="action-btn">
Update Password
</button>

</form>

</div>

</div>

</div>

</div>

<script>
function togglePassword(){
    var p = document.getElementById("password");
    var c = document.getElementById("confirm_password");
    if(p.type === "password"){
        p.type = "text";
        c.type = "text";
    } else {
        p.type = "password";
        c.type = "password";
    }
}
</script>

<?php include "includes/footer.php"; ?>
```
