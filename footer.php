<!-- footer.php - Reusable Policies Footer -->
<style>
    /* Footer Styles */
    .policy-footer {
        background: #1a1a2e;
        color: #ccc;
        padding: 40px 50px 20px;
        margin-top: 50px;
    }

    .footer-container {
        max-width: 1400px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 30px;
    }

    .footer-section h4 {
        color: #ff6600;
        margin-bottom: 15px;
        font-size: 16px;
    }

    .footer-section ul {
        list-style: none;
    }

    .footer-section li {
        margin-bottom: 8px;
    }

    .footer-section a {
        color: #ccc;
        text-decoration: none;
        font-size: 13px;
        transition: 0.3s;
    }

    .footer-section a:hover {
        color: #ff6600;
    }

    .copyright {
        text-align: center;
        padding-top: 30px;
        margin-top: 30px;
        border-top: 1px solid #333;
        font-size: 12px;
    }

    @media (max-width: 768px) {
        .policy-footer {
            padding: 30px 20px 15px;
        }
        .footer-container {
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
    }

    @media (max-width: 480px) {
        .footer-container {
            grid-template-columns: 1fr;
            text-align: center;
        }
    }
</style>

<footer class="policy-footer">
    <div class="footer-container">
        
        <!-- Policies Section -->
        <div class="footer-section">
            <h4>📜 Smart Meal Policies</h4>
            <ul>
                <li><a href="#">Privacy Policy</a></li>
                <li><a href="#">Terms of Service</a></li>
                <li><a href="#">Refund & Cancellation</a></li>
                <li><a href="#">Delivery Policy</a></li>
            </ul>
        </div>

        <!-- Quick Links Section -->
        <div class="footer-section">
            <h4>🍽️ Quick Links</h4>
            <ul>
                <li><a href="../index.php">Home</a></li>
                <li><a href="staff_login.php">Staff Login</a></li>
                <li><a href="customer_login.php">Customer Login</a></li>
                <li><a href="../menu_dashboard.php">Menu Dashboard</a></li>
            </ul>
        </div>

        <!-- Support Section -->
        <div class="footer-section">
            <h4>📞 Support</h4>
            <ul>
                <li><a href="#">Help Center</a></li>
                <li><a href="#">Contact Admin</a></li>
                <li><a href="#">24/7 Support</a></li>
                <li><a href="#">Report an Issue</a></li>
            </ul>
        </div>

        <!-- Compliance Section -->
        <div class="footer-section">
            <h4>✅ Compliance</h4>
            <ul>
                <li><a href="#">Food Safety Standards</a></li>
                <li><a href="#">Halal Certified</a></li>
                <li><a href="#">Quality Assurance</a></li>
                <li><a href="#">ISO 22000 Certified</a></li>
            </ul>
        </div>

        <!-- Contact Section -->
        <div class="footer-section">
            <h4>🏢 Contact Us</h4>
            <ul>
                <li>📧 info@smartmeal.com</li>
                <li>📞 (555) 123-4567</li>
                <li>📍 123 Food Street, City</li>
            </ul>
        </div>
    </div>
    
    <div class="copyright">
        &copy; <?php echo date('Y'); ?> Smart Meal Food Ordering System. All rights reserved. | Version 2.0
    </div>
</footer>