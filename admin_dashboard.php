<?php
session_start();
include "includes/db_connect.php";

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != "Admin"){
    header("Location: login.php");
    exit();
}

$name = $_SESSION['name'];

/* FETCH STATS */
$total_doctors = $conn->query("SELECT COUNT(*) as count FROM users WHERE role='Doctor'")->fetch_assoc()['count'];
$total_patients = $conn->query("SELECT COUNT(*) as count FROM users WHERE role='Patient'")->fetch_assoc()['count'];
$total_appointments = $conn->query("SELECT COUNT(*) as count FROM appointments")->fetch_assoc()['count'];

/* CHART DATA: Doctors per Department */
$dept_query = $conn->query("SELECT d.name, COUNT(dc.doctor_id) as count FROM departments d LEFT JOIN doctors dc ON d.id=dc.department_id GROUP BY d.id");
$dept_labels = [];
$dept_counts = [];
while($row = $dept_query->fetch_assoc()){
    $dept_labels[] = $row['name'];
    $dept_counts[] = $row['count'];
}

$page_title = "Admin Dashboard";
include "includes/header.php";
?>

<div class="dashboard">
    <?php include "includes/sidebar.php"; ?>

    <div class="main-content">
        <h2>Welcome Admin, <?php echo htmlspecialchars($name); ?> 👑</h2>

        <!-- STAT CARDS -->
        <div class="stat-cards">
            <div class="stat-card">
                <h3><?php echo $total_doctors; ?></h3>
                <p>Registered Doctors</p>
            </div>
            <div class="stat-card secondary">
                <h3><?php echo $total_patients; ?></h3>
                <p>Registered Patients</p>
            </div>
            <div class="stat-card tertiary">
                <h3><?php echo $total_appointments; ?></h3>
                <p>Total Appointments</p>
            </div>
        </div>

        <div class="dashboard-cards">
            <div class="card">
                <h4>Add Doctor</h4>
                <p>Create new doctor account</p>
                <a href="add_doctor.php">Open</a>
            </div>

            <div class="card">
                <h4>View Appointments</h4>
                <p>Monitor hospital bookings</p>
                <a href="admin_appointments.php">Open</a>
            </div>
        </div>

        <!-- DATA VISUALIZATION -->
        <div class="chart-grid">
            <div class="chart-container">
                <h3>Doctors per Department <i class="fa-solid fa-chart-pie" style="color:var(--primary);"></i></h3>
                <div class="chart-wrapper">
                    <canvas id="deptChart"></canvas>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    new Chart(document.getElementById('deptChart'), {
        type: 'pie',
        data: {
            labels: <?php echo json_encode($dept_labels); ?>,
            datasets: [{
                data: <?php echo json_encode($dept_counts); ?>,
                backgroundColor: ['#4F46E5', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899', '#06B6D4'],
                borderWidth: 0,
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