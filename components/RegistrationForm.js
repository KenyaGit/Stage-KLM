import React, { useState } from 'react';

const RegistrationForm = () => {
    const [name, setName] = useState('');
    const [email, setEmail] = useState('');
    const [demo, setDemo] = useState('');
    const [error, setError] = useState('');

    const handleSubmit = (e) => {
        e.preventDefault();
        if (!name || !email || !demo) {
            setError('Please fill in all fields.');
            return;
        }
        setError('');
        // Handle registration logic here (e.g., send data to an API)
        console.log('Registration successful:', { name, email, demo });
        // Reset form fields
        setName('');
        setEmail('');
        setDemo('');
    };

    return (
        <form onSubmit={handleSubmit}>
            <h2>Register for a Demo</h2>
            {error && <p style={{ color: 'red' }}>{error}</p>}
            <div>
                <label htmlFor="name">Name:</label>
                <input
                    type="text"
                    id="name"
                    value={name}
                    onChange={(e) => setName(e.target.value)}
                    required
                />
            </div>
            <div>
                <label htmlFor="email">Email:</label>
                <input
                    type="email"
                    id="email"
                    value={email}
                    onChange={(e) => setEmail(e.target.value)}
                    required
                />
            </div>
            <div>
                <label htmlFor="demo">Select Demo:</label>
                <select
                    id="demo"
                    value={demo}
                    onChange={(e) => setDemo(e.target.value)}
                    required
                >
                    <option value="">--Please choose an option--</option>
                    <option value="demo1">Demo 1</option>
                    <option value="demo2">Demo 2</option>
                    <option value="demo3">Demo 3</option>
                </select>
            </div>
            <button type="submit">Register</button>
        </form>
    );
};

export default RegistrationForm;