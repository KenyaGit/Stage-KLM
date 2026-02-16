import React, { useEffect, useState } from 'react';

const Schedule = () => {
    const [schedule, setSchedule] = useState([]);

    useEffect(() => {
        fetch('/data/schedule.json')
            .then(response => response.json())
            .then(data => setSchedule(data))
            .catch(error => console.error('Error fetching schedule:', error));
    }, []);

    return (
        <div>
            <h2>Event Schedule</h2>
            <ul>
                {schedule.map((event, index) => (
                    <li key={index}>
                        <strong>{event.name}</strong><br />
                        {event.date} - {event.time}<br />
                        Location: {event.location}
                    </li>
                ))}
            </ul>
        </div>
    );
};

export default Schedule;