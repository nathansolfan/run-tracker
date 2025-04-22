console.log('Run Tracker module loaded');

document.addEventListener('DOMContentLoaded', function() {
    // Initialize variables
    let isTracking = false;
    let startTime = null;
    let elapsedTime = 0;
    let totalDistance = 0;
    let lastPosition = null;
    let routeCoordinates = [];
    let watchId = null;
    const isDevelopment = true; // Set to true for development mode
    
    // Initialize map
    const map = L.map('route-map').setView([40.7128, -74.0060], 14);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
    
    // Route line
    let routeLine = L.polyline([], {color: 'blue', weight: 4}).addTo(map);
    
    // Cache DOM elements
    const startButton = document.getElementById('start-tracking');
    const stopButton = document.getElementById('stop-tracking');
    const timeDisplay = document.getElementById('time-display');
    const distanceDisplay = document.getElementById('distance-display');
    const paceDisplay = document.getElementById('pace-display');
    
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
    
    // For development: add artificial distance
    function addDevDistance() {
        if (isDevelopment && isTracking) {
            // Add a small amount of distance every few seconds
            totalDistance += 0.01;
            distanceDisplay.textContent = totalDistance.toFixed(2);
            
            // Add a slightly jittered point to the route
            if (routeCoordinates.length > 0) {
                const lastPoint = routeCoordinates[routeCoordinates.length - 1];
                const newPoint = [
                    lastPoint[0] + (Math.random() * 0.0002 - 0.0001),
                    lastPoint[1] + (Math.random() * 0.0002 - 0.0001)
                ];
                routeCoordinates.push(newPoint);
                routeLine.setLatLngs(routeCoordinates);
            }
            
            setTimeout(addDevDistance, 2000);
        }
    }
    
    // Try to get current position
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            position => {
                // Permission granted, center map on current location
                const userLocation = [position.coords.latitude, position.coords.longitude];
                map.setView(userLocation, 16);
            },
            error => {
                console.error("Error getting initial location:", error);
            }
        );
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
        totalDistance = 0;
        lastPosition = null;
        distanceDisplay.textContent = "0.00";
        
        // Use real device GPS
        watchId = navigator.geolocation.watchPosition(
            position => {
                // Handle position update
                const latLng = [position.coords.latitude, position.coords.longitude];
                
                // Add position to route
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
                
                lastPosition = {
                    coords: {
                        latitude: position.coords.latitude,
                        longitude: position.coords.longitude
                    }
                };
            },
            error => {
                console.error("Error getting location:", error);
            },
            {
                enableHighAccuracy: true,
                maximumAge: 0,
                timeout: 5000
            }
        );
        
        updateTimer();
        
        // For development mode: simulate movement
        if (isDevelopment) {
            addDevDistance();
        }
    });
    
    // Stop tracking button
    stopButton.addEventListener('click', function() {
        if (!isTracking) return;
        
        isTracking = false;
        elapsedTime += Math.floor((Date.now() - startTime) / 1000);
        startButton.disabled = false;
        stopButton.disabled = true;
        
        if (watchId !== null) {
            // Stop watching GPS position
            navigator.geolocation.clearWatch(watchId);
            watchId = null;
        }
        
        // Log the data we're about to send
        console.log('Route coordinates to save:', routeCoordinates);
        console.log('Total distance:', totalDistance);
        
        // Development mode bypass
        if (isDevelopment && (totalDistance <= 0 || routeCoordinates.length < 2)) {
            // In dev mode, generate some fake data if we don't have enough
            if (routeCoordinates.length < 2) {
                const baseCoord = [40.7128, -74.0060];
                routeCoordinates = [
                    baseCoord,
                    [baseCoord[0] + 0.001, baseCoord[1] + 0.001],
                    [baseCoord[0] + 0.002, baseCoord[1] + 0.002]
                ];
            }
            if (totalDistance <= 0) {
                totalDistance = 0.5;
            }
        } else if (totalDistance <= 0 || routeCoordinates.length < 2) {
            alert('Not enough data collected. Please try again.');
            return;
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
        formData.append('notes', 'Tracked with GPS');
        
        // Convert route coordinates to JSON string
        const routeDataJson = JSON.stringify(routeCoordinates);
        formData.append('route_data', routeDataJson);
        
        // Debug log
        console.log('Saving run:', {
            distance: totalDistance.toFixed(2),
            duration: durationString,
            date: new Date().toISOString().split('T')[0],
            route_points: routeCoordinates.length
        });
        
        // Send data to server
        fetch('/runs', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (response.redirected) {
                window.location.href = response.url;
                return;
            }
            
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error(`Server returned ${response.status}: ${text}`);
                });
            }
            
            return response.json();
        })
        .then(data => {
            if (data && data.success) {
                window.location.href = '/runs';
            } else {
                console.error('Server response indicated failure:', data);
                alert('Failed to save run. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error saving run:', error);
            alert('Error saving run: ' + error.message);
        });
    });
});