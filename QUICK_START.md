# Quick Start Guide - Email Confirmation

## ✅ Implementation Complete!

Your website now sends automatic confirmation emails when users register for workshops.

## 🚀 Quick Test (3 Steps)

1. **Edit test file**: Open `test-email.php` and change line 5 to your email
2. **Visit**: http://localhost/Stage-KLM/test-email.php
3. **Check**: Your email inbox (and spam folder)

## ⚙️ Configure XAMPP Mail (Required)

### Step 1: Edit php.ini
Location: `C:\xampp\php\php.ini`

```ini
SMTP=smtp.gmail.com
smtp_port=587
sendmail_from=your-email@gmail.com
sendmail_path="\"C:\xampp\sendmail\sendmail.exe\" -t"
```

### Step 2: Edit sendmail.ini
Location: `C:\xampp\sendmail\sendmail.ini`

```ini
smtp_server=smtp.gmail.com
smtp_port=587
auth_username=your-email@gmail.com
auth_password=your-app-password
force_sender=your-email@gmail.com
```

### Step 3: Gmail App Password
1. Enable 2FA: https://myaccount.google.com/security
2. Create App Password: https://myaccount.google.com/apppasswords
3. Use that password in sendmail.ini

### Step 4: Restart Apache
Click "Stop" then "Start" in XAMPP Control Panel

## 📧 What Gets Sent

**Subject**: "Your Innovation Fair Workshop Registration"

**Content**:
- Personalized greeting
- Thank you message
- Workshop title
- Date and time
- Location
- KLM-branded HTML design

## 📁 New Files

- `database/mailer.php` - Email class
- `test-email.php` - Test script
- `EMAIL_SETUP.md` - Detailed guide
- `IMPLEMENTATION_SUMMARY.md` - Full documentation

## ❓ Not Working?

Check logs:
- Apache: `C:\xampp\apache\logs\error.log`
- Sendmail: `C:\xampp\sendmail\sendmail.log`

Common fixes:
- Restart Apache after config changes
- Use Gmail App Password (not regular password)
- Check spam folder
- Verify SMTP settings

## 📝 How to Use

Users just need to:
1. Go to website
2. Scroll to "Register for Demos"
3. Fill name, email, select workshop
4. Click "Sign Up"
5. Receive automatic confirmation email!

---

Need help? See `EMAIL_SETUP.md` for detailed instructions.
