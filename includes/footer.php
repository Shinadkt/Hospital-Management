<!-- Custom logic to gracefully destroy the Toast -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const toast = document.getElementById("toast-notification");
    if(toast) {
        // Show the toast with a smooth slide-in
        setTimeout(() => toast.classList.add("show"), 100);
        
        // Wait 4 seconds, then slide it out elegantly before completely eliminating it from DOM
        setTimeout(() => {
            toast.classList.remove("show");
            setTimeout(() => toast.remove(), 500); 
        }, 4000);
    }
});
</script>
</body>
</html>
