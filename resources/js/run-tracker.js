document.addEventListener('DOMContentLoaded', function() {
    // Initialize variables
    const simulator = new window.GPSSimulator();
    let simulation = null;
    let removeListener = null;
    let isTracking = false;
    let startTime = null;
    let elapsedTime = 0;
    let totalDistance = 0;
    let lastPosition = null;
    let routeCoordinates = [];

    let useRealGPS = false;
let watchId = null;
    
    // Initialize map
    const map = L.map('route-map').setView([40.7128, -74.0060], 14);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
    
    // Route line
    let routeLine = L.polyline([], {color: 'blue', weight: 4}).addTo(map);
    
    // Predefined waypoints - Central Park loop example
    const waypoints = [
        {latitude: 40.7812, longitude: -73.9665}, // Northeast corner
        {latitude: 40.7812, longitude: -73.9815}, // Northwest corner
        {latitude: 40.7682, longitude: -73.9815}, // Southwest corner
        {latitude: 40.7682, longitude: -73.9665}  // Southeast corner
    ];
    
    // Add waypoint markers
    waypoints.forEach((waypoint, index) => {
        L.marker([waypoint.latitude, waypoint.longitude])
            .addTo(map)
            .bindPopup(`Waypoint ${index + 1}`);
    });
    
    // Cache DOM elements
    const startButton = document.getElementById('start-tracking');
    const stopButton = document.getElementById('stop-tracking');
    const timeDisplay = document.getElementById('time-display');
    const distanceDisplay = document.getElementById('distance-display');
    const paceDisplay = document.getElementById('pace-display');
    const routeTypeSelect = document.getElementById('route-type');

    const useRealGPSCheckbox = document.getElementById('use-real-gps');

    // Add a listener for the toggle
useRealGPSCheckbox.addEventListener('change', function() {
    useRealGPS = this.checked;
    
    // If we're using real GPS, check for permission
    if (useRealGPS) {
        if (!navigator.geolocation) {
            alert("Geolocation is not supported by your browser");
            useRealGPSCheckbox.checked = false;
            useRealGPS = false;
        } else {
            // Test for permission by getting current position
            navigator.geolocation.getCurrentPosition(
                position => {
                    // Permission granted, center map on current location
                    const userLocation = [position.coords.latitude, position.coords.longitude];
                    map.setView(userLocation, 16);
                    // Add a marker for current position
                    L.marker(userLocation).addTo(map)
                        .bindPopup("Your location")
                        .openPopup();
                },
                error => {
                    // Permission denied or error
                    alert("Unable to access your location. Using simulation instead.");
                    useRealGPSCheckbox.checked = false;
                    useRealGPS = false;
                }
            );
        }
    }
});
    
    // Calculate distance function (Haversine)
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
    
    // Update timer display
    function updateTimer() {
        if (isTracking) {
            const currentTime = Math.floor((Date.now() - startTime) / 1000) + elapsedTime;
            timeDisplay.textContent = formatTime(currentTime);
            
            if (totalDistance > 0) {
                const paceSeconds = Math.floor(currentTime / totalDistance);
                const paceMinutes = Math.floor(paceSeconds / 60);
                const paceRemainder = Math.floor(paceSeconds % 60);
                paceDisplay.textContent = `${paceMinutes}:${paceRemainder.toString().padStart(2, '0')}`;
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
        
        // Reset route data
        routeCoordinates = [];
        routeLine.setLatLngs([]);
        
        // Get route type
        const routeType = routeTypeSelect.value;
        
        // Pick starting position
        let startPosition;
        
        if (routeType === 'path' && waypoints.length > 0) {
            // Start at first waypoint
            startPosition = {
                latitude: waypoints[0].latitude,
                longitude: waypoints[0].longitude
            };
            
            // Center map on first waypoint
            map.setView([startPosition.latitude, startPosition.longitude], 14);
        } else {
            // Random start position (New York City)
            startPosition = {
                latitude: 40.7128,
                longitude: -74.0060
            };
            
            // Center map on start position
            map.setView([startPosition.latitude, startPosition.longitude], 14);
        }
        
        // Start the simulation
        simulation = simulator.simulate(
            startPosition,
            { 
                speed: 3.0, 
                mode: routeType,
                waypoints: waypoints
            }
        );
        
        // Listen for position updates
        removeListener = simulator.addListener(position => {
            // Add position to route
            const latLng = [position.coords.latitude, position.coords.longitude];
            routeCoordinates.push(latLng);
            routeLine.setLatLngs(routeCoordinates);
            
            // Pan map to follow current position
            map.panTo(latLng);
            
            // Calculate distance if we have a previous position
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
        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        formData.append('distance', totalDistance.toFixed(2));
        
        // Format duration for the server
        const hours = Math.floor(elapsedTime / 3600);
        const minutes = Math.floor((elapsedTime % 3600) / 60);
        const seconds = elapsedTime % 60;
        const durationString = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        
        formData.append('duration', durationString);
        formData.append('date', new Date().toISOString().split('T')[0]);
        formData.append('notes', routeTypeSelect.value === 'path' ? 'Central Park Loop' : 'Random Route');
        
        // Simple route data as JSON
        formData.append('route_data', JSON.stringify(routeCoordinates));
        
        fetch('/runs', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (response.redirected) {
                window.location.href = response.url;
            } else {
                return response.json();
            }
        })
        .then(data => {
            if (data && data.success) {
                window.location.href = '/runs';
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
});