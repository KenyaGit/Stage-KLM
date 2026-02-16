# Innovation Fair Project

This project is designed to create a webpage and potentially an app for an innovation fair. It includes features for event schedules, demo descriptions, and participant registration.

## Project Structure

```
innovation-fair
├── src
│   ├── index.html          # Main HTML document for the event webpage
│   ├── styles
│   │   └── main.css       # CSS styles for the webpage
│   ├── scripts
│   │   └── app.js         # Main JavaScript file for the application
│   ├── components
│   │   ├── Schedule.js     # Component to display the event schedule
│   │   ├── DemoDescriptions.js # Component to show demo descriptions
│   │   └── RegistrationForm.js  # Component for participant registration
│   ├── data
│   │   ├── schedule.json   # Schedule data for the innovation fair
│   │   └── demos.json      # Demo descriptions data
│   └── assets              # Directory for images, videos, or other assets
├── package.json            # Configuration file for npm
└── README.md               # Documentation for the project
```

## Features

- **Event Schedule**: Displays a list of events with their timings and locations.
- **Demo Descriptions**: Provides details about demos and workshops, including descriptions and teaser videos.
- **Participant Registration**: Allows users to sign up for demos through a registration form.

## Setup Instructions

1. Clone the repository to your local machine.
2. Navigate to the project directory.
3. Run `npm install` to install the necessary dependencies.
4. Open `src/index.html` in your web browser to view the event webpage.

## Usage Guidelines

- Modify the `schedule.json` and `demos.json` files in the `src/data` directory to update event and demo information.
- Customize styles in `src/styles/main.css` to change the appearance of the webpage.
- Extend functionality by adding new components in the `src/components` directory as needed.

## Contributing

Contributions are welcome! Please submit a pull request or open an issue for any suggestions or improvements.