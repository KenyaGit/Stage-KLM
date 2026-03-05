<?php
require_once 'database/db.php';
session_start();

$success_message = '';
$error_message = '';

// Handle quick registration from schedule checkboxes
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quick_register'])) {
    $email = $_POST['email'] ?? '';
    $name = $_POST['name'] ?? '';
    $department = $_POST['department'] ?? '';
    $selectedDemos = $_POST['selected_demos'] ?? [];
    
    if ($email && $name && !empty($selectedDemos)) {
        $user = new User($dbh);
        // Get existing registrations from DATABASE for THIS email
        $existingDemos = $user->getUserRegistrations($email);
        
        $registeredCount = 0;
        $demoDetails = [];
        foreach ($selectedDemos as $demo) {
            if (!in_array($demo, $existingDemos)) {
                if ($user->signUp($name, $email, $demo, $department)) {
                    $registeredCount++;
                    $scheduleInfo = $dbh->query(
                        "SELECT date, time, location FROM schedule WHERE event = :demo LIMIT 1",
                        ['demo' => $demo]
                    )->fetch(PDO::FETCH_ASSOC);
                    if ($scheduleInfo) {
                        $demoDetails[] = $demo . ' | ' . date("D d/m/Y", strtotime($scheduleInfo['date'])) . ' at ' . date("H:i", strtotime($scheduleInfo['time'])) . ' - ' . $scheduleInfo['location'];
                    } else {
                        $demoDetails[] = $demo;
                    }
                }
            }
        }
        
        if ($registeredCount > 0) {
            $success_message = "Successfully registered for $registeredCount demo(s)! Check your email for confirmation.";
            // Update session with THIS user's email and registrations
            $_SESSION['user_email'] = $email;
            $_SESSION['registered_demos'] = $user->getUserRegistrations($email);
            $_SESSION['emailjs_demos_index'] = implode(" | ", $demoDetails);
        } else {
            $error_message = 'You are already registered for the selected demo(s).';
        }
    } else {
        $error_message = 'Please fill in all required fields and select at least one demo.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact'])) {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $message = $_POST['message'] ?? '';
    $contact = new Contact($dbh);
    if ($contact->contactUs($name, $email, $message)) {
        $success_message = 'Thank you for your message! We will get back to you soon.';
    } else {
        $error_message = 'Failed to send message. Please try again.';
    }
}

$scheduleStmt = $dbh->query("SELECT * FROM schedule ORDER BY date, time");
$schedules = $scheduleStmt->fetchAll(PDO::FETCH_ASSOC);

// Group schedules by date (max 3 days)
$schedulesByDate = [];
foreach ($schedules as $schedule) {
    $date = $schedule['date'];
    if (!isset($schedulesByDate[$date])) {
        if (count($schedulesByDate) >= 3) continue;
        $schedulesByDate[$date] = [];
    }
    $schedulesByDate[$date][] = $schedule;
}

$demosStmt = $dbh->query("SELECT * FROM demos");
$demos = $demosStmt->fetchAll(PDO::FETCH_ASSOC);

