// This file initializes the application, handles user interactions, and fetches data for the event webpage.

document.addEventListener('DOMContentLoaded', () => {
    loadSchedule();
    loadDemos();
    setupRegistrationForm();
});

function loadSchedule() {
    fetch('./data/schedule.json')
        .then(response => response.json())
        .then(data => {
            const scheduleContainer = document.getElementById('schedule');
            data.forEach(event => {
                const eventElement = document.createElement('div');
                eventElement.innerHTML = `<h3>${event.name}</h3><p>${event.date} - ${event.time} at ${event.location}</p>`;
                scheduleContainer.appendChild(eventElement);
            });
        })
        .catch(error => console.error('Error loading schedule:', error));
}

function loadDemos() {
    fetch('./data/demos.json')
        .then(response => response.json())
        .then(data => {
            const demosContainer = document.getElementById('demos');
            data.forEach(demo => {
                const demoElement = document.createElement('div');
                demoElement.innerHTML = `<h3>${demo.title}</h3><p>${demo.description}</p><a href="${demo.videoLink}">Watch Teaser</a>`;
                demosContainer.appendChild(demoElement);
            });
        })
        .catch(error => console.error('Error loading demos:', error));
}

function setupRegistrationForm() {
    const form = document.getElementById('registration-form');
    form.addEventListener('submit', (event) => {
        event.preventDefault();
        const formData = new FormData(form);
        // Handle form submission logic here
        console.log('Registration data:', Object.fromEntries(formData));
        alert('Thank you for registering!');
        form.reset();
    });
}