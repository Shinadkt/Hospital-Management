<?php
$page_title = "Home";
include "includes/header.php";
?>

<!-- NAVBAR -->
<div class="navbar">
    <div class="logo-area">
        <img src="images/logo.png">
        <h1>MedLife Hospital</h1>
    </div>

    <div class="nav-links">
        <a href="#home">Home</a>
        <a href="#departments">Departments</a>
        <a href="login.php">Login</a>
        <a href="signup.php">Signup</a>
    </div>
</div>

<!-- HERO -->
<section class="hero" id="home">
    <h2>Advanced Healthcare With Compassion</h2>
    <p>Providing trusted medical care with modern facilities and experienced specialists.</p>

    <div class="hero-buttons">
        <a href="login.php" class="btn btn-primary">Login</a>
        <a href="signup.php" class="btn btn-secondary">Register</a>
    </div>
</section>

<!-- DEPARTMENTS -->
<section class="section" id="departments">
    <h2>Our Departments</h2>

    <div class="departments">

        <div class="card">
            <img src="images/cardio.jpg" alt="">
            <div class="card-content">
                <h3>Cardiology</h3>
                <p>Heart and vascular treatments.</p>
            </div>
        </div>

        <div class="card">
            <img src="images/dental.jpg" alt="">
            <div class="card-content">
                <h3>Dental</h3>
                <p>Complete dental care.</p>
            </div>
        </div>

        <div class="card">
            <img src="images/derma.jpg" alt="">
            <div class="card-content">
                <h3>Dermatology</h3>
                <p>Skin care solutions.</p>
            </div>
        </div>

        <div class="card">
            <img src="images/ent.jpg" alt="">
            <div class="card-content">
                <h3>ENT</h3>
                <p>Ear, nose and throat care.</p>
            </div>
        </div>

        <div class="card">
            <img src="images/psych.jpg" alt="">
            <div class="card-content">
                <h3>Psychiatry</h3>
                <p>Mental health services.</p>
            </div>
        </div>

        <div class="card">
            <img src="images/gyno.jpg" alt="">
            <div class="card-content">
                <h3>Gynecology</h3>
                <p>Women’s healthcare.</p>
            </div>
        </div>

        <div class="card">
            <img src="images/neuro.jpg" alt="">
            <div class="card-content">
                <h3>Neurology</h3>
                <p>Brain and nerve treatments.</p>
            </div>
        </div>

        <div class="card">
            <img src="images/ortho.jpg" alt="">
            <div class="card-content">
                <h3>Orthopedics</h3>
                <p>Bone and joint care.</p>
            </div>
        </div>

    </div>
</section>
<div class="footer">
    © 2026 MedLife Hospital | All Rights Reserved
</div>

<?php include "includes/footer.php"; ?>