<?php
session_start();
include "includes/db_connect.php";

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != "Admin"){
    header("Location: login.php");
    exit();
}

/* FETCH DEPARTMENTS */
$departments = $conn->query("SELECT id, name FROM departments");

/* ADD DOCTOR */
if(isset($_POST['add_doctor'])){

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $department_id = $_POST['department_id'];
    $specialization = trim($_POST['specialization']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = "Doctor";

    // Insert user first
    $stmt = $conn->prepare("INSERT INTO users (name,email,phone,password,role) VALUES (?,?,?,?,?)");
    $stmt->bind_param("sssss",$name,$email,$phone,$password,$role);

    if($stmt->execute()){
        $new_user_id = $conn->insert_id;
        
        // Insert doctor profile details
        $doc_stmt = $conn->prepare("INSERT INTO doctors (user_id, full_name, specialization, department_id) VALUES (?,?,?,?)");
        $doc_stmt->bind_param("issi", $new_user_id, $name, $specialization, $department_id);
        $doc_stmt->execute();
        
        $_SESSION['toast'] = ['type' => 'success', 'message' => 'Doctor added successfully!'];
    } else {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'Error adding doctor. Email or phone may already exist.'];
    }
}

$page_title = "Add Doctor";
include "includes/header.php";
?>
<div class="dashboard">
    <?php include "includes/sidebar.php"; ?>

    <div class="main-content">
    <h2>Add New Doctor</h2>

    <form method="POST" style="max-width:400px;">

        <input type="text" name="name" placeholder="Doctor Name" required><br><br>
        <input type="email" name="email" placeholder="Email" required><br><br>
        <input type="text" name="phone" placeholder="Phone Number" required><br><br>

        <select name="department_id" required>
            <option value="">Select Department</option>

            <?php while($dept = $departments->fetch_assoc()){ ?>

            <option value="<?php echo $dept['id']; ?>">
                <?php echo htmlspecialchars($dept['name']); ?>
            </option>

            <?php } ?>

        </select><br><br>

        <input type="text" name="specialization" placeholder="Specialization (e.g., Surgeon)" required><br><br>

        <input type="password" name="password" placeholder="Password" required><br><br>

        <button name="add_doctor" class="login-btn">Add Doctor</button>

    </form>

</div>
</div>

<?php include "includes/footer.php"; ?>