<?php
// Test email functionality
require_once 'database/mailer.php';

// Test data
$testName = "John Doe";
$testEmail = "testklm2025@outlook.com"; // CHANGE THIS TO YOUR EMAIL
$testWorkshopTitle = "AI-Powered Maintenance Prediction";
$testWorkshopDate = "Monday, January 15, 2026";
$testWorkshopTime = "10:00 AM";
$testWorkshopLocation = "Hangar 14, Building E";

echo "<!DOCTYPE html>
<html>
<head>
    <title>Email Test</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; }
        h1 { color: #00A1DE; }
        .result { padding: 15px; margin: 20px 0; border-radius: 5px; }
        .success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .info { background-color: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
        code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>Email Functionality Test</h1>
        <p>Testing the workshop confirmation email system...</p>
        
        <div class='info'>
            <strong>Test Parameters:</strong><br>
            Name: {$testName}<br>
            Email: {$testEmail}<br>
            Workshop: {$testWorkshopTitle}<br>
            Date: {$testWorkshopDate}<br>
            Time: {$testWorkshopTime}<br>
            Location: {$testWorkshopLocation}
        </div>";

try {
    $result = Mailer::sendWorkshopConfirmation(
        $testName,
        $testEmail,
        $testWorkshopTitle,
        $testWorkshopDate,
        $testWorkshopTime,
        $testWorkshopLocation
    );
    
    if ($result) {
        echo "<div class='result success'>
            <strong>✓ Success!</strong><br>
            Email sent successfully to <code>{$testEmail}</code><br>
            Please check your inbox (and spam folder).
        </div>";
    } else {
        echo "<div class='result error'>
            <strong>✗ Failed!</strong><br>
            The mail() function returned false. Please check:<br>
            <ul>
                <li>XAMPP mail configuration in php.ini</li>
                <li>Sendmail configuration in sendmail.ini</li>
                <li>Apache error logs</li>
            </ul>
        </div>";
    }
} catch (Exception $e) {
    echo "<div class='result error'>
        <strong>✗ Error!</strong><br>
        {$e->getMessage()}
    </div>";
}

echo "
        <div class='info'>
            <strong>Configuration Notes:</strong><br>
            <ul>
                <li>Update <code>\$testEmail</code> in this file with your real email address</li>
                <li>Configure SMTP settings in <code>C:\\xampp\\php\\php.ini</code></li>
                <li>Configure sendmail in <code>C:\\xampp\\sendmail\\sendmail.ini</code></li>
                <li>See <code>EMAIL_SETUP.md</code> for detailed instructions</li>
                <li>Restart Apache after any configuration changes</li>
            </ul>
        </div>
    </div>
</body>
</html>";
?>
