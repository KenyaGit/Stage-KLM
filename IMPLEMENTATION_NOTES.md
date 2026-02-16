# Demo Registration System - Implementation Complete

## Latest Updates (3-Day Schedule & Carousel)

### New Features
1. **3-Day Schedule Layout**:
   - Schedule reorganized into 3 columns (Woensdag, Donderdag, Vrijdag)
   - Each day has its own color theme:
     - **Woensdag** (Wednesday): White background with blue header
     - **Donderdag** (Thursday): Light blue gradient background
     - **Vrijdag** (Friday): Dark blue gradient background with white text
   - Events displayed vertically per day
   - Lunch/Opening/Closing events excluded from checkbox selection

2. **Automatic Demo Carousel**:
   - Demos displayed in rotating carousel (3 demos per slide)
   - Auto-rotates every 3.5 seconds
   - Manual navigation with arrow buttons
   - Indicator dots at bottom for slide navigation
   - Smooth transitions between slides

3. **Expanded Content**:
   - **18 scheduled events** across 3 days (6 events per day)
   - **13 innovation demos** covering various technologies
   - More diverse workshop topics

### Database Updates
Run this SQL to update your database:
```bash
mysql -u root -p < database/update_schedule_3day.sql
```

This will:
- Clear existing schedule and demo data
- Insert 18 events across 3 days (March 18-20, 2026)
- Add 13 different innovation demos

### New Demos Added
- VR Training Simulator
- Smart Maintenance Workshop
- Robotics & Automation
- Biofuel Innovation
- IoT in Aviation
- Predictive Analytics Demo
- Digital Twin Technology
- Blockchain for Aviation
- 3D Printing Workshop
- Future of Flight Demo

### Schedule Structure
**Woensdag (March 18)**:
- Opening & Welcome (09:00)
- AI in Aviation (10:00)
- Sustainable Flying Demo (11:30)
- Lunch Break (12:30)
- VR Training Simulator (14:00)
- Smart Maintenance Workshop (15:30)

**Donderdag (March 19)**:
- Passenger Experience Workshop (09:00)
- Robotics & Automation (10:30)
- Biofuel Innovation (11:30)
- Lunch Break (12:30)
- IoT in Aviation (14:00)
- Predictive Analytics Demo (15:30)

**Vrijdag (March 20)**:
- Digital Twin Technology (09:00)
- Blockchain for Aviation (10:30)
- 3D Printing Workshop (11:30)
- Lunch Break (12:30)
- Future of Flight Demo (14:00)
- Closing Ceremony & Networking (16:00)

---

## Original Features

### What's New
1. **Removed standalone "Register" section** from homepage
2. **Clickable demo cards** - Click any demo to see details and register
3. **Clickable schedule cards** - Click events to see demo details
4. **Calendar-based registration** - Select demos with checkboxes in the schedule
5. **Visual feedback** - Registered demos appear with green background
6. **Multiple demo selection** - Select and register for multiple demos at once
7. **Department field** - Optional text input for user's department
8. **Detail pages** - Each demo has its own page showing times and locations

### Database Changes
- Added `department` field (optional) to `sign_up` table
- Added `registered_at` timestamp to track registration time
- Updated `User` class to handle department and multiple registrations

### How to Use

#### For Users:
1. **Browse demos** in the automatic carousel on the homepage
2. **View 3-day schedule** with color-coded days
3. **Click any demo card** to view details and scheduled times
4. **Use the Event Schedule** to:
   - Check boxes next to demos you want to attend
   - Fill in your name, email, and optionally department
   - Click "Register for X Demo(s)" button
5. **Visual feedback**: Selected demos turn green in the schedule
6. **Email confirmation**: You'll receive an email for each demo registered

#### For Developers:
1. **Apply database changes**:
   ```bash
   # For new database
   mysql -u root -p < klm.sql
   
   # For updating existing database
   mysql -u root -p < database/migration_add_department.sql
   mysql -u root -p < database/update_schedule_3day.sql
   ```

2. **Session management**: User registrations are tracked via PHP sessions
   - `$_SESSION['user_email']` - stores user's email
   - `$_SESSION['registered_demos']` - array of registered demo titles

3. **New files**:
   - `demo-detail.php` - Detail page for individual demos
   - `database/migration_add_department.sql` - Database migration
   - `database/update_schedule_3day.sql` - 3-day schedule update

### Key Features

#### Homepage (`index.php`)
- **Demo carousel** with auto-rotation every 3.5 seconds
- **3-day schedule** in column layout with color coding
- Schedule cards have checkboxes for quick selection
- Registered demos show green background
- Quick registration form below schedule
- Removed standalone registration section

#### Detail Page (`demo-detail.php`)
- Shows demo title, description, image, and video
- Lists all scheduled times for the demo
- Registration form with checkboxes for multiple demos
- Shows which demos user is already registered for
- Department field (optional)

#### Database Methods
```php
// Register user for a demo
$user->signUp($name, $email, $demo, $department);

// Get user's registered demos
$registeredDemos = $user->getUserRegistrations($email);
```

### Styling
- **Color-coded days**: White, Light Blue, Dark Blue
- Green background (`schedule-item.registered`) for selected/registered demos
- Carousel with smooth transitions and navigation controls
- Hover effects on clickable cards
- Responsive design maintained
- Bootstrap 5 carousel and form styling

## Testing Checklist
- [x] Click demo cards to open detail pages
- [x] Carousel auto-rotates every 3.5 seconds
- [x] Manual carousel navigation with arrows
- [x] 3-day schedule displays in columns
- [x] Each day has correct color coding
- [x] Select multiple demos with checkboxes
- [x] Submit registration with name, email, department
- [x] Verify green color appears for registered demos
- [x] Check email confirmation is sent
- [ ] Test on mobile devices

## Notes
- Carousel shows 3 demos per slide
- Auto-rotation interval: 3500ms (3.5 seconds)
- Lunch/Opening/Closing events not selectable (no checkbox)
- Department field is optional
- Users can select multiple demos before registering
- Already registered demos are pre-checked and marked
- Session persists registered demos across page loads
- 3-day event: March 18-20, 2026

