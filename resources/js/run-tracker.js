// Import your GPS simulator package
const { GPSSimulator } = require('sensor-simulator');

document.addEventListener('DOMContentLoaded', function() {
    // Initialize variables
    let simulator = new GPSSimulator();
    let simulation = null;
    let removeListener = null;
    let isTracking = false;
    let startTime = null;
    let elapsedTime = 0;
    let totalDistance = 0;
    let lastPosition = null;
    
    // Cache DOM elements
    const startButton = document.getElementById('start-tracking');
    const stopButton = document.getElementById('stop-tracking');
    const timeDisplay = document.getElementById('time-display');
    const distanceDisplay = document.getElementById('distance-display');
    const paceDisplay = document.getElementById('pace-display');
    
    // Calculate distance between two coordinates using Haversine formula
    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 3958.8; // Earth's radius in miles
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = 
            Math.sin(dLat/2) * Math.sin(dLat/2) +
            Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * 
            Math.sin(dLon/2) * Math.sin(dLon/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        return R * c;
    }
    
    // Format time as HH:MM:SS
    function formatTime(seconds) {
        const hrs = Math.floor(seconds / 3600);
        const mins = Math.floor((seconds % 3600) / 60);
        const secs = seconds % 60;
        return `${hrs.toString().padStart(2, '0')}:${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
    }
    
    // Format pace as MM:SS per mile
    function formatPace(seconds, miles) {
        if (miles === 0) return '--:--';
        
        const paceSeconds = Math.floor(seconds / miles);
        const mins = Math.floor(paceSeconds / 60);
        const secs = paceSeconds % 60;
        return `${mins}:${secs.toString().padStart(2, '0')}`;
    }
    
    // Update timer display
    function updateTimer() {
        if (isTracking) {
            const currentTime = Math.floor((Date.now() - startTime) / 1000) + elapsedTime;
            timeDisplay.textContent = formatTime(currentTime);
            
            if (totalDistance > 0) {
                paceDisplay.textContent = formatPace(currentTime, totalDistance);
            }
            
            setTimeout(updateTimer, 1000);
        }
    }
    
    // Start tracking button
    startButton.addEventListener('click', function() {
        if (isTracking) return;
        
        isTracking = true;
        startTime = Date.now();
        startButton.disabled = true;
        stopButton.disabled = false;
        
        // Start the simulation from a random location
        simulation = simulator.simulate(
            { latitude: 40.7128, longitude: -74.0060 }, // New York City
            { speed: 3.0, mode: 'random' }
        );
        
        // Listen for position updates
        removeListener = simulator.addListener(position => {
            if (lastPosition) {
                const segmentDistance = calculateDistance(
                    lastPosition.coords.latitude, lastPosition.coords.longitude,
                    position.coords.latitude, position.coords.longitude
                );
                
                totalDistance += segmentDistance;
                distanceDisplay.textContent = totalDistance.toFixed(2);
            }
            
            lastPosition = position;
        });
        
        updateTimer();
    });
    
    // Stop tracking button
    stopButton.addEventListener('click', function() {
        if (!isTracking) return;
        
        isTracking = false;
        elapsedTime += Math.floor((Date.now() - startTime) / 1000);
        startButton.disabled = false;
        stopButton.disabled = true;
        
        if (simulation) {
            simulation.stop();
        }
        
        if (removeListener) {
            removeListener();
        }
        
        // Save the run data
        saveRunData();
    });
    
    // Save run data to the server
    function saveRunData() {
        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        formData.append('distance', totalDistance.toFixed(2));
        formData.append('duration', formatTime(elapsedTime));
        formData.append('date', new Date().toISOString().split('T')[0]);
        formData.append('notes', 'Tracked using simulator');
        
        fetch('/runs', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '/runs';
            } else {
                alert('Error saving run data');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error saving run data');
        });
    }
});