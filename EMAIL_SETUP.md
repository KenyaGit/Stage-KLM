# Email Configuration for Workshop Registration

## Overview
The website now sends automatic confirmation emails when users register for workshops. The email includes:
- User's name
- Workshop title
- Date and time
- Location
- Professional HTML-styled template

## Email Configuration for XAMPP

### Option 1: Using PHP mail() with XAMPP (Local Testing)

1. **Configure php.ini**:
   - Open `C:\xampp\php\php.ini`
   - Find and update these settings:
   ```ini
   [mail function]
   SMTP=smtp.outlook.com
   smtp_port=587
   sendmail_from=your-email@outlook.com
   sendmail_path="\"C:\xampp\sendmail\sendmail.exe\" -t"
   ```

2. **Configure sendmail.ini**:
   - Open `C:\xampp\sendmail\sendmail.ini`
   - Update these settings:
   ```ini
   [sendmail]
   smtp_server=smtp.outlook.com
   smtp_port=587
   auth_username=your-email@outlook.com
   auth_password=your-app-password
   force_sender=your-email@outlook.com
   ```

3. **For Gmail**: 
   - Enable 2-factor authentication
   - Generate an App Password at: https://myaccount.google.com/apppasswords
   - Use the App Password in `auth_password`

4. **Restart Apache** after configuration changes

### Option 2: Using PHPMailer (Recommended for Production)

If you want to use PHPMailer instead, install it via Composer:

```bash
composer require phpmailer/phpmailer
```

Then update `database/mailer.php` to use PHPMailer class instead of mail() function.

## Testing

1. Register for a workshop through the website
2. Check the email inbox of the address you provided
3. Check XAMPP error logs if emails don't arrive:
   - `C:\xampp\apache\logs\error.log`
   - `C:\xampp\sendmail\sendmail.log`

## Email Features

- **Subject**: "Your Innovation Fair Workshop Registration"
- **HTML Template**: Professional KLM-branded email with gradient header
- **Workshop Details**: Includes all relevant information in a styled card
- **Responsive Design**: Looks great on desktop and mobile devices

## Troubleshooting

- **Emails not sending**: Check XAMPP logs and verify SMTP settings
- **Gmail blocking**: Use App Passwords instead of regular password
- **Port issues**: Try port 465 with SSL or port 587 with TLS
- **Test email function**: Create a simple test script to verify mail() works

## Files Modified

- `database/mailer.php` - New file with email functionality
- `database/db.php` - Includes mailer class
- `index.php` - Already had email sending logic (lines 27-29)
