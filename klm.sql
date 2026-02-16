USE klm;

CREATE TABLE sign_up (
sign_upID int auto_increment PRIMARY KEY,
name varchar(255) NOT NULL,
email varchar(255) NOT NULL,
demo varchar(255) NOT NULL,
department varchar(255) NULL,
registered_at datetime default current_timestamp
);

CREATE TABLE contact (
contactID int auto_increment PRIMARY KEY,
name varchar(255) NOT NULL,
email varchar(255) NOT NULL,
message text NOT NULL,
sent_at datetime default current_timestamp
);

CREATE TABLE schedule (
    scheduleID INT AUTO_INCREMENT PRIMARY KEY,
    event VARCHAR(255) NOT NULL,
    date DATE NOT NULL,
    time TIME NOT NULL,
    location VARCHAR(255) NOT NULL
);

CREATE TABLE demos (
    demoID INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    image_url VARCHAR(255),
    video_url VARCHAR(255)
);

INSERT INTO schedule (event, date, time, location)
VALUES
-- Woensdag (Day 1)
('Opening & Welcome', '2026-03-18', '09:00:00', 'Main Hall'),
('AI in Aviation', '2026-03-18', '10:00:00', 'Hangar 7'),
('Sustainable Flying Demo', '2026-03-18', '11:30:00', 'Hangar 9'),
('Lunch Break', '2026-03-18', '12:30:00', 'Canteen'),
('VR Training Simulator', '2026-03-18', '14:00:00', 'Training Center'),
('Smart Maintenance Workshop', '2026-03-18', '15:30:00', 'Hangar 14'),

-- Donderdag (Day 2)
('Passenger Experience Workshop', '2026-03-19', '09:00:00', 'Hangar 14'),
('Robotics & Automation', '2026-03-19', '10:30:00', 'Hangar 5'),
('Biofuel Innovation', '2026-03-19', '11:30:00', 'Lab Room 2'),
('Lunch Break', '2026-03-19', '12:30:00', 'Canteen'),
('IoT in Aviation', '2026-03-19', '14:00:00', 'Conference Room A'),
('Predictive Analytics Demo', '2026-03-19', '15:30:00', 'Hangar 7'),

-- Vrijdag (Day 3)
('Digital Twin Technology', '2026-03-20', '09:00:00', 'Innovation Lab'),
('Blockchain for Aviation', '2026-03-20', '10:30:00', 'Conference Room B'),
('3D Printing Workshop', '2026-03-20', '11:30:00', 'Workshop Area'),
('Lunch Break', '2026-03-20', '12:30:00', 'Canteen'),
('Future of Flight Demo', '2026-03-20', '14:00:00', 'Main Hall'),
('Closing Ceremony & Networking', '2026-03-20', '16:00:00', 'Main Hall');

INSERT INTO demos (title, description, image_url, video_url)
VALUES
('AI in Aviation', 'Discover how artificial intelligence is optimizing processes within the aviation industry.', '../img/AI-in-Aviation.png', NULL),
('Sustainable Flying Demo', 'Learn all about sustainable innovations at KLM.', '../img/sustainability.jpg', NULL),
('Passenger Experience Workshop', 'Interactive workshop about the future of passenger experience.', '../img/passenger.jpg', NULL),
('VR Training Simulator', 'Experience next-generation virtual reality training for aviation professionals.', '../img/vr-training.jpg', NULL),
('Smart Maintenance Workshop', 'Explore AI-powered predictive maintenance solutions for aircraft.', '../img/maintenance.jpg', NULL),
('Robotics & Automation', 'See how robotics is transforming aircraft maintenance and ground operations.', '../img/robotics.jpg', NULL),
('Biofuel Innovation', 'Discover breakthrough sustainable aviation fuel technologies.', '../img/biofuel.jpg', NULL),
('IoT in Aviation', 'Learn how Internet of Things is connecting aircraft systems for better efficiency.', '../img/iot.jpg', NULL),
('Predictive Analytics Demo', 'See real-time data analytics predicting maintenance needs and optimizing operations.', '../img/analytics.jpg', NULL),
('Digital Twin Technology', 'Explore digital replicas of aircraft for testing and optimization.', '../img/digital-twin.jpg', NULL),
('Blockchain for Aviation', 'Understand how blockchain ensures secure data sharing in aviation.', '../img/blockchain.jpg', NULL),
('3D Printing Workshop', 'Hands-on experience with 3D printing of aircraft components.', '../img/3d-printing.jpg', NULL),
('Future of Flight Demo', 'Vision of next-generation aircraft and flying technologies.', '../img/future-flight.jpg', NULL);