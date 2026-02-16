<?php
require_once 'database/db.php';
session_start();

$success_message = '';
$error_message = '';

// Get demo title from URL
$demoTitle = $_GET['demo'] ?? '';

// Handle registration
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $department = $_POST['department'] ?? '';
    $selectedDemos = $_POST['demos'] ?? [];
    
    if (empty($selectedDemos)) {
        $error_message = 'Please select at least one demo.';
    } else {
        $user = new User($dbh);
        
        // Get existing registrations from DATABASE for THIS email
        $existingDemos = $user->getUserRegistrations($email);
        
        $registeredCount = 0;
        foreach ($selectedDemos as $demo) {
            if (!in_array($demo, $existingDemos)) {
                if ($user->signUp($name, $email, $demo, $department)) {
                    $registeredCount++;
                    
                    // Send confirmation email for each new demo
                    $workshopStmt = $dbh->query(
                        "SELECT s.date, s.time, s.location FROM schedule s 
                         WHERE s.event = :demo LIMIT 1",
                        ['demo' => $demo]
                    );
                    $workshop = $workshopStmt->fetch(PDO::FETCH_ASSOC);
                    
                    if ($workshop) {
                        $workshopDate = date("l, F j, Y", strtotime($workshop['date']));
                        $workshopTime = date("g:i A", strtotime($workshop['time']));
                        $workshopLocation = $workshop['location'];
                        
                        Mailer::sendWorkshopConfirmation($name, $email, $demo, $workshopDate, $workshopTime, $workshopLocation);
                    }
                }
            }
        }
        
        if ($registeredCount > 0) {
            $success_message = "Successfully registered for $registeredCount demo(s)! Check your email for confirmation.";
            // Update session with THIS user's email and registrations
            $_SESSION['user_email'] = $email;
            $_SESSION['registration_email'] = $email;
            $_SESSION['registered_demos'] = $user->getUserRegistrations($email);
            
            // Redirect to homepage demos section after successful registration
            header('Location: index.php#demos');
            exit;
        } else {
            $error_message = 'You are already registered for the selected demo(s).';
        }
    }
}

// Get demo details
$demoStmt = $dbh->query("SELECT * FROM demos WHERE title = :title LIMIT 1", ['title' => $demoTitle]);
$demo = $demoStmt->fetch(PDO::FETCH_ASSOC);

if (!$demo) {
    header('Location: index.php');
    exit;
}

// Get all schedule times for this demo
$scheduleStmt = $dbh->query("SELECT * FROM schedule WHERE event = :event ORDER BY date, time", ['event' => $demoTitle]);
$schedules = $scheduleStmt->fetchAll(PDO::FETCH_ASSOC);

// Get all demos for the registration form
$allDemosStmt = $dbh->query("SELECT * FROM demos");
$allDemos = $allDemosStmt->fetchAll(PDO::FETCH_ASSOC);

// Get user's registered demos from session (only show if there's an active session)
$userEmail = $_SESSION['user_email'] ?? $_SESSION['registration_email'] ?? '';
$registeredDemos = [];
if ($userEmail) {
    $user = new User($dbh);
    $registeredDemos = $user->getUserRegistrations($userEmail);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($demo['title']); ?> - KLM Innovation Pop-Up</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="img/KLM2.png" alt="KLM Logo" class="me-2">
                <span class="fw-bold">Innovation Pop Up</span>
            </a>
            <a href="index.php" class="btn btn-outline-light">
                <i class="bi bi-arrow-left"></i> Back to Home
            </a>
        </div>
    </nav>

    <!-- Demo Detail Section -->
    <section class="demo-detail-section">
        <div class="container py-5">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card border-0 shadow-lg">
                        <div class="card-body p-5">
                            <h1 class="mb-4"><?php echo htmlspecialchars($demo['title']); ?></h1>
                            
                            <?php if (!empty($demo['image_url'])): ?>
                                <img src="<?php echo htmlspecialchars($demo['image_url']); ?>" 
                                     alt="<?php echo htmlspecialchars($demo['title']); ?>"
                                     class="img-fluid rounded mb-4">
                            <?php endif; ?>
                            
                            <p class="lead"><?php echo htmlspecialchars($demo['description']); ?></p>
                            
                            <?php if (!empty($demo['video_url'])): ?>
                                <a href="<?php echo htmlspecialchars($demo['video_url']); ?>" 
                                   target="_blank" class="btn btn-video mb-4">
                                    <i class="bi bi-play-circle"></i> Watch Video
                                </a>
                            <?php endif; ?>
                            
                            <hr class="my-4">
                            
                            <h3 class="mb-3">Scheduled Times</h3>
                            <?php if (!empty($schedules)): ?>
                                <div class="list-group mb-4">
                                    <?php foreach ($schedules as $schedule): ?>
                                        <div class="list-group-item">
                                            <div class="d-flex w-100 justify-content-between align-items-center">
                                                <div>
                                                    <h5 class="mb-1">
                                                        <i class="bi bi-calendar-event text-primary"></i>
                                                        <?php echo date("l, F j, Y", strtotime($schedule['date'])); ?>
                                                    </h5>
                                                    <p class="mb-1">
                                                        <i class="bi bi-clock text-primary"></i>
                                                        <?php echo date("g:i A", strtotime($schedule['time'])); ?>
                                                    </p>
                                                    <p class="mb-0">
                                                        <i class="bi bi-geo-alt-fill text-primary"></i>
                                                        <?php echo htmlspecialchars($schedule['location']); ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">No scheduled times available yet.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Registration Form -->
                    <div class="card border-0 shadow-lg mt-4">
                        <div class="card-body p-5">
                            <h3 class="mb-4">Register for Demos</h3>
                            
                            <?php if ($success_message): ?>
                                <div class="alert alert-success" role="alert">
                                    <i class="bi bi-check-circle"></i> <?php echo $success_message; ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($error_message): ?>
                                <div class="alert alert-danger" role="alert">
                                    <i class="bi bi-exclamation-triangle"></i> <?php echo $error_message; ?>
                                </div>
                            <?php endif; ?>
                            
                            <form method="POST">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name *</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="department" class="form-label">Department (Optional)</label>
                                    <input type="text" class="form-control" id="department" name="department" 
                                           placeholder="e.g., Engineering, Marketing">
                                </div>
                                
                                <div class="mb-4">
                                    <label class="form-label">Select Demos *</label>
                                    <?php foreach ($allDemos as $d): ?>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="demos[]" 
                                                   value="<?php echo htmlspecialchars($d['title']); ?>" 
                                                   id="demo_<?php echo $d['demoID']; ?>">
                                            <label class="form-check-label" for="demo_<?php echo $d['demoID']; ?>">
                                                <?php echo htmlspecialchars($d['title']); ?>
                                                <?php if ($userEmail && in_array($d['title'], $registeredDemos)): ?>
                                                    <span class="badge bg-success">Already Registered</span>
                                                <?php endif; ?>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                
                                <div class="text-center">
                                    <button type="submit" name="register" class="btn btn-klm">
                                        <i class="bi bi-check-lg"></i> Register Now
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-md-start text-center mb-3 mb-md-0">
                    <p class="mb-0">&copy; 2025 KLM Innovation Fair. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end text-center">
                    <a href="index.php" class="me-3"><i class="bi bi-house"></i> Back to Home</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
