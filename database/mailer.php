<?php
class Mailer {
    
    public static function sendWorkshopConfirmation($name, $email, $workshopTitle, $workshopDate, $workshopTime, $workshopLocation) {
        $subject = "Your Innovation Fair Workshop Registration";
        
        $htmlBody = self::getEmailTemplate($name, $workshopTitle, $workshopDate, $workshopTime, $workshopLocation);
        
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: KLM Innovation Fair <noreply@klm-innovation.com>" . "\r\n";
        $headers .= "Reply-To: support@klm-innovation.com" . "\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        return mail($email, $subject, $htmlBody, $headers);
    }
    
    private static function getEmailTemplate($name, $workshopTitle, $workshopDate, $workshopTime, $workshopLocation) {
        return '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workshop Registration Confirmation</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
    <table role="presentation" style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="padding: 0;">
                <table role="presentation" style="width: 600px; margin: 0 auto; background-color: #ffffff; border-collapse: collapse;">
                    <!-- Header -->
                    <tr>
                        <td style="padding: 40px 30px; background: linear-gradient(135deg, #00A1DE 0%, #0066CC 100%); text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 28px; font-weight: bold;">
                                KLM Innovation Pop Up 2026
                            </h1>
                            <p style="color: #ffffff; margin: 10px 0 0 0; font-size: 16px;">
                                Registration Confirmation
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Body -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <h2 style="color: #333333; margin: 0 0 20px 0; font-size: 24px;">
                                Dear ' . htmlspecialchars($name) . ',
                            </h2>
                            
                            <p style="color: #666666; line-height: 1.6; margin: 0 0 20px 0; font-size: 16px;">
                                Thank you for registering for the KLM Innovation Pop Up! We\'re excited to have you join us.
                            </p>
                            
                            <p style="color: #666666; line-height: 1.6; margin: 0 0 30px 0; font-size: 16px;">
                                Your registration has been confirmed for the following workshop:
                            </p>
                            
                            <!-- Workshop Details Card -->
                            <table role="presentation" style="width: 100%; border-collapse: collapse; background-color: #f8f9fa; border-radius: 8px; margin-bottom: 30px;">
                                <tr>
                                    <td style="padding: 25px;">
                                        <table role="presentation" style="width: 100%; border-collapse: collapse;">
                                            <tr>
                                                <td style="padding: 10px 0;">
                                                    <strong style="color: #00A1DE; font-size: 14px; text-transform: uppercase;">Workshop</strong>
                                                    <p style="margin: 5px 0 0 0; color: #333333; font-size: 18px; font-weight: bold;">
                                                        ' . htmlspecialchars($workshopTitle) . '
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 15px 0 10px 0; border-top: 1px solid #dee2e6;">
                                                    <strong style="color: #00A1DE; font-size: 14px; text-transform: uppercase;">Date</strong>
                                                    <p style="margin: 5px 0 0 0; color: #333333; font-size: 16px;">
                                                        ' . htmlspecialchars($workshopDate) . '
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 15px 0 10px 0; border-top: 1px solid #dee2e6;">
                                                    <strong style="color: #00A1DE; font-size: 14px; text-transform: uppercase;">Time</strong>
                                                    <p style="margin: 5px 0 0 0; color: #333333; font-size: 16px;">
                                                        ' . htmlspecialchars($workshopTime) . '
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 15px 0 0 0; border-top: 1px solid #dee2e6;">
                                                    <strong style="color: #00A1DE; font-size: 14px; text-transform: uppercase;">Location</strong>
                                                    <p style="margin: 5px 0 0 0; color: #333333; font-size: 16px;">
                                                        ' . htmlspecialchars($workshopLocation) . '
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            
                            <p style="color: #666666; line-height: 1.6; margin: 0 0 20px 0; font-size: 16px;">
                                We look forward to seeing you at the event! Please arrive 10 minutes early to check in.
                            </p>
                            
                            <p style="color: #666666; line-height: 1.6; margin: 0 0 30px 0; font-size: 16px;">
                                If you have any questions or need to make changes to your registration, please don\'t hesitate to contact us.
                            </p>
                            
                            <div style="text-align: center; margin: 30px 0;">
                                <a href="#" style="display: inline-block; padding: 15px 40px; background: linear-gradient(135deg, #00A1DE 0%, #0066CC 100%); color: #ffffff; text-decoration: none; border-radius: 5px; font-size: 16px; font-weight: bold;">
                                    View Event Details
                                </a>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="padding: 30px; background-color: #f8f9fa; text-align: center; border-top: 1px solid #dee2e6;">
                            <p style="color: #999999; margin: 0 0 10px 0; font-size: 14px;">
                                Best regards,<br>
                                <strong>KLM Innovation Fair Team</strong>
                            </p>
                            <p style="color: #999999; margin: 20px 0 0 0; font-size: 12px;">
                                &copy; 2025 KLM Innovation Fair. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
        ';
    }
}
?>
