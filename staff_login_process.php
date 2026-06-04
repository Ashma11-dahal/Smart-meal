<?php
// ============================================
// SmartMeal - Staff/Admin Login Process
// ============================================
// This file handles the login authentication for staff and administrators.
// It includes:
// - Email and password validation
// - Account lockout after 5 failed attempts
// - Two-Factor Authentication (2FA) OTP generation
// - Secure password verification (bcrypt + MD5 compatibility)

// ============================================

// Start session to track user login state
session_start();

// Include database connection class
require_once __DIR__ . '/db.php';

// ============================================
// CHECK IF USER IS ALREADY LOGGED IN
// ============================================
// If user has an active session, redirect to appropriate dashboard
if (isset($_SESSION['staff_logged_in']) && $_SESSION['staff_logged_in'] === true) {
    if ($_SESSION['staff_role'] == 'admin') {
        header("Location: admin_dashboard.php");
    } else {
        header("Location: staff_dashboard.php");
    }
    exit();
}

// ============================================
// TWO-FACTOR AUTHENTICATION - OTP VERIFICATION
// ============================================
// This section verifies the 6-digit OTP entered by the user
// The OTP is valid for 5 minutes (300 seconds)
// ============================================
if (isset($_POST['verify_otp'])) {
    
    // Get the OTP entered by user and remove any extra spaces
    $entered_otp = trim($_POST['otp_code']);
    
    // Check if OTP exists in session and matches what user entered
    if (isset($_SESSION['generated_otp']) && $entered_otp == $_SESSION['generated_otp']) {
        
        // Check if OTP is still within 5 minute validity period
        if (time() - $_SESSION['otp_time'] <= 300) {
            
            // ========== OTP VERIFIED - LOGIN SUCCESSFUL ==========
            // Set all session variables for the logged-in user
            $_SESSION['staff_logged_in'] = true;
            $_SESSION['staff_id'] = $_SESSION['otp_staff_id'];
            $_SESSION['staff_name'] = $_SESSION['otp_staff_name'];
            $_SESSION['staff_email'] = $_SESSION['otp_staff_email'];
            $_SESSION['staff_role'] = $_SESSION['otp_staff_role'];
            
            // Clear OTP session data for security
            unset($_SESSION['generated_otp']);
            unset($_SESSION['otp_time']);
            unset($_SESSION['show_otp']);
            unset($_SESSION['otp_staff_id']);
            unset($_SESSION['otp_staff_name']);
            unset($_SESSION['otp_staff_email']);
            unset($_SESSION['otp_staff_role']);
            
            // Redirect user based on their role
            if ($_SESSION['staff_role'] == 'admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: staff_dashboard.php");
            }
            exit();
            
        } else {
            // OTP has expired (more than 5 minutes old)
            $_SESSION['staff_login_error'] = "❌ OTP has expired! Please login again.";
            unset($_SESSION['generated_otp']);
            unset($_SESSION['show_otp']);
            header("Location: staff_login.php");
            exit();
        }
    } else {
        // User entered wrong OTP code
        $_SESSION['staff_login_error'] = "❌ Invalid OTP! Please try again.";
        header("Location: staff_login.php");
        exit();
    }
}

// ============================================
// NORMAL LOGIN PROCESS (EMAIL & PASSWORD)
// ============================================
// This section runs when user submits the login form
// It validates credentials and generates OTP for 2FA
// ============================================
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    
    // Get and clean user input
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    // ========== BASIC VALIDATION ==========
    if (empty($email) || empty($password)) {
        $_SESSION['staff_login_error'] = "Email and password are required";
        header("Location: staff_login.php");
        exit();
    }
    
    // Validate email format using PHP's built-in filter
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['staff_login_error'] = "Invalid email format";
        header("Location: staff_login.php");
        exit();
    }
    
    // ========== DATABASE CONNECTION ==========
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        $_SESSION['staff_login_error'] = "Database connection failed";
        header("Location: staff_login.php");
        exit();
    }
    
    // ========== CHECK USER CREDENTIALS ==========
    // Using prepared statement to prevent SQL injection
    $query = "SELECT staff_id, full_name, email, password, role, is_active, login_attempts, locked_until 
              FROM staff_users 
              WHERE email = :email";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    // If user exists in database
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // ========== CHECK IF ACCOUNT IS LOCKED ==========
        // Account gets locked after 5 failed attempts for 15 minutes
        if ($user['locked_until'] && strtotime($user['locked_until']) > time()) {
            $_SESSION['staff_login_error'] = "Account is locked. Try again after " . date('H:i', strtotime($user['locked_until']));
            header("Location: staff_login.php");
            exit();
        }
        
        // ========== CHECK IF ACCOUNT IS ACTIVE ==========
        if ($user['is_active'] == 0) {
            $_SESSION['staff_login_error'] = "Account is deactivated. Contact administrator.";
            header("Location: staff_login.php");
            exit();
        }
        
        // ============================================
        // SECURE PASSWORD VERIFICATION
        // ============================================
        // This section supports TWO password hashing methods:
        // 1. bcrypt (password_hash) - Secure method for new passwords
        // 2. MD5 - Legacy support for existing passwords
        // When an MD5 password is used successfully, it automatically
        // gets upgraded to bcrypt for better security.
        // ============================================
        $password_valid = false;
        
        // METHOD 1: Try bcrypt verification (secure, recommended)
        // password_verify() automatically detects the hash type
        if (password_verify($password, $user['password'])) {
            $password_valid = true;
        }
        // METHOD 2: Try MD5 verification (legacy support)
        // For existing accounts created before bcrypt implementation
        elseif ($user['password'] === md5($password)) {
            // Password is correct but using weak MD5 hash
            // Auto-upgrade to secure bcrypt hash
            $new_hash = password_hash($password, PASSWORD_DEFAULT);
            $upgrade_sql = "UPDATE staff_users SET password = :new_hash WHERE staff_id = :id";
            $upgrade_stmt = $db->prepare($upgrade_sql);
            $upgrade_stmt->bindParam(':new_hash', $new_hash);
            $upgrade_stmt->bindParam(':id', $user['staff_id']);
            $upgrade_stmt->execute();
            $password_valid = true;
        }
        
        // ============================================
        // IF PASSWORD IS CORRECT - GENERATE OTP
        // ============================================
        if ($password_valid) {
            
            // Generate random 6-digit OTP (100000 to 999999)
            $otp = rand(100000, 999999);
            
            // Store OTP and user data in session for verification
            $_SESSION['generated_otp'] = $otp;
            $_SESSION['otp_time'] = time();
            $_SESSION['show_otp'] = $otp;
            $_SESSION['otp_staff_id'] = $user['staff_id'];
            $_SESSION['otp_staff_name'] = $user['full_name'];
            $_SESSION['otp_staff_email'] = $user['email'];
            $_SESSION['otp_staff_role'] = $user['role'];
            
            // Reset login attempts counter since password was correct
            $reset = "UPDATE staff_users SET login_attempts = 0, locked_until = NULL WHERE staff_id = :id";
            $reset_stmt = $db->prepare($reset);
            $reset_stmt->bindParam(':id', $user['staff_id']);
            $reset_stmt->execute();
            
            // Redirect to OTP verification page
            header("Location: staff_login.php");
            exit();
            
        } else {
            // ============================================
            // INVALID PASSWORD - INCREMENT ATTEMPT COUNT
            // ============================================
            $new_attempts = $user['login_attempts'] + 1;
            $lock_until = null;
            
            // Lock account after 5 failed attempts
            if ($new_attempts >= 5) {
                $lock_until = date('Y-m-d H:i:s', strtotime('+15 minutes'));
                $_SESSION['staff_login_error'] = "Too many failed attempts. Account locked for 15 minutes.";
            } else {
                $_SESSION['staff_login_error'] = "Invalid password. " . (5 - $new_attempts) . " attempts remaining.";
            }
            
            // Update database with new attempt count
            $update = "UPDATE staff_users SET login_attempts = :attempts, locked_until = :locked WHERE staff_id = :id";
            $update_stmt = $db->prepare($update);
            $update_stmt->bindParam(':attempts', $new_attempts);
            $update_stmt->bindParam(':locked', $lock_until);
            $update_stmt->bindParam(':id', $user['staff_id']);
            $update_stmt->execute();
            
            header("Location: staff_login.php");
            exit();
        }
    } else {
        // No account found with this email address
        $_SESSION['staff_login_error'] = "No account found with this email.";
        header("Location: staff_login.php");
        exit();
    }
} else {
    // If no valid POST request, redirect to login page
    header("Location: staff_login.php");
    exit();
}
?>