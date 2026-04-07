<?php
session_start();
include "includes/db_connect.php";

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != "Doctor"){
    header("Location: login.php");
    exit();
}

$doctor_id = $_SESSION['user_id'];

/* UPDATE STATUS */
if(isset($_POST['update'])){
    $appointment_id = $_POST['appointment_id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE appointments SET status=? WHERE appointment_id=? AND doctor_id=?");
    $stmt->bind_param("sii",$status,$appointment_id,$doctor_id);
    
    if($stmt->execute()){
        $_SESSION['toast'] = ['type' => 'success', 'message' => 'Appointment status updated!'];
    } else {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'Failed to update status.'];
    }
}

/* GET APPOINTMENTS */
$appointments = $conn->query("
    SELECT a.*, u.name as patient_name
    FROM appointments a
    JOIN users u ON a.patient_id = u.user_id
    WHERE a.doctor_id = $doctor_id
    ORDER BY a.appointment_date DESC, a.appointment_time DESC
");

$page_title = "My Appointments";
include "includes/header.php";
?>

<div class="dashboard">
    <?php include "includes/sidebar.php"; ?>

<div class="main-content">

<h2>My Appointments</h2>

<div class="table-wrapper">
<table class="styled-table">
<thead>
<tr>
<th>Patient</th>
<th>Date</th>
<th>Time</th>
<th>Reason</th>
<th>Status</th>
<th>Update</th>
</tr>
</thead>
<tbody>

<?php while($row = $appointments->fetch_assoc()){ ?>
<tr>
<td><?php echo htmlspecialchars($row['patient_name']); ?></td>
<td><?php echo $row['appointment_date']; ?></td>
<td><?php echo $row['appointment_time']; ?></td>
<td><?php echo htmlspecialchars($row['reason']); ?></td>
<td>
    <span class="status-badge status-<?php echo $row['status']; ?>">
        <?php echo $row['status']; ?>
    </span>
</td>
<td>
<form method="POST" style="display:flex; gap:10px; align-items:center;">
<input type="hidden" name="appointment_id" value="<?php echo $row['appointment_id']; ?>">
<select name="status" style="margin-bottom:0;">
<option value="Scheduled" <?php if($row['status'] == 'Scheduled') echo 'selected'; ?>>Scheduled</option>
<option value="Completed" <?php if($row['status'] == 'Completed') echo 'selected'; ?>>Completed</option>
<option value="Rejected" <?php if($row['status'] == 'Rejected') echo 'selected'; ?>>Rejected</option>
</select>
<button name="update" class="action-btn">Update</button>
</form>
</td>
</tr>
<?php } ?>

</tbody>
</table>
</div>

</div>
</div>

<?php include "includes/footer.php"; ?>