// Get user's registered demos if they have a session
$userEmail = $_SESSION['user_email'] ?? $_SESSION['registration_email'] ?? '';
$registeredDemos = [];
if ($userEmail) {
    $user = new User($dbh);
    $registeredDemos = $user->getUserRegistrations($userEmail);
    $_SESSION['registered_demos'] = $registeredDemos;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KLM Innovation Pop-Up 2026</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <!-- Sticky Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#home">
                <img src="img/KLM2.png" alt="KLM Logo" class="me-2">
                <span class="fw-bold">Innovation Pop Up</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#demos">Demos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#schedule">Schedule</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#faq">FAQ</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Home Section -->
    <section id="home">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mx-auto text-center">
                    <h1><i class="bi bi-rocket-takeoff"></i> KLM Innovation Pop Up 2026</h1>
                    <p class="lead">Discover the Future of Aviation</p>
                    <p class="mb-4">Join us at the Innovation Pop Up to explore cutting-edge technologies and innovations shaping the future of KLM and the aviation industry.</p>
                    <a href="#demos" class="btn btn-klm btn-lg me-2">Explore Demos</a>
                    <a href="#schedule" class="btn btn-outline-light btn-lg">View Schedule</a>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about">
        <div class="container">
            <h2 class="section-title">About the Innovation Pop Up</h2>
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <div class="card border-0 shadow-lg">
                        <div class="card-body p-5">
                            <p class="lead">
                                The KLM Innovation Pop Up is a low-key, high-impact event where innovation 
                                comes to you. Instead of one big fair, we bring innovations directly to the 
                                workplace — across multiple locations within KLM Engineering & Maintenance.
                            </p>
                            <p>
                                Think of it as an Innovation Pop Up: a journey through the MRO chain, from 
                                Airframe to Engine Services and beyond. At each stop, teams showcase how 
                                they are using new technologies and creative solutions to improve their work — 
                                hands-on, interactive, and close to the floor.
                            </p>
                            <p>
                                The goal is simple: inform, inspire, and connect KLM employees with innovation, 
                                and together grow an innovation mindset that drives us forward.
                            </p>
                            <p class="mb-0">
                                <strong>Join us to explore, experience, and get inspired!</strong>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Demos Section -->
    <section id="demos">
        <div class="container">
            <h2 class="section-title">Innovation Demos</h2>
            <p class="text-center mb-4">Explore our cutting-edge innovation showcases</p>
            
            <?php if (isset($_SESSION['registration_email']) && !empty($registeredDemos)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> Successfully registered for <?php echo count($registeredDemos); ?> demo(s)! Check your email for confirmation.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php 
                // Clear the flag after showing the message once
                unset($_SESSION['registration_email']);
                ?>
            <?php endif; ?>
            
            <!-- Demo Grid -->
            <div class="row">
                <?php foreach ($demos as $demo): ?>
                    <div class="col-lg-3 col-md-6 mb-4">
                        <a href="demo-detail.php?demo=<?php echo urlencode($demo['title']); ?>" class="demo-card-link">
                            <div class="demo-card">
                                <?php if (!empty($demo['image_url'])): ?>
                                    <img src="<?php echo htmlspecialchars($demo['image_url']); ?>" 
                                         alt="<?php echo htmlspecialchars($demo['title']); ?>">
                                <?php endif; ?>
                                <div class="demo-card-body">
                                    <h5><?php echo htmlspecialchars($demo['title']); ?></h5>
                                    <p><?php echo htmlspecialchars($demo['description']); ?></p>
                                    <?php if (!empty($demo['video_url'])): ?>
                                        <span class="btn btn-video" onclick="event.preventDefault(); window.open('<?php echo htmlspecialchars($demo['video_url']); ?>', '_blank');">
                                            <i class="bi bi-play-circle"></i> Watch Video
                                        </span>
                                    <?php endif; ?>
                                    <div class="mt-2">
                                        <span class="text-primary"><i class="bi bi-arrow-right-circle"></i> View Details</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Schedule Section -->
    <section id="schedule">
        <div class="container-fluid px-4">
            <h2 class="section-title">3-Day Event Schedule</h2>
            <p class="text-center mb-4">Select demos you want to attend and register below</p>
            
            <?php if ($success_message && isset($_POST['quick_register'])): ?>
                <div class="alert alert-success" role="alert">
                    <i class="bi bi-check-circle"></i> <?php echo $success_message; ?>
                </div>
            <?php endif; ?>
            <?php if ($error_message && isset($_POST['quick_register'])): ?>
                <div class="alert alert-danger" role="alert">
                    <i class="bi bi-exclamation-triangle"></i> <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            
            <div class="row">
                <?php 
                $dayColors = [
                    0 => 'day-white',      // Wednesday - white
                    1 => 'day-white',      // Thursday - white
                    2 => 'day-white'       // Friday - white
                ];
                $dayNames = ['Wednesday', 'Thursday', 'Friday'];
                $dayIndex = 0;
                
                foreach ($schedulesByDate as $date => $daySchedules): 
                    $dayColorClass = $dayColors[$dayIndex] ?? 'day-white';
                    $dayName = $dayNames[$dayIndex] ?? date("l", strtotime($date));
                    $formattedDate = date("F j, Y", strtotime($date));
                ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="day-column <?php echo $dayColorClass; ?>">
                            <div class="day-header">
                                <h3><?php echo $dayName; ?></h3>
                                <p class="mb-0"><?php echo $formattedDate; ?></p>
                            </div>
                            
                            <div class="day-events">
                                <?php foreach ($daySchedules as $schedule): 
                                    $isRegistered = in_array($schedule['event'], $registeredDemos);
                                    $scheduleClass = $isRegistered ? 'schedule-item registered' : 'schedule-item';
                                    // Skip lunch breaks from selection
                                    $isLunch = stripos($schedule['event'], 'lunch') !== false || 
                                               stripos($schedule['event'], 'opening') !== false ||
                                               stripos($schedule['event'], 'closing') !== false;
                                ?>
                                    <div class="<?php echo $scheduleClass; ?>" data-event="<?php echo htmlspecialchars($schedule['event']); ?>"
                                         <?php if (!$isLunch && !$isRegistered): ?>
                                         onclick="toggleScheduleItem(this, '<?php echo htmlspecialchars(addslashes($schedule['event'])); ?>')"
                                         style="cursor: pointer;"
                                         <?php endif; ?>>
                                        <?php if (!$isLunch): ?>
                                            <input class="schedule-checkbox" 
                                                   type="checkbox" 
                                                   value="<?php echo htmlspecialchars($schedule['event']); ?>" 
                                                   id="schedule_<?php echo $schedule['scheduleID']; ?>"
                                                   <?php echo $isRegistered ? 'checked data-registered="true"' : ''; ?>
                                                   style="display:none;">
                                        <?php endif; ?>
                                        <div class="d-flex align-items-start">
                                            <div class="flex-grow-1 schedule-item-content">
                                                <div class="time-badge">
                                                    <i class="bi bi-clock"></i>
                                                    <?php echo date("g:i A", strtotime($schedule['time'])); ?>
                                                </div>
                                                <h5 class="event-title"><?php echo htmlspecialchars($schedule['event']); ?></h5>
                                                <div class="event-location">
                                                    <i class="bi bi-geo-alt-fill"></i>
                                                    <?php echo htmlspecialchars($schedule['location']); ?>
                                                </div>
                                                <?php if ($isRegistered): ?>
                                                    <span class="badge bg-success mt-1">
                                                        <i class="bi bi-check-circle"></i> Registered
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            <?php if (!$isLunch): ?>
                                                <a href="demo-detail.php?demo=<?php echo urlencode($schedule['event']); ?>"
                                                   class="btn-schedule-detail ms-2"
                                                   onclick="event.stopPropagation();"
                                                   title="View details">
                                                    <i class="bi bi-arrow-right-circle-fill"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php 
                    $dayIndex++;
                endforeach; 
                ?>
            </div>
            
            <!-- Quick Registration Form -->
            <div class="row mt-5">
                <div class="col-lg-8 mx-auto">
                    <div class="card border-0 shadow-lg">
                        <div class="card-body p-4">
                            <h4 class="mb-3">Register for Selected Demos</h4>
                            <form method="POST" id="quickRegisterForm">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="quick_name" class="form-label">Name *</label>
                                        <input type="text" class="form-control" id="quick_name" name="name" 
                                               value="<?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?>" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="quick_email" class="form-label">Email *</label>
                                        <input type="email" class="form-control" id="quick_email" name="email" 
                                               value="<?php echo htmlspecialchars($userEmail); ?>" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="quick_department" class="form-label">Department (Optional)</label>
                                        <input type="text" class="form-control" id="quick_department" name="department">
                                    </div>
                                </div>
                                <div id="selected_demos_container"></div>
                                <div class="text-center">
                                    <button type="submit" name="quick_register" class="btn btn-klm" id="registerBtn" disabled>
                                        <i class="bi bi-check-lg"></i> Register for <span id="selectedCount">0</span> Demo(s)
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact">
        <div class="container">
            <h2 class="section-title">Contact Us</h2>
            <div class="form-section text-center">
                <p class="mb-3">Contact us if you have any questions.</p>
                <p>You can send an email to <a href="mailto:Serdar.Cifoglu@klm.com">Serdar.Cifoglu@klm.com</a> and we will respond as soon as possible.</p>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq">
        <div class="container">
            <h2 class="section-title">FAQ</h2>
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <div class="accordion" id="faqAccordion">
                        <!-- FAQ 1 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                        data-bs-target="#faq1">
                                    What is the KLM Innovation Pop Up?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    The Innovation Pop Up is a low-key event where innovation comes to you.
                                    Instead of one big fair, innovations are showcased across multiple locations within KLM E&M like a Safari through the MRO chain.
                                    The goal is to inform, inspire, and connect KLM employees with innovation
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 2 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                        data-bs-target="#faq2">
                                    Where and when does the event take place?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    The event takes place in 2026. For specific dates, times, and locations, 
                                    please check the <a href="#schedule">Schedule section</a> on this page.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 3 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                        data-bs-target="#faq3">
                                    How can I register for a demo?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    You can now register directly through the event schedule by checking the demos you’d like to attend,
                                    or by selecting them individually on the demo pages.After selecting your demos,
                                    simply fill in your name, email address, and department to complete your registration.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 4 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                        data-bs-target="#faq4">
                                    Is participation free?
                                </button>
                            </h2>
                            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Yes, participation in the Innovation Pop Up is completely free for all KLM employees 
                                    and invited guests.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 5 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                        data-bs-target="#faq5">
                                    What demos are available?
                                </button>
                            </h2>
                            <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    View all available demos in the <a href="#demos">Demos section</a>. Here you will find 
                                    detailed information about each innovative solution being presented.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 6 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                        data-bs-target="#faq6">
                                    Can I register for multiple demos?
                                </button>
                            </h2>
                            <div id="faq6" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Yes, you can register for multiple demos in one go through the event schedule. 
                                    Simply check all the demos you would like to attend and then fill in your name,
                                    email address, and department (optional) to complete your registration.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 7 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                        data-bs-target="#faq7">
                                    What if I want to cancel my registration?
                                </button>
                            </h2>
                            <div id="faq7" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    If you wish to cancel your registration, please contact us at <a href="mailto:Serdar.Cifoglu@klm.com">Serdar.Cifoglu@klm.com</a> or use the information in your confirmation email.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 8 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                        data-bs-target="#faq8">
                                    Who can I contact for more questions?
                                </button>
                            </h2>
                            <div id="faq8" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    For further questions, you can send an email to <a href="mailto:Serdar.Cifoglu@klm.com">Serdar.Cifoglu@klm.com</a> and we will respond as soon as possible.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-md-start text-center mb-3 mb-md-0">
                    <p class="mb-0">&copy; 2025 KLM Innovation Fair. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end text-center">
                    <a href="#home" class="me-3"><i class="bi bi-arrow-up-circle"></i> Back to Top</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- EmailJS -->
    <script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@3/dist/email.min.js"></script>
    
    <!-- Schedule Checkbox Handling -->
    <script>
        const checkboxes = document.querySelectorAll('.schedule-checkbox');
        const registerBtn = document.getElementById('registerBtn');
        const selectedCountSpan = document.getElementById('selectedCount');
        const selectedDemosContainer = document.getElementById('selected_demos_container');
        
        function updateSelectedDemos() {
            const selected = Array.from(checkboxes)
                .filter(cb => cb.checked && !cb.hasAttribute('data-registered'))
                .map(cb => cb.value);

            selectedCountSpan.textContent = selected.length;
            registerBtn.disabled = selected.length === 0;

            // Update hidden inputs
            selectedDemosContainer.innerHTML = '';
            selected.forEach(demo => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'selected_demos[]';
                input.value = demo;
                selectedDemosContainer.appendChild(input);
            });

            // Update visual state for non-preregistered items
            checkboxes.forEach(cb => {
                if (!cb.hasAttribute('data-registered')) {
                    const scheduleItem = cb.closest('.schedule-item');
                    if (scheduleItem) {
                        if (cb.checked) {
                            scheduleItem.classList.add('selected-pending');
                        } else {
                            scheduleItem.classList.remove('selected-pending');
                        }
                    }
                }
            });
        }

        function toggleScheduleItem(element, eventName) {
            const cb = element.querySelector('.schedule-checkbox');
            if (!cb || cb.hasAttribute('data-registered')) return;
            cb.checked = !cb.checked;
            updateSelectedDemos();
        }

        updateSelectedDemos();

        // ── EmailJS ──────────────────────────────────────────────
        // Vervang de drie waarden hieronder met jouw eigen EmailJS gegevens
        emailjs.init("2zBR--lVekRwOmgb2"); // ← Dashboard → Account → Public Key

        <?php if ($success_message && isset($_POST['quick_register'])): ?>
        // Stuur bevestigingsmail na succesvolle registratie
        emailjs.send(
            "service_65py1u5",   // ← Dashboard → Email Services → Service ID
            "template_jsvarws",  // ← Dashboard → Email Templates → Template ID
            {
                naam:  "<?php echo addslashes($_POST['name'] ?? ''); ?>",
                email: "<?php echo addslashes($_POST['email'] ?? ''); ?>",
                demos: "<?php echo addslashes($_SESSION['emailjs_demos_index'] ?? implode(', ', $_POST['selected_demos'] ?? [])); ?>"
            }
        ).then(function() {
            console.log("Bevestigingsmail verstuurd!");
        }, function(error) {
            console.error("EmailJS fout:", error);
        });
        // Scroll terug naar de schedule sectie
        window.addEventListener('load', function() {
            const scheduleSection = document.getElementById('schedule');
            if (scheduleSection) {
                const navbarHeight = document.querySelector('.navbar').offsetHeight;
                window.scrollTo({ top: scheduleSection.offsetTop - navbarHeight, behavior: 'instant' });
            }
        });
        <?php endif; ?>
    </script>
    
    <!-- Custom Smooth Scroll -->
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    const navbarHeight = document.querySelector('.navbar').offsetHeight;
                    const targetPosition = target.offsetTop - navbarHeight;
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                    
                    // Close mobile menu if open
                    const navbarCollapse = document.querySelector('.navbar-collapse');
                    if (navbarCollapse.classList.contains('show')) {
                        bootstrap.Collapse.getInstance(navbarCollapse).hide();
                    }
                }
            });
        });
        
        // Add active class to nav links on scroll
        window.addEventListener('scroll', () => {
            const sections = document.querySelectorAll('section');
            const navLinks = document.querySelectorAll('.nav-link');
            const navbarHeight = document.querySelector('.navbar').offsetHeight;
            
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop - navbarHeight - 100;
                const sectionHeight = section.clientHeight;
                if (scrollY >= sectionTop) {
                    current = section.getAttribute('id');
                }
            });
            
            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === `#${current}`) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>