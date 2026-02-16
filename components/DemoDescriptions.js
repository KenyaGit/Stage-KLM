import React, { useEffect, useState } from 'react';

const DemoDescriptions = () => {
    const [demos, setDemos] = useState([]);

    useEffect(() => {
        fetch('/data/demos.json')
            .then(response => response.json())
            .then(data => setDemos(data))
            .catch(error => console.error('Error fetching demo data:', error));
    }, []);

    return (
        <div className="demo-descriptions">
            <h2>Demos and Workshops</h2>
            {demos.length > 0 ? (
                demos.map((demo, index) => (
                    <div key={index} className="demo">
                        <h3>{demo.title}</h3>
                        <p>{demo.description}</p>
                        {demo.teaserVideo && (
                            <iframe
                                width="560"
                                height="315"
                                src={demo.teaserVideo}
                                title={demo.title}
                                frameBorder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowFullScreen
                            ></iframe>
                        )}
                    </div>
                ))
            ) : (
                <p>Loading demos...</p>
            )}
        </div>
    );
};

export default DemoDescriptions;