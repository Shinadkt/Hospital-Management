<?php
session_start();
include "includes/db_connect.php";

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != "Doctor"){
    header("Location: login.php");
    exit();
}

$doctor_id = $_SESSION['user_id'];

/* FETCH DOCTOR STATS */
$total_assigned = $conn->query("SELECT COUNT(DISTINCT patient_id) as count FROM appointments WHERE doctor_id = $doctor_id")->fetch_assoc()['count'];
$upcoming_appt = $conn->query("SELECT COUNT(*) as count FROM appointments WHERE doctor_id = $doctor_id AND status='Scheduled'")->fetch_assoc()['count'];
$completed_appt = $conn->query("SELECT COUNT(*) as count FROM appointments WHERE doctor_id = $doctor_id AND status='Completed'")->fetch_assoc()['count'];


/* CHART DATA: Appointments Status Breakdown */
$status_query = $conn->query("SELECT status, COUNT(*) as count FROM appointments WHERE doctor_id = $doctor_id GROUP BY status");
$status_labels = [];
$status_counts = [];
while($row = $status_query->fetch_assoc()){
    $status_labels[] = $row['status'];
    $status_counts[] = $row['count'];
}

$page_title = "Doctor Dashboard";
include "includes/header.php";
?>

<div class="dashboard">
    <?php include "includes/sidebar.php"; ?>

<div class="main-content">

<h2>Welcome, Dr. <?php echo htmlspecialchars($_SESSION['name']); ?> 👨‍⚕️</h2>

<!-- STAT CARDS -->
<div class="stat-cards">
    <div class="stat-card">
        <h3><?php echo $total_assigned; ?></h3>
        <p>Total Unique Patients</p>
    </div>
    <div class="stat-card secondary">
        <h3><?php echo $upcoming_appt; ?></h3>
        <p>Upcoming Appointments</p>
    </div>
    <div class="stat-card tertiary">
        <h3><?php echo $completed_appt; ?></h3>
        <p>Completed Consultations</p>
    </div>
</div>

<div class="dashboard-cards" style="margin-bottom: 40px; margin-top: 30px;">
    <div class="card">
        <h3>My Appointments</h3>
        <p>View agenda and update consultation statuses.</p>
        <a href="doctor_appointment.php" class="btn btn-primary" style="margin-top:15px; display:inline-block;">View Appointments</a>
    </div>

    <div class="card">
        <h3>My Profile</h3>
        <p>Manage your account details and password.</p>
        <a href="profile.php" class="btn btn-secondary" style="margin-top:15px; display:inline-block;">Edit Profile</a>
    </div>
</div>

<!-- DATA VISUALIZATION -->
<div class="chart-grid" style="margin-top: 40px;">
    <div class="chart-container">
        <h3>Consultation Status Breakdown <i class="fa-solid fa-chart-donut" style="color:var(--primary);"></i></h3>
        <div class="chart-wrapper">
            <canvas id="statusChart"></canvas>
        </div>
    </div>
</div>

</div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode($status_labels); ?>,
            datasets: [{
                data: <?php echo json_encode($status_counts); ?>,
                backgroundColor: ['#F59E0B', '#10B981', '#EF4444'], // Scheduled: Amber, Completed: Green, Rejected: Red
                borderWidth: 0,
                cutout: '70%',
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'right' }
            }
        }
    });
});
</script>

<?php include "includes/footer.php"; ?>