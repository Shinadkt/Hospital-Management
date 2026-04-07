<?php
// This handles popping flash session messages securely
if(session_status() === PHP_SESSION_NONE) session_start();

if(isset($_SESSION['toast'])) {
    $toast = $_SESSION['toast'];
    // Map icons based on logical status
    $icon = ($toast['type'] === 'success') ? 'fa-circle-check' : 'fa-circle-xmark';
    $colorClass = 'toast-' . $toast['type']; // e.g., toast-success or toast-error
    
    echo '<div id="toast-notification" class="toast-container ' . $colorClass . '">';
    echo '  <i class="fa-solid ' . $icon . '"></i> ';
    echo '  <span class="toast-message">' . htmlspecialchars($toast['message']) . '</span>';
    echo '</div>';
    
    // Clear the notification from the session mathematically perfectly so it doesn't duplicate on refresh
    unset($_SESSION['toast']);
}
?>
