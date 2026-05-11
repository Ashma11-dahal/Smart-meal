
<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>SmartMeal - Food Ordering System</title>

<style>

/* ===============================
   GLOBAL STYLES
================================= */

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family: Arial, sans-serif;
}

body{
    background:#f5f5f5;
    color:#333;
}

/* ===============================
   NAVBAR
================================= */

.navbar{
    width:100%;
    background:white;
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:15px 50px;
    box-shadow:0 2px 10px rgba(0,0,0,0.1);
    position:sticky;
    top:0;
    z-index:100;
}

.logo{
    display:flex;
    align-items:center;
}

.logo img{
    width:70px;
    margin-right:10px;
}

.logo h1{
    font-size:38px;
    color:#28a745;
}

.logo span{
    color:#ff6600;
}

.nav-links{
    list-style:none;
    display:flex;
}

.nav-links li{
    margin-left:30px;
}

.nav-links a{
    text-decoration:none;
    color:#333;
    font-size:18px;
    transition:0.3s;
}

.nav-links a:hover{
    color:#ff6600;
}

/* ===============================
   HERO SECTION
================================= */

.hero{
    width:100%;
    min-height:90vh;
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:50px;
    background:linear-gradient(to right,#fff,#f7fff7);
}

.hero-text{
    width:50%;
}

.hero-text h2{
    font-size:65px;
    margin-bottom:20px;
}

.hero-text h2 span{
    color:#ff6600;
}

.hero-text p{
    font-size:20px;
    line-height:1.8;
    margin-bottom:30px;
    color:#555;
}

.hero-buttons a{
    display:inline-block;
    padding:15px 35px;
    margin-right:15px;
    border-radius:50px;
    text-decoration:none;
    font-size:18px;
    transition:0.3s;
}

.btn-order{
    background:#28a745;
    color:white;
}

.btn-order:hover{
    background:#1e7e34;
}

.btn-menu{
    border:2px solid #28a745;
    color:#28a745;
}

.btn-menu:hover{
    background:#28a745;
    color:white;
}

.hero-image{
    width:45%;
}

.hero-image img{
    width:100%;
}

/* ===============================
   LOGIN SECTION
================================= */

.login-section{
    padding:80px 50px;
    background:#fff;
}

.section-title{
    text-align:center;
    font-size:45px;
    margin-bottom:50px;
    color:#28a745;
}

.login-container{
    display:flex;
    justify-content:center;
    gap:40px;
    flex-wrap:wrap;
}

.login-card{
    width:400px;
    background:white;
    padding:35px;
    border-radius:20px;
    box-shadow:0 5px 20px rgba(0,0,0,0.1);
    transition:0.3s;
}

.login-card:hover{
    transform:translateY(-8px);
}

.login-card h3{
    font-size:30px;
    margin-bottom:25px;
    text-align:center;
}

.customer{
    border-top:6px solid #28a745;
}

.staff{
    border-top:6px solid #ff6600;
}

.login-card input{
    width:100%;
    padding:15px;
    margin-bottom:20px;
    border:1px solid #ccc;
    border-radius:10px;
    font-size:16px;
}

.login-card button{
    width:100%;
    padding:15px;
    border:none;
    border-radius:10px;
    font-size:18px;
    cursor:pointer;
    transition:0.3s;
}

.customer button{
    background:#28a745;
    color:white;
}

.customer button:hover{
    background:#1e7e34;
}

.staff button{
    background:#ff6600;
    color:white;
}

.staff button:hover{
    background:#e65c00;
}

/* ===============================
   FEATURES
================================= */

.features{
    padding:80px 50px;
    background:#f8f8f8;
}

.feature-container{
    display:flex;
    justify-content:center;
    gap:30px;
    flex-wrap:wrap;
}

.feature-box{
    width:280px;
    background:white;
    padding:30px;
    border-radius:15px;
    text-align:center;
    box-shadow:0 4px 15px rgba(0,0,0,0.1);
    transition:0.3s;
}

.feature-box:hover{
    transform:translateY(-5px);
}

.feature-box h4{
    margin:20px 0;
    font-size:24px;
    color:#28a745;
}

.feature-box p{
    color:#555;
    line-height:1.6;
}

/* ===============================
   FOOTER
================================= */

footer{
    background:#222;
    color:white;
    text-align:center;
    padding:25px;
}

/* ===============================
   RESPONSIVE DESIGN
================================= */

@media(max-width:900px){

    .hero{
        flex-direction:column;
        text-align:center;
    }

    .hero-text{
        width:100%;
        margin-bottom:40px;
    }

    .hero-image{
        width:100%;
    }

    .hero-text h2{
        font-size:45px;
    }

    .navbar{
        flex-direction:column;
    }

    .nav-links{
        margin-top:20px;
    }
}

</style>

</head>

<body>

<!-- ===========================
     NAVBAR
=========================== -->

<div class="navbar">

    <div class="logo">

        <!-- PUT YOUR LOGO FILE HERE -->
        <img src="logo.png" alt="SmartMeal Logo">

        <h1>Smart<span>Meal</span></h1>

    </div>

    <ul class="nav-links">
        <li><a href="#">Home</a></li>
        <li><a href="#login">Login</a></li>
        <li><a href="#features">Features</a></li>
        <li><a href="#">Contact</a></li>
    </ul>

</div>

<!-- ===========================
     HERO SECTION
=========================== -->

<section class="hero">

    <div class="hero-text">

        <h2>
            Delicious Food Delivered with 
            <span>SmartMeal</span>
        </h2>

        <p>
            Order your favorite meals online quickly and easily.
            SmartMeal helps customers enjoy delicious food
            with fast delivery and simple ordering.
        </p>

        <div class="hero-buttons">

            <a href="#" class="btn-order">
                Order Now
            </a>

            <a href="#" class="btn-menu">
                View Menu
            </a>

        </div>

    </div>

    <div class="hero-image">

        <!-- FOOD IMAGE -->
        <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?q=80&w=1200&auto=format&fit=crop">

    </div>

</section>

<!-- ===========================
     LOGIN SECTION
=========================== -->

<section class="login-section" id="login">

    <h2 class="section-title">
        Login Portal
    </h2>

    <div class="login-container">

        <!-- CUSTOMER LOGIN -->

        <div class="login-card customer">

            <h3>Customer Login</h3>

           <form action="staff_admin_login.php" method="POST">

    <input type="email"
           name="email"
           placeholder="Staff/Admin Email"
           required>

    <input type="password"
           name="password"
           placeholder="Password"
           required>

    <button type="submit">
        Secure Staff Login
    </button>

</form>

        </div>

        <!-- STAFF / ADMIN LOGIN -->

        <div class="login-card staff">

            <h3>Staff / Admin Login</h3>

            <form action="staff_admin_login.php" method="POST">

                <input type="email"
                       name="email"
                       placeholder="Staff/Admin Email"
                       required>

                <input type="password"
                       name="password"
                       placeholder="Password"
                       required>

                <button type="submit">
                    Secure Staff Login
                </button>

            </form>

        </div>

    </div>

</section>

<!-- ===========================
     FEATURES SECTION
=========================== -->

<section class="features" id="features">

    <h2 class="section-title">
        Why Choose SmartMeal?
    </h2>

    <div class="feature-container">

        <div class="feature-box">

            <h4>Fast Delivery</h4>

            <p>
                Get your favorite meals delivered quickly
                to your doorstep.
            </p>

        </div>

        <div class="feature-box">

            <h4>Fresh Food</h4>

            <p>
                Enjoy fresh and delicious food
                prepared every day.
            </p>

        </div>

        <div class="feature-box">

            <h4>Secure System</h4>

            <p>
                Protected customer and staff login
                with secure authentication.
            </p>

        </div>

    </div>

</section>

<!-- ===========================
     FOOTER
=========================== -->

<footer>

    <p>
        © 2026 SmartMeal Food Ordering System
    </p>

</footer>

</body>
</html>