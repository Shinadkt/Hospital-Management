<?php $page_title = "Login"; include "includes/header.php"; ?>
<script>
function togglePassword(){
    var pass = document.getElementById("password");
    pass.type = pass.type === "password" ? "text" : "password";
}
</script>

<div class="login-container">

    <div class="login-card">
        <h2>Welcome Back</h2>

        <form method="POST" action="auth.php">

            <div class="role-toggle">
                <input type="radio" name="role" value="Patient" checked id="patient">
                <label for="patient">Patient</label>

                <input type="radio" name="role" value="Doctor" id="doctor">
                <label for="doctor">Doctor</label>

                <input type="radio" name="role" value="Admin" id="admin">
                <label for="admin">Admin</label>
            </div>

            <input type="text" name="login_input" placeholder="Email Address or Phone Number" required>
            <input type="password" id="password" name="password" placeholder="Password" required>

            <div style="text-align:left; margin-bottom:15px;">
                <input type="checkbox" onclick="togglePassword()"> Show Password
            </div>

            <button name="login" class="login-btn">Login</button>

            <div class="login-links">
                <a href="index.php">Home</a>
                <span>|</span>
                <a href="signup.php">Create Account</a>
            </div>

        </form>
    </div>

</div>

<?php include "includes/footer.php"; ?>