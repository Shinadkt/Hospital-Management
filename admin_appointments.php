<?php
session_start();
include "includes/db_connect.php";

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != "Admin"){
    header("Location: login.php");
    exit();
}

/* FILTER LOGIC */
$where = "";

if(isset($_GET['date']) && $_GET['date'] != ""){
    $date = $conn->real_escape_string($_GET['date']);
    $where .= " AND a.appointment_date='$date'";
}

if(isset($_GET['doctor']) && $_GET['doctor'] != ""){
    $doctor = (int)$_GET['doctor'];
    $where .= " AND a.doctor_id='$doctor'";
}

/* FETCH APPOINTMENTS */
$appointments = $conn->query("
SELECT a.*, 
p.name AS patient_name,
d.name AS doctor_name
FROM appointments a
JOIN users p ON a.patient_id = p.user_id
JOIN users d ON a.doctor_id = d.user_id
WHERE 1=1 $where
ORDER BY a.appointment_date DESC, a.appointment_time DESC
");

/* FETCH DOCTORS FOR FILTER */
$doctors = $conn->query("SELECT user_id,name FROM users WHERE role='Doctor'");

$page_title = "All Appointments";
include "includes/header.php";
?>

<div class="dashboard">
    <?php include "includes/sidebar.php"; ?>

<!-- MAIN CONTENT -->
<div class="main-content">

<h2>All Appointments</h2>

<!-- FILTER FORM -->
<form class="filter-form" method="GET">
    
    <input type="date" name="date">

    <select name="doctor">
        <option value="">All Doctors</option>
        <?php while($doc = $doctors->fetch_assoc()){ ?>
            <option value="<?php echo $doc['user_id']; ?>">
                Dr. <?php echo $doc['name']; ?>
            </option>
        <?php } ?>
    </select>

    <button class="action-btn">Filter</button>
</form>

<!-- APPOINTMENT TABLE -->
<div class="table-wrapper">
<table class="styled-table">

<thead>
<tr>
    <th>Patient</th>
    <th>Doctor</th>
    <th>Date</th>
    <th>Time</th>
    <th>Reason</th>
    <th>Status</th>
</tr>
</thead>

<tbody>

<?php while($row = $appointments->fetch_assoc()){ ?>
<tr>
    <td><?php echo htmlspecialchars($row['patient_name']); ?></td>
    <td>Dr. <?php echo htmlspecialchars($row['doctor_name']); ?></td>
    <td><?php echo $row['appointment_date']; ?></td>
    <td><?php echo $row['appointment_time']; ?></td>
    <td><?php echo htmlspecialchars($row['reason']); ?></td>
    <td>
        <span class="status-badge status-<?php echo $row['status']; ?>">
            <?php echo $row['status']; ?>
        </span>
    </td>
</tr>
<?php } ?>

</tbody>

</table>
</div>

</div>
</div>

<?php include "includes/footer.php"; ?>