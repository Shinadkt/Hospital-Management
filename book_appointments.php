<?php
session_start();
include "includes/db_connect.php";

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != "Patient"){
    header("Location: login.php");
    exit();
}

$patient_id = $_SESSION['user_id'];

/* CANCEL APPOINTMENT */
if(isset($_POST['cancel'])){
    $appointment_id = $_POST['appointment_id'];

    $stmt = $conn->prepare("UPDATE appointments 
                            SET status='Cancelled' 
                            WHERE appointment_id=? 
                            AND patient_id=?");
    $stmt->bind_param("ii", $appointment_id, $patient_id);
    $stmt->execute();
}

/* BOOK APPOINTMENT */
if(isset($_POST['book'])){

    $doctor_id = $_POST['doctor_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $reason = $_POST['reason'];

    /* CHECK DOUBLE BOOKING */
    $check = $conn->prepare("SELECT appointment_id 
                             FROM appointments
                             WHERE doctor_id=? 
                             AND appointment_date=? 
                             AND appointment_time=? 
                             AND status!='Cancelled'");

    $check->bind_param("iss",$doctor_id,$date,$time);
    $check->execute();
    $result = $check->get_result();

    if($result->num_rows > 0){
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'This time slot is already booked. Please choose another time.'];

    } else {

        $stmt = $conn->prepare("INSERT INTO appointments
        (patient_id, doctor_id, appointment_date, appointment_time, reason)
        VALUES (?,?,?,?,?)");

        $stmt->bind_param("iisss",$patient_id,$doctor_id,$date,$time,$reason);
        $stmt->execute();

        $_SESSION['toast'] = ['type' => 'success', 'message' => 'Appointment booked successfully.'];
    }
}

/* FETCH DOCTORS WITH DEPARTMENT */
$doctors = $conn->query("
SELECT u.user_id, u.name, d.name AS department_name
FROM users u
JOIN doctors dc ON u.user_id = dc.user_id
JOIN departments d ON dc.department_id = d.id
WHERE u.role='Doctor'
");

/* FETCH PATIENT APPOINTMENTS WITH DEPARTMENT */
$appointments = $conn->query("
SELECT a.*, u.name AS doctor_name, d.name AS department_name
FROM appointments a
JOIN users u ON a.doctor_id = u.user_id
JOIN doctors dc ON a.doctor_id = dc.user_id
JOIN departments d ON dc.department_id = d.id
WHERE a.patient_id = $patient_id
ORDER BY a.appointment_date DESC, a.appointment_time DESC
");
$page_title = "My Appointments";
include "includes/header.php";
?>

<div class="dashboard">
    <?php include "includes/sidebar.php"; ?>

<div class="main-content">

<h2>Book Appointment</h2>

<form method="POST" style="max-width:400px; margin-bottom:30px;">

<select name="doctor_id" required>
<option value="">Select Doctor</option>

<?php while($doc = $doctors->fetch_assoc()){ ?>

<option value="<?php echo $doc['user_id']; ?>">
Dr. <?php echo $doc['name']; ?> (<?php echo $doc['department_name']; ?>)
</option>

<?php } ?>

</select><br><br>

<input type="date" name="date" required><br><br>

<input type="time" name="time" required><br><br>

<textarea name="reason" placeholder="Reason for visit" required></textarea><br><br>

<button name="book" class="login-btn">Book Appointment</button>

</form>

<h2>My Appointments</h2>

<div class="table-wrapper">

<table class="styled-table">

<thead>
<tr>
<th>Doctor</th>
<th>Department</th>
<th>Date</th>
<th>Time</th>
<th>Reason</th>
<th>Status</th>
<th>Action</th>
</tr>
</thead>

<tbody>

<?php while($row = $appointments->fetch_assoc()){ ?>

<tr>

<td>Dr. <?php echo htmlspecialchars($row['doctor_name']); ?></td>

<td><?php echo htmlspecialchars($row['department_name']); ?></td>

<td><?php echo $row['appointment_date']; ?></td>

<td><?php echo $row['appointment_time']; ?></td>

<td><?php echo htmlspecialchars($row['reason']); ?></td>

<td>
<span class="status-badge status-<?php echo $row['status']; ?>">
<?php echo $row['status']; ?>
</span>
</td>

<td>

<?php if($row['status']=="Scheduled"){ ?>

<form method="POST">

<input type="hidden" name="appointment_id"
value="<?php echo $row['appointment_id']; ?>">

<button name="cancel"
class="action-btn"
style="background:#dc3545;">
Cancel
</button>

</form>

<?php } ?>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

<?php include "includes/footer.php"; ?>