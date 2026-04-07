<?php
session_start();
include "includes/db_connect.php";

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != "Patient"){
    header("Location: login.php");
    exit();
}

$name = $_SESSION['name'];
$patient_id = $_SESSION['user_id'];

/* FETCH PATIENT STATS */
$total_booked = $conn->query("SELECT COUNT(*) as count FROM appointments WHERE patient_id = $patient_id")->fetch_assoc()['count'];
$upcoming_booked = $conn->query("SELECT COUNT(*) as count FROM appointments WHERE patient_id = $patient_id AND status='Scheduled'")->fetch_assoc()['count'];

$page_title = "Patient Dashboard";
include "includes/header.php";
?>

<div class="dashboard">
    <?php include "includes/sidebar.php"; ?>

    <div class="main-content">
        <h2>Welcome, <?php echo htmlspecialchars($name); ?> 👋</h2>

        <!-- STAT CARDS -->
        <div class="stat-cards" style="grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));">
            <div class="stat-card">
                <h3><?php echo $total_booked; ?></h3>
                <p>Total Appointments Booked</p>
            </div>
            <div class="stat-card secondary">
                <h3><?php echo $upcoming_booked; ?></h3>
                <p>Upcoming Scheduled Visits</p>
            </div>
        </div>

        <div class="dashboard-cards">
            <div class="card">
                <h4>Book Appointment</h4>
                <p>Schedule a visit with doctor</p>
                <a href="book_appointments.php">Go</a>
            </div>

            <div class="card">
                <h4>My Appointments</h4>
                <p>View your appointment history</p>
                <a href="book_appointments.php">View</a>
            </div>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>