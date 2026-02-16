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
        $existingDemos = $user->getUserRegistrations($email);
        
        $registeredCount = 0;
        foreach ($selectedDemos as $demo) {
            if (!in_array($demo, $existingDemos)) {
                if ($user->signUp($name, $email, $demo, $department)) {
                    $registeredCount++;
                    
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
            $_SESSION['user_email'] = $email;
            $_SESSION['registered_demos'] = $user->getUserRegistrations($email);
        } else {
            $error_message = 'You are already registered for the selected demo(s).';
        }
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

// Group schedules by date
$schedulesByDate = [];
foreach ($schedules as $schedule) {
    $date = $schedule['date'];
    if (!isset($schedulesByDate[$date])) {
        $schedulesByDate[$date] = [];
    }
    $schedulesByDate[$date][] = $schedule;
}

$demosStmt = $dbh->query("SELECT * FROM demos");
$demos = $demosStmt->fetchAll(PDO::FETCH_ASSOC);

// Get user's registered demos if they have a session
$userEmail = $_SESSION['user_email'] ?? '';
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
            <h2 class="section-title">About the Innovation Fair</h2>
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <div class="card border-0 shadow-lg">
                        <div class="card-body p-5">
                            <p class="lead">
                                The Innovation Pop Up is dedicated to showcasing the latest advancements and creative 
                                solutions in KLM. Innovation events (where technological and innovative developments 
                                are shared) contribute to informing, inspiring and connecting employees to innovation 
                                and thus collectively growing an innovation mindset and progressive actions.
                            </p>
                            <p>
                                The Innovation Safari can be designed as a journey through an aircraft's maintenance 
                                chain, a journey that symbolizes the future of MRO. From the moment an aircraft arrives 
                                at Airframe with the motor tok, through its disassembly at Engine Services, Component 
                                Services, and Airframe, to its reassembly and return with the motor tok. At every point 
                                in this journey, we demonstrate how innovation enables, changes, and improves these steps.
                            </p>
                            <p class="mb-0">
                                <strong>Join us to discover, learn, and connect!</strong>
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
            
            <!-- Carousel -->
            <div id="demosCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3500">
                <div class="carousel-indicators">
                    <?php 
                    $totalSlides = ceil(count($demos) / 3);
                    for ($i = 0; $i < $totalSlides; $i++): 
                    ?>
                        <button type="button" data-bs-target="#demosCarousel" data-bs-slide-to="<?php echo $i; ?>" 
                                <?php echo $i === 0 ? 'class="active" aria-current="true"' : ''; ?> 
                                aria-label="Slide <?php echo $i + 1; ?>"></button>
                    <?php endfor; ?>
                </div>
                
                <div class="carousel-inner">
                    <?php 
                    $chunkedDemos = array_chunk($demos, 3);
                    foreach ($chunkedDemos as $index => $demoChunk): 
                    ?>
                        <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                            <div class="row justify-content-center">
                                <?php foreach ($demoChunk as $demo): ?>
                                    <div class="col-lg-4 col-md-6 mb-4">
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
                    <?php endforeach; ?>
                </div>
                
                <button class="carousel-control-prev" type="button" data-bs-target="#demosCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#demosCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
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
                    1 => 'day-lightblue',  // Thursday - light blue
                    2 => 'day-darkblue'    // Friday - dark blue
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
                                    <div class="<?php echo $scheduleClass; ?>" data-event="<?php echo htmlspecialchars($schedule['event']); ?>">
                                        <div class="d-flex align-items-start">
                                            <?php if (!$isLunch): ?>
                                                <div class="form-check me-2">
                                                    <input class="form-check-input schedule-checkbox" 
                                                           type="checkbox" 
                                                           value="<?php echo htmlspecialchars($schedule['event']); ?>" 
                                                           id="schedule_<?php echo $schedule['scheduleID']; ?>"
                                                           <?php echo $isRegistered ? 'checked' : ''; ?>>
                                                </div>
                                            <?php endif; ?>
                                            <div class="flex-grow-1 schedule-item-content" 
                                                 <?php if (!$isLunch): ?>
                                                 onclick="window.location.href='demo-detail.php?demo=<?php echo urlencode($schedule['event']); ?>'"
                                                 style="cursor: pointer;"
                                                 <?php endif; ?>>
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
            <div class="form-section">
                <p class="text-center mb-4">Have questions? We'd love to hear from you!</p>
                <?php if ($success_message && isset($_POST['contact'])): ?>
                    <div class="alert alert-success" role="alert">
                        <i class="bi bi-check-circle"></i> <?php echo $success_message; ?>
                    </div>
                <?php endif; ?>
                <?php if ($error_message && isset($_POST['contact'])): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="bi bi-exclamation-triangle"></i> <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>
                <form method="POST" action="#contact">
                    <div class="mb-3">
                        <label for="contact_name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="contact_name" name="name" 
                               placeholder="Enter your name" required>
                    </div>
                    <div class="mb-3">
                        <label for="contact_email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="contact_email" name="email" 
                               placeholder="your.email@example.com" required>
                    </div>
                    <div class="mb-4">
                        <label for="contact_message" class="form-label">Message</label>
                        <textarea class="form-control" id="contact_message" name="message" 
                                  rows="5" placeholder="Your message..." required></textarea>
                    </div>
                    <div class="text-center">
                        <button type="submit" name="contact" class="btn btn-klm">
                            <i class="bi bi-send"></i> Send Message
                        </button>
                    </div>
                </form>
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
                                    The Innovation Pop Up is an event showcasing the latest technological developments 
                                    and innovative solutions within KLM. The goal is to inform, inspire, and connect 
                                    employees with innovation.
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
                                    If you wish to cancel your registration, please contact us through the 
                                    <a href="#contact">contact form</a> or use the information in your confirmation email.
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
                                    For further questions, you can use the <a href="#contact">contact form</a>. 
                                    Our team will respond to your inquiry as soon as possible.
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
            
            // Update visual state
            checkboxes.forEach(cb => {
                const scheduleItem = cb.closest('.schedule-item');
                if (scheduleItem) {
                    if (cb.checked) {
                        scheduleItem.classList.add('registered');
                    } else {
                        scheduleItem.classList.remove('registered');
                    }
                }
            });
        }
        
        // Mark already registered checkboxes
        checkboxes.forEach(cb => {
            if (cb.checked) {
                cb.setAttribute('data-registered', 'true');
            }
        });
        
        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateSelectedDemos);
        });
        
        // Prevent checkbox click from triggering card click
        checkboxes.forEach(cb => {
            cb.addEventListener('click', (e) => {
                e.stopPropagation();
            });
        });
        
        updateSelectedDemos();
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
