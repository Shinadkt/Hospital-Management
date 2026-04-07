<?php
include "includes/db_connect.php";

if(isset($_POST['register'])){

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $role = "Patient"; // force role

    $stmt = $conn->prepare("INSERT INTO users (name,email,phone,address,password,role) VALUES (?,?,?,?,?,?)");
    $stmt->bind_param("ssssss",$name,$email,$phone,$address,$password,$role);

    if($stmt->execute()){
        $_SESSION['toast'] = ['type' => 'success', 'message' => 'Account created! Please login.'];
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'Email or Phone already exists.'];
    }
}
$page_title = "Signup";
include "includes/header.php";
?>
<script>
function togglePassword(){
    var pass = document.getElementById("password");
    if(pass.type === "password"){
        pass.type = "text";
    } else {
        pass.type = "password";
    }
}
</script>

<div class="login-container">
    <div class="login-card">
        <h2>Create Patient Account</h2>

        <form method="POST">

            <input type="text" name="name" placeholder="Full Name *" required>

            <input type="email" name="email" placeholder="Email Address *" required>

            <input type="text" name="phone" placeholder="Phone Number *" required>

            <input type="text" name="address" placeholder="Address">

            <input type="password" id="password" name="password" placeholder="Password *" required>

            <div style="text-align:left; margin-bottom:15px;">
                <input type="checkbox" onclick="togglePassword()"> Show Password
            </div>

            <button name="register" class="login-btn">Register</button>

            <div class="login-links">
                <a href="index.php">Home</a>
                <span>|</span>
                <a href="login.php">Already have account?</a>
            </div>

        </form>
    </div>

<?php include "includes/footer.php"; ?>