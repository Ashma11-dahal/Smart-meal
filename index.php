<?php
// Start the session to store user login information and other session data
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>

<!-- Character encoding -->
<meta charset="UTF-8">

<!-- Responsive design for mobile devices -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Website title shown in browser tab -->
<title>SmartMeal - Food Ordering System</title>

<style>

/* =========================
   GLOBAL STYLES
========================= */

/* Remove default margins and paddings from all elements */
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family: Arial, sans-serif;
}

/* Main body styling */
body{
    background:#f5f5f5;
    color:#333;
}

/* =========================
   NAVIGATION BAR
========================= */

/* Navbar container */
.navbar{
    width:100%;
    background:white;
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:15px 50px;
    box-shadow:0 2px 10px rgba(0,0,0,0.1);
}

/* Logo section container */
.logo{
    display:flex;
    align-items:center;
}

/* Logo image styling */
.logo img{
    width:70px;
    margin-right:10px;
}

/* Main logo text */
.logo h1{
    font-size:38px;
    color:#28a745;
}

/* Highlighted logo text */
.logo span{
    color:#ff6600;
}

/* Navigation links container */
.nav-links{
    list-style:none;
    display:flex;
    align-items:center;
}

/* Spacing between navigation items */
.nav-links li{
    margin-left:30px;
}

/* Navigation link styling */
.nav-links a{
    text-decoration:none;
    color:#333;
    font-size:18px;
    transition:0.3s;
}

/* Hover effect for navigation links */
.nav-links a:hover{
    color:#ff6600;
}

/* =========================
   STAFF LOGIN BUTTON
========================= */

/* Special style for staff login button */
.staff-nav-btn{
    background:#ff6600;
    color:white !important;
    padding:8px 20px;
    border-radius:25px;
}

/* Hover effect for staff button */
.staff-nav-btn:hover{
    background:#e55a00;
    color:white !important;
}

/* =========================
   HERO SECTION
========================= */

/* Main hero section */
.hero{
    width:100%;
    min-height:90vh;
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:50px;
    background:linear-gradient(to right,#fff,#f7fff7);
}

/* Text content area */
.hero-text{
    width:50%;
}

/* Main hero heading */
.hero-text h2{
    font-size:60px;
    margin-bottom:20px;
}

/* Highlighted text inside heading */
.hero-text span{
    color:#ff6600;
}

/* Hero paragraph styling */
.hero-text p{
    font-size:20px;
    margin-bottom:30px;
}

/* Hero image container */
.hero-image{
    width:45%;
}

/* Hero image styling */
.hero-image img{
    width:100%;
    border-radius:20px;
}

/* =========================
   LOGIN SECTION
========================= */

/* Login section container */
.login-section{
    padding:80px 50px;
    background:#fff;
}

/* Common title styling */
.section-title{
    text-align:center;
    font-size:45px;
    margin-bottom:50px;
    color:#28a745;
}

/* Login cards container */
.login-container{
    display:flex;
    justify-content:center;
    gap:40px;
    flex-wrap:wrap;
}

/* Individual login card */
.login-card{
    width:400px;
    background:white;
    padding:35px;
    border-radius:20px;
    box-shadow:0 5px 20px rgba(0,0,0,0.1);
    text-align:center;
}

/* Customer card top border color */
.customer{
    border-top:6px solid #28a745;
}

/* Staff card top border color */
.staff{
    border-top:6px solid #ff6600;
}

/* Login card heading */
.login-card h3{
    font-size:30px;
    margin-bottom:20px;
}

/* Login card paragraph */
.login-card p{
    margin-bottom:25px;
}

/* Common login button styling */
.login-btn{
    display:inline-block;
    width:100%;
    padding:15px;
    border:none;
    border-radius:10px;
    font-size:18px;
    cursor:pointer;
    text-decoration:none;
    text-align:center;
    transition:0.3s;
}

/* Customer button style */
.customer .login-btn{
    background:#28a745;
    color:white;
}

/* Staff button style */
.staff .login-btn{
    background:#ff6600;
    color:white;
}

/* Button hover animation */
.login-btn:hover{
    transform:translateY(-2px);
    opacity:0.9;
}

/* =========================
   FOOTER
========================= */

/* Footer styling */
footer{
    background:#222;
    color:white;
    text-align:center;
    padding:25px;
}

</style>
</head>

<body>

<!-- =========================
     NAVIGATION BAR
========================= -->
<div class="navbar">

    <!-- Logo area -->
    <div class="logo">

        <!-- Website logo image -->
        <img src="logo.png" alt="Logo">

        <!-- Website name -->
        <h1>Smart<span>Meal</span></h1>
    </div>

    <!-- Navigation menu -->
    <ul class="nav-links">

        <!-- Home link -->
        <li><a href="#">Home</a></li>

        <!-- Login section link -->
        <li><a href="#login">Login</a></li>

        <!-- Staff login button -->
        <li>
            <a href="/FoodOrderingSystem/views/staff_login.php" class="staff-nav-btn">
                👨‍🍳 Staff Login
            </a>
        </li>

    </ul>

</div>

<!-- =========================
     HERO SECTION
========================= -->
<section class="hero">

    <!-- Hero text content -->
    <div class="hero-text">

        <!-- Main heading -->
        <h2>
            Delicious Food With
            <span>SmartMeal</span>
        </h2>

        <!-- Short description -->
        <p>
            Order food quickly and easily online.
        </p>

    </div>

    <!-- Hero image -->
    <div class="hero-image">

        <!-- Food image from Unsplash -->
        <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?q=80&w=1200&auto=format&fit=crop">

    </div>

</section>

<!-- =========================
     LOGIN PORTAL SECTION
========================= -->
<section class="login-section" id="login">

    <!-- Section heading -->
    <h2 class="section-title">
        Login Portal
    </h2>

    <!-- Login cards container -->
    <div class="login-container">

        <!-- Staff/Admin login card -->
        <div class="login-card staff">

            <h3>Staff / Admin Login</h3>

            <p>
                Secure login for staff and administrators.
            </p>

            <!-- Staff login button -->
            <a href="/FoodOrderingSystem/views/staff_login.php" class="login-btn">
                Open Staff/Admin Login →
            </a>

        </div>

    </div>

</section>

<!-- =========================
     FOOTER SECTION
========================= -->
<footer>

    <!-- Copyright text -->
    <p>
        © 2026 SmartMeal Food Ordering System | Staff Login Available
    </p>

</footer>
<?php include 'footer.php'; ?>
</body>
</html>