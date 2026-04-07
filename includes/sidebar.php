<?php
// Ensure session is started in case this block is included raw
if(session_status() === PHP_SESSION_NONE) session_start();
$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';
?>

<!-- DYNAMIC SIDEBAR WITH FONTAWESOME -->
<div class="sidebar">
    <h3><i class="fa-solid fa-hospital" style="color:var(--primary); margin-right:8px;"></i> <?php echo $role; ?> Panel</h3>
    
    <?php if($role == 'Admin'): ?>
        <a href="admin_dashboard.php"><i class="fa-solid fa-chart-line fa-fw"></i> Dashboard</a>
        <a href="add_doctor.php"><i class="fa-solid fa-user-doctor fa-fw"></i> Add Doctor</a>
        <a href="manage_patients.php"><i class="fa-solid fa-users-gear fa-fw"></i> Manage Users</a>
        <a href="admin_appointments.php"><i class="fa-solid fa-calendar-check fa-fw"></i> Appointments</a>
    
    <?php elseif($role == 'Doctor'): ?>
        <a href="doctor_dashboard.php"><i class="fa-solid fa-chart-line fa-fw"></i> Dashboard</a>
        <a href="doctor_appointment.php"><i class="fa-solid fa-calendar-day fa-fw"></i> Appointments</a>
    
    <?php elseif($role == 'Patient'): ?>
        <a href="patient_dashboard.php"><i class="fa-solid fa-house-chimney-medical fa-fw"></i> Dashboard</a>
        <a href="book_appointments.php"><i class="fa-solid fa-calendar-plus fa-fw"></i> Appointments</a>
    <?php endif; ?>

    <!-- UNIVERSAL LOGGED-IN LINKS -->
    <?php if($role != ''): ?>
        <a href="profile.php"><i class="fa-solid fa-id-card fa-fw"></i> Profile</a>
        <a href="logout.php" class="logout"><i class="fa-solid fa-right-from-bracket fa-fw"></i> Logout</a>
    <?php endif; ?>
</div>
