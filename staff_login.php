<?php
// ============================================
// SmartMeal - Staff & Admin Login Page with 2FA
// ============================================
// This page provides the login interface for both staff members and administrators.
// It includes Two-Factor Authentication (2FA) with OTP verification.
// After successful password entry, users must enter a 6-digit OTP.

// ============================================

// Start a session to track user login status
session_start();

// ============================================
// CHECK IF USER IS ALREADY LOGGED IN
// ============================================
// If the user already has an active session, redirect them
// to the appropriate dashboard based on their role.
if (isset($_SESSION['staff_logged_in']) && $_SESSION['staff_logged_in'] === true) {
    // Admin users go to admin dashboard
    if ($_SESSION['staff_role'] == 'admin') {
        header("Location: admin_dashboard.php");
    } else {
        // Regular staff go to staff dashboard
        header("Location: staff_dashboard.php");
    }
    exit(); // Stop further execution
}

// Initialize empty variables for error messages and email field
$error = '';
$email = '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartMeal - Staff/Admin Login with 2FA</title>
    
    <style>
        /* ============================================
           GLOBAL STYLES & RESET
        ============================================ */
        /* Reset all default browser margins and paddings */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        /* Body styling - light gradient background */
        body {
            background: linear-gradient(135deg, #f5f5f5, #e8f5e9);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        /* ============================================
           LOGIN CONTAINER - White Card Design
        ============================================ */
        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 450px;
            overflow: hidden;
        }

        /* ============================================
           HEADER SECTION - Orange Gradient Bar
        ============================================ */
        .login-header {
            background: #ff6600;
            padding: 30px;
            text-align: center;
            color: white;
        }

        .login-header h1 {
            font-size: 2rem;
            margin-bottom: 5px;
        }

        /* Green color for "Meal" in the logo */
        .login-header h1 span {
            color: #28a745;
        }

        .login-header p {
            opacity: 0.9;
        }

        /* ============================================
           FORM BODY SECTION
        ============================================ */
        .login-body {
            padding: 30px;
        }

        /* Form group spacing */
        .form-group {
            margin-bottom: 20px;
        }

        /* Label styling */
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        /* Input field styling */
        input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 14px;
            transition: 0.3s;
        }

        /* Highlight input border when focused */
        input:focus {
            outline: none;
            border-color: #ff6600;
        }

        /* ============================================
           BUTTON STYLING
        ============================================ */
        button {
            width: 100%;
            padding: 12px;
            background: #ff6600;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }

        /* Button hover effect - lifts up and darkens */
        button:hover {
            background: #e55a00;
            transform: translateY(-2px);
        }

        /* ============================================
           ERROR & SUCCESS MESSAGES
        ============================================ */
        /* Red error message box */
        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #dc3545;
        }

        /* Green success message box */
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #28a745;
        }

        /* ============================================
           2FA OTP DISPLAY STYLES
           Shows the One-Time Password for verification
        ============================================ */
        .otp-display {
            background: #e8f5e9;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
        }

        /* Large, prominent OTP number */
        .otp-number {
            font-size: 48px;
            font-weight: bold;
            color: #ff6600;
            letter-spacing: 10px;
            margin: 15px 0;
        }

        .info-text {
            color: #2e7d32;
            font-size: 12px;
        }

        /* ============================================
           BACK LINK STYLES
        ============================================ */
        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: #ff6600;
            text-decoration: none;
        }

        .back-link a:hover {
            text-decoration: underline;
        }

        /* ============================================
           STAFF INFO BOX
           Shows list of authorized staff emails for testing
        ============================================ */
        .staff-info {
            background: #f0f2f5;
            padding: 15px;
            border-radius: 10px;
            margin-top: 20px;
        }

        .staff-info h4 {
            color: #28a745;
            margin-bottom: 10px;
        }

        /* Two-column grid for staff list */
        .staff-list {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
            font-size: 12px;
        }

        /* ============================================
           RESPONSIVE DESIGN FOR MOBILE DEVICES
        ============================================ */
        @media (max-width: 480px) {
            .staff-list {
                grid-template-columns: 1fr;  /* Single column on small screens */
            }
        }
    </style>
</head>
<body>

    <div class="login-container">
        <!-- ============================================
             LOGIN HEADER - Logo and Title
        ============================================ -->
        <div class="login-header">
            <h1>Smart<span>Meal</span></h1>
            <p>Staff & Admin Login Portal with 2FA</p>
        </div>
        
        <div class="login-body">
            
            <!-- ============================================
                 DISPLAY ERROR MESSAGES
                 Shows any errors from the login process
            ============================================ -->
            <?php if (isset($_SESSION['staff_login_error'])): ?>
                <div class="error-message">
                    <?php 
                        echo $_SESSION['staff_login_error'];
                        unset($_SESSION['staff_login_error']); // Clear after displaying
                    ?>
                </div>
            <?php endif; ?>
            
            <!-- ============================================
                 TWO-FACTOR AUTHENTICATION (2FA) OTP FORM
                 This section appears AFTER user enters correct password
                 Shows the OTP code and asks user to enter it
            ============================================ -->
            <?php if (isset($_SESSION['show_otp'])): ?>
                <div class="otp-display">
                    <div class="info-text">🔐 TWO-FACTOR AUTHENTICATION</div>
                    <div class="info-text">Your One-Time Password (OTP) is:</div>
                    <div class="otp-number"><?php echo $_SESSION['show_otp']; ?></div>
                    <div class="info-text">⏰ Valid for 5 minutes</div>
                    <div class="info-text">📧 In production, this would be sent to your email</div>
                </div>
                
                <!-- OTP Verification Form -->
                <form action="staff_login_process.php" method="POST">
                    <div class="form-group">
                        <label>🔑 Enter OTP Code</label>
                        <input type="text" 
                               name="otp_code" 
                               placeholder="Enter 6-digit OTP" 
                               maxlength="6" 
                               autofocus
                               required>
                    </div>
                    <button type="submit" name="verify_otp">Verify OTP & Login →</button>
                </form>
                
                <div class="back-link">
                    <a href="staff_login.php">← Back to Login</a>
                </div>
                
            <?php else: ?>
                <!-- ============================================
                     NORMAL LOGIN FORM
                     This is the first screen users see
                     Collects email and password from user
                ============================================ -->
                <form action="staff_login_process.php" method="POST">
                    <div class="form-group">
                        <label>📧 Email Address</label>
                        <input type="email" 
                               name="email" 
                               value="<?php echo htmlspecialchars($email); ?>"
                               placeholder="name@smartmeal.com"
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label>🔒 Password</label>
                        <input type="password" 
                               name="password" 
                               placeholder="Enter your password"
                               required>
                    </div>
                    
                    <button type="submit" name="login">Login to Dashboard →</button>
                </form>
                
                <!-- ============================================
                     STAFF INFORMATION SECTION
                     Shows authorized staff emails for testing/reference
                ============================================ -->
                <div class="staff-info">
                    <h4>📋 Authorized Staff Email</h4>
                    <div class="staff-list">
                        <div>📧 admin@smartmeal.com (Admin)</div>
                        <div style="color: #666; font-size: 11px; margin-top: 5px;">🔑 Password: admin123</div>
                    </div>
                </div>
                
                <!-- ============================================
                     BACK LINKS - Navigation to other pages
                ============================================ -->
                <div class="back-link">
                    <a href="index.php">← Back to Main Website</a>
                    <br><br>
                    <a href="customer_login.php">Customer Login →</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
</body>
</html>