<?php
// ============================================
// SmartMeal - Admin & Staff Login Page with 2FA
// ============================================
// This page handles the login process for administrators and staff.
// It includes email validation, password verification, and 
// Two-Factor Authentication (2FA) using OTP.

// ============================================

// Start a session to store user login information
session_start();

// Variable to store any error messages that occur during login
$error = "";

// ============================================
// TWO-FACTOR AUTHENTICATION - OTP VERIFICATION
// ============================================
// This section handles the OTP (One-Time Password) verification
// after the user has successfully entered their email and password.
// The OTP is valid for 5 minutes (300 seconds).
// ============================================
if (isset($_POST['verify_otp'])) {
    
    // Get the OTP code entered by the user and remove any extra spaces
    $entered_otp = trim($_POST['otp_code']);
    
    // Check if we have a generated OTP in session and if it matches what user entered
    if (isset($_SESSION['generated_otp']) && $entered_otp == $_SESSION['generated_otp']) {
        
        // Check if the OTP is still within the 5 minute validity period
        if (time() - $_SESSION['otp_time'] <= 300) {
            
            // OTP is correct and still valid - login successful!
            // Set all the session variables needed for the dashboard
            $_SESSION['staff_email'] = $_SESSION['otp_email'];
            $_SESSION['staff_name'] = $_SESSION['otp_name'];
            $_SESSION['role'] = $_SESSION['otp_role'];
            $_SESSION['staff_logged_in'] = true;  // Main flag to indicate user is logged in
            $_SESSION['login_time'] = time();
            
            // Clean up - remove all OTP related session data
            unset($_SESSION['generated_otp']);
            unset($_SESSION['otp_time']);
            unset($_SESSION['otp_email']);
            unset($_SESSION['otp_name']);
            unset($_SESSION['otp_role']);
            unset($_SESSION['show_otp']);
            
            // Send user to the appropriate dashboard based on their role
            if ($_SESSION['role'] == 'admin') {
                header("Location: admin_dashboard.php");  // Admin goes to admin dashboard
            } else {
                header("Location: staff_dashboard.php");  // Staff goes to staff dashboard
            }
            exit();  // Stop further execution
        } else {
            // OTP has expired (more than 5 minutes old)
            $error = "❌ OTP has expired! Please login again.";
            unset($_SESSION['generated_otp']);
            unset($_SESSION['show_otp']);
        }
    } else {
        // User entered wrong OTP code
        $error = "❌ Invalid OTP! Please try again.";
    }
}

// ============================================
// AUTHORIZED STAFF/ADMIN ACCOUNTS
// ============================================
// This array stores all the valid email addresses and their passwords.
// Each account has a password, role (admin/menu/order/customer), and display name.
// In a real production system, this data would be stored in a database.
// ============================================
$staff_accounts = [
    "admin@smartmeal.com" => [
        "password" => "admin123",
        "role" => "admin",
        "name" => "Admin User"
    ],
    "menu@smartmeal.com" => [
        "password" => "menu123",
        "role" => "menu",
        "name" => "Menu Manager"
    ],
    "orders@smartmeal.com" => [
        "password" => "order123",
        "role" => "order",
        "name" => "Order Manager"
    ],
    "customers@smartmeal.com" => [
        "password" => "customer123",
        "role" => "customer",
        "name" => "Customer Manager"
    ]
];

// ============================================
// LOGIN FORM PROCESSING
// ============================================
// This section runs when the user submits the login form (not the OTP form)
// It validates the email and password, then generates an OTP for 2FA
// ============================================
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['verify_otp'])) {

    // Get the email and password from the form and clean them up
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    // ============================================
    // EMAIL VALIDATION - Multiple checks to ensure email is valid
    // ============================================
    $email_errors = [];
    
    // 1. Check if email field is empty
    if (empty($email)) {
        $email_errors[] = "Email address is required";
    }
    
    // 2. Check if email format is valid (e.g., name@domain.com)
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_errors[] = "Please enter a valid email address (e.g., name@domain.com)";
    }
    
    // 3. Make sure email contains the @ symbol
    if (!empty($email) && strpos($email, '@') === false) {
        $email_errors[] = "Email must contain '@' symbol";
    }
    
    // 4. Check if the email domain is from an allowed provider
    if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $domain = substr(strrchr($email, "@"), 1);
        $valid_domains = ['smartmeal.com', 'gmail.com', 'yahoo.com', 'outlook.com'];
        
        if (!in_array($domain, $valid_domains)) {
            $email_errors[] = "Email domain must be from: " . implode(', ', $valid_domains);
        }
    }
    
    // 5. Email cannot be longer than 100 characters
    if (!empty($email) && strlen($email) > 100) {
        $email_errors[] = "Email address is too long (maximum 100 characters)";
    }
    
    // 6. Only allow specific characters in email (letters, numbers, @, ., _, -)
    if (!empty($email) && !preg_match("/^[a-zA-Z0-9@._-]+$/", $email)) {
        $email_errors[] = "Email contains invalid characters. Only letters, numbers, @, ., _, - are allowed";
    }
    
    // 7. Prevent consecutive dots (..) in email address
    if (!empty($email) && preg_match('/\.\./', $email)) {
        $email_errors[] = "Email cannot contain consecutive dots (..)";
    }
    
    // 8. Check the part before @ symbol (local part) - cannot be empty or too long
    if (!empty($email) && strpos($email, '@') !== false) {
        $local_part = substr($email, 0, strpos($email, '@'));
        if (strlen($local_part) < 1) {
            $email_errors[] = "Email must have a name before @ symbol";
        }
        if (strlen($local_part) > 64) {
            $email_errors[] = "Email local part is too long (maximum 64 characters)";
        }
    }
    
    // ============================================
    // PASSWORD VALIDATION - Basic checks for password
    // ============================================
    $password_errors = [];
    
    // Password cannot be empty
    if (empty($password)) {
        $password_errors[] = "Password is required";
    }
    
    // Password must be at least 4 characters long
    if (!empty($password) && strlen($password) < 4) {
        $password_errors[] = "Password must be at least 4 characters";
    }
    
    // ============================================
    // DISPLAY VALIDATION ERRORS IF ANY
    // ============================================
    if (!empty($email_errors) || !empty($password_errors)) {
        $error = "";
        if (!empty($email_errors)) {
            $error .= "❌ Email Validation:<br>• " . implode("<br>• ", $email_errors) . "<br><br>";
        }
        if (!empty($password_errors)) {
            $error .= "❌ Password Validation:<br>• " . implode("<br>• ", $password_errors);
        }
    } else {
        
        // ============================================
        // CHECK CREDENTIALS AGAINST STAFF ACCOUNTS
        // ============================================
        // Convert email to lowercase for case-insensitive comparison
        $email_lower = strtolower($email);
        
        // Search through the staff accounts to find a match
        $found_email = null;
        foreach ($staff_accounts as $acc_email => $acc_data) {
            if (strtolower($acc_email) == $email_lower) {
                $found_email = $acc_email;
                break;
            }
        }
        
        // If email exists in our records, check the password
        if ($found_email) {
            
            // Verify if the entered password matches the stored password
            if ($staff_accounts[$found_email]['password'] == $password) {
                
                // ============================================
                // 2FA: GENERATE ONE-TIME PASSWORD (OTP)
                // ============================================
                // Generate a random 6-digit number between 100000 and 999999
                $otp = rand(100000, 999999);
                
                // Store the OTP and related user data in session variables
                $_SESSION['generated_otp'] = $otp;           // The actual OTP number
                $_SESSION['otp_time'] = time();               // Timestamp when OTP was generated
                $_SESSION['otp_email'] = $found_email;        // User's email
                $_SESSION['otp_name'] = $staff_accounts[$found_email]['name'];  // User's name
                $_SESSION['otp_role'] = $staff_accounts[$found_email]['role'];  // User's role
                $_SESSION['show_otp'] = $otp;                 // Display OTP on screen for testing
                
                
                // The user must first verify the OTP before being fully logged in.
                // The page will reload and show the OTP verification form.
                
            } else {
                // Password is incorrect
                $error = "❌ Wrong password! Please try again.";
            }
            
        } else {
            // Email does not exist in our records
            $error = "❌ Unauthorized email! No account found with this email address.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>SmartMeal - Admin / Staff Login</title>
    <style>
        /*
        ============================================
        PAGE STYLING
        ============================================
        These styles create a modern, professional login page
        with a purple gradient background and white card in the center.
        */
        
        /* Reset all default margins and paddings for consistency */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        /* Body styling - gradient background and centered content */
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        /*
        ============================================
        LOGIN BOX DESIGN
        ============================================
        White card that contains the login form
        */
        .login-box {
            width: 450px;
            margin: 20px auto;
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        
        /*
        ============================================
        HEADING STYLES
        ============================================
        */
        h2 {
            text-align: center;
            margin-bottom: 10px;
            color: #ff6600;
        }
        
        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }
        
        /*
        ============================================
        INPUT FIELD STYLES
        ============================================
        */
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        
        input {
            width: 100%;
            padding: 12px 15px;
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
        
        /* Red border for invalid inputs */
        input.error {
            border-color: #dc3545;
            background-color: #fff8f8;
        }
        
        /* Green border for valid inputs */
        input.valid {
            border-color: #28a745;
            background-color: #f0fff4;
        }
        
        /* Validation message styling */
        .validation-message {
            font-size: 12px;
            margin-top: 5px;
            min-height: 35px;
        }
        
        .validation-message.error-text {
            color: #dc3545;
        }
        
        .validation-message.success-text {
            color: #28a745;
        }
        
        /*
        ============================================
        BUTTON STYLES
        ============================================
        */
        button {
            width: 100%;
            padding: 14px;
            background: #ff6600;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }
        
        /* Button hover effect - slight lift and darker color */
        button:hover {
            background: #e55a00;
            transform: translateY(-2px);
        }
        
        /*
        ============================================
        ERROR MESSAGE STYLES
        ============================================
        Red box that appears when there's a login error
        */
        .error {
            color: #dc3545;
            text-align: center;
            margin-bottom: 20px;
            padding: 12px;
            background: #f8d7da;
            border-radius: 10px;
            border-left: 4px solid #dc3545;
            font-size: 14px;
        }
        
        /*
        ============================================
        OTP DISPLAY STYLES - For Two-Factor Authentication
        ============================================
        Green box that shows the OTP code to the user
        */
        .otp-display {
            background: #e8f5e9;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        /* Large font for the OTP number to make it easy to read */
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
        
        /*
        ============================================
        STAFF INFO BOX - Shows list of authorized accounts
        ============================================
        */
        .staff-info {
            background: #f0f2f5;
            padding: 15px;
            border-radius: 10px;
            margin-top: 20px;
        }
        
        .staff-info h4 {
            color: #28a745;
            margin-bottom: 10px;
            font-size: 14px;
        }
        
        /* Two-column grid for displaying staff accounts */
        .staff-list {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
            font-size: 12px;
        }
        
        .staff-list div {
            padding: 5px;
            border-bottom: 1px solid #ddd;
        }
        
        /*
        ============================================
        BACK LINK STYLES
        ============================================
        */
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .back-link a {
            color: #ff6600;
            text-decoration: none;
            font-size: 14px;
        }
        
        .back-link a:hover {
            text-decoration: underline;
        }
        
        /* Horizontal line separator */
        hr {
            margin: 20px 0;
            border: none;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2>🍽️ SmartMeal</h2>
    <div class="subtitle">Staff / Admin Login Portal</div>
    
    <!-- ============================================
         DISPLAY ERROR MESSAGES
         Shows any validation or login errors to the user
    ============================================ -->
    <?php if($error != ""): ?>
        <div class="error">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>
    
    <!-- ============================================
         TWO-FACTOR AUTHENTICATION FORM
         Shows when user has successfully entered email/password
         and needs to enter the OTP code
    ============================================ -->
    <?php if(isset($_SESSION['show_otp'])): ?>
        <div class="otp-display">
            <div class="info-text">🔐 TWO-FACTOR AUTHENTICATION</div>
            <div class="info-text">Your One-Time Password (OTP) is:</div>
            <div class="otp-number"><?php echo $_SESSION['show_otp']; ?></div>
            <div class="info-text">⏰ Valid for 5 minutes</div>
            <div class="info-text">📧 In production, this would be sent to your email</div>
        </div>
        
        <form method="POST">
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
            <a href="admin_login.php">← Back to Login</a>
        </div>
        
    <?php else: ?>
        <!-- ============================================
             MAIN LOGIN FORM
             Shows when user first arrives at the login page
             Collects email and password from the user
        ============================================ -->
        <form method="POST" id="loginForm" onsubmit="return validateForm()">
            <div class="form-group">
                <label>📧 Email Address</label>
                <input type="email"
                       name="email"
                       id="email"
                       placeholder="staff@smartmeal.com"
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                       required
                       maxlength="100">
                <div id="email-validation" class="validation-message"></div>
            </div>
            
            <div class="form-group">
                <label>🔒 Password</label>
                <input type="password"
                       name="password"
                       id="password"
                       placeholder="Enter your password"
                       required>
                <div id="password-validation" class="validation-message"></div>
            </div>
            
            <button type="submit" name="login">Secure Login →</button>
        </form>
        
        <!-- ============================================
             STAFF ACCOUNTS INFORMATION
             Shows the list of authorized users for testing purposes
        ============================================ -->
        <div class="staff-info">
            <h4>📋 Authorized Staff Accounts</h4>
            <div class="staff-list">
                <div>📧 admin@smartmeal.com (Admin)</div>
                <div>📧 menu@smartmeal.com (Menu Manager)</div>
                <div>📧 orders@smartmeal.com (Order Manager)</div>
                <div>📧 customers@smartmeal.com (Customer Manager)</div>
            </div>
        </div>
        
        <hr>
        
        <div class="back-link">
            <a href="index.php">← Back to Main Website</a>
            <br><br>
            <a href="customer_login.php">Customer Login →</a>
        </div>
    <?php endif; ?>
</div>

<script>
    // ============================================
    // EMAIL VALIDATION FUNCTION
    // ============================================
    // This function performs real-time email validation
    // It checks for empty fields, proper format, valid domain, etc.
    function validateEmail(email) {
        let errors = [];
        
        // Check if email field is empty
        if (email.trim() === '') {
            errors.push('Email is required');
            return { isValid: false, errors: errors };
        }
        
        // Check if email format is valid using regex pattern
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email)) {
            errors.push('Please enter a valid email address (e.g., name@domain.com)');
            return { isValid: false, errors: errors };
        }
        
        // Check for @ symbol
        if (email.indexOf('@') === -1) {
            errors.push('Email must contain @ symbol');
        }
        
        // Check if domain is from allowed providers
        const domain = email.split('@')[1];
        const validDomains = ['smartmeal.com', 'gmail.com', 'yahoo.com', 'outlook.com'];
        if (domain && !validDomains.includes(domain)) {
            errors.push('Email domain must be from: ' + validDomains.join(', '));
        }
        
        // Check maximum length
        if (email.length > 100) {
            errors.push('Email is too long (max 100 characters)');
        }
        
        // Check for invalid characters
        if (!/^[a-zA-Z0-9@._-]+$/.test(email)) {
            errors.push('Email contains invalid characters');
        }
        
        // Check for consecutive dots (invalid email pattern)
        if (/\.\./.test(email)) {
            errors.push('Email cannot contain consecutive dots');
        }
        
        // Check the local part (before @) length
        const localPart = email.split('@')[0];
        if (localPart && localPart.length < 1) {
            errors.push('Email must have a name before @');
        }
        if (localPart && localPart.length > 64) {
            errors.push('Email local part is too long (max 64 characters)');
        }
        
        return { isValid: errors.length === 0, errors: errors };
    }
    
    // ============================================
    // PASSWORD VALIDATION FUNCTION
    // ============================================
    // Basic password validation - checks if password is empty or too short
    function validatePassword(password) {
        let errors = [];
        
        if (password.trim() === '') {
            errors.push('Password is required');
        } else if (password.length < 4) {
            errors.push('Password must be at least 4 characters');
        }
        
        return { isValid: errors.length === 0, errors: errors };
    }
    
    // ============================================
    // REAL-TIME EMAIL VALIDATION DISPLAY
    // ============================================
    // Shows validation messages as the user types
    function showEmailValidation() {
        const email = document.getElementById('email').value;
        const result = validateEmail(email);
        const validationDiv = document.getElementById('email-validation');
        const emailInput = document.getElementById('email');
        
        if (!result.isValid && email.trim() !== '') {
            // Show error message in red
            validationDiv.innerHTML = '❌ ' + result.errors.join('<br>❌ ');
            validationDiv.className = 'validation-message error-text';
            emailInput.classList.add('error');
            emailInput.classList.remove('valid');
        } else if (email.trim() !== '' && result.isValid) {
            // Show success message in green
            validationDiv.innerHTML = '✓ Email looks good!';
            validationDiv.className = 'validation-message success-text';
            emailInput.classList.add('valid');
            emailInput.classList.remove('error');
        } else {
            // Clear messages when field is empty
            validationDiv.innerHTML = '';
            emailInput.classList.remove('error', 'valid');
        }
    }
    
    // ============================================
    // REAL-TIME PASSWORD VALIDATION DISPLAY
    // ============================================
    function showPasswordValidation() {
        const password = document.getElementById('password').value;
        const result = validatePassword(password);
        const validationDiv = document.getElementById('password-validation');
        const passwordInput = document.getElementById('password');
        
        if (!result.isValid && password.trim() !== '') {
            validationDiv.innerHTML = '❌ ' + result.errors.join('<br>❌ ');
            validationDiv.className = 'validation-message error-text';
            passwordInput.classList.add('error');
            passwordInput.classList.remove('valid');
        } else if (password.trim() !== '' && result.isValid) {
            validationDiv.innerHTML = '✓ Password looks good!';
            validationDiv.className = 'validation-message success-text';
            passwordInput.classList.add('valid');
            passwordInput.classList.remove('error');
        } else {
            validationDiv.innerHTML = '';
            passwordInput.classList.remove('error', 'valid');
        }
    }
    
    // ============================================
    // FORM SUBMISSION VALIDATION
    // ============================================
    // Runs when user clicks the login button
    // Prevents form submission if there are validation errors
    function validateForm() {
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        
        const emailResult = validateEmail(email);
        const passwordResult = validatePassword(password);
        
        let allErrors = [];
        
        if (!emailResult.isValid) {
            allErrors.push(...emailResult.errors);
        }
        
        if (!passwordResult.isValid) {
            allErrors.push(...passwordResult.errors);
        }
        
        // If there are errors, show them in an alert box and prevent submission
        if (allErrors.length > 0) {
            alert('Please fix the following errors:\n\n- ' + allErrors.join('\n- '));
            return false;
        }
        
        return true;  // Allow form submission
    }
    
    // ============================================
    // ADD EVENT LISTENERS FOR REAL-TIME VALIDATION
    // ============================================
    // These listeners trigger validation as the user types
    document.addEventListener('DOMContentLoaded', function() {
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        
        if (emailInput) {
            emailInput.addEventListener('input', showEmailValidation);
            emailInput.addEventListener('blur', showEmailValidation);
        }
        
        if (passwordInput) {
            passwordInput.addEventListener('input', showPasswordValidation);
            passwordInput.addEventListener('blur', showPasswordValidation);
        }
    });
</script>

<!-- ============================================
     INCLUDE POLICIES FOOTER
     Adds the consistent footer with policies and copyright info
     
============================================ -->
<?php include 'footer.php'; ?>

</body>
</html>