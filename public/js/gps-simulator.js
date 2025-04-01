/**
 * GPS simulator for testing location-based applications
 */
class GPSSimulator {
    constructor(options = {}) {
      this.options = {
        updateInterval: 1000,
        accuracy: 10,
        ...options
      };
      this.listeners = [];
      this.intervalId = null;
    }
  
    /**
     * Begin GPS simulation from a starting coordinate
     * @param {Object} startCoords - {latitude, longitude} starting position
     * @param {Object} config - Configuration options
     * @returns {Object} - Control methods for the simulation
     */
    simulate(startCoords, config = {}) {
      const defaults = {
        mode: 'random',  // 'random' or 'path'
        speed: 2.5,      // Simulated movement speed
        waypoints: []    // Array of coordinates for path mode
      };
      
      const pathConfig = { ...defaults, ...config };
      
      const position = {
        coords: { 
          latitude: startCoords.latitude,
          longitude: startCoords.longitude,
          accuracy: this.options.accuracy,
          altitude: null,
          altitudeAccuracy: null,
          heading: null,
          speed: pathConfig.speed
        },
        timestamp: Date.now()
      };
      
      // Clear any existing simulation
      this.stop();
      
      this.intervalId = setInterval(() => {
        this._updatePosition(position, pathConfig);
        this._notifyListeners(position);
      }, this.options.updateInterval);
      
      return {
        isActive: () => this.intervalId !== null,
        getCurrentPosition: () => ({ ...position }),
        stop: () => this.stop()
      };
    }
    
    /**
     * Stop any active GPS simulation
     */
    stop() {
      if (this.intervalId) {
        clearInterval(this.intervalId);
        this.intervalId = null;
      }
    }
    
    /**
     * Add a listener for position updates
     * @param {Function} callback - Function to call with position updates
     * @returns {Function} - Function to remove this listener
     */
    addListener(callback) {
      this.listeners.push(callback);
      
      return () => {
        const index = this.listeners.indexOf(callback);
        if (index !== -1) {
          this.listeners.splice(index, 1);
        }
      };
    }
    
    /**
     * Update the simulated position based on configuration
     * @private
     */
    _updatePosition(position, config) {
      if (config.mode === 'random') {
        // Random movement with configured speed factor
        position.coords.latitude += (Math.random() - 0.5) * 0.0001 * config.speed;
        position.coords.longitude += (Math.random() - 0.5) * 0.0001 * config.speed;
      } else if (config.mode === 'path' && config.waypoints.length > 0) {
        // Path following logic would go here
        // Would move toward next waypoint in the path
      }
      
      position.timestamp = Date.now();
    }
    
    /**
     * Notify all listeners of position update
     * @private
     */
    _notifyListeners(position) {
      const positionCopy = JSON.parse(JSON.stringify(position));
      this.listeners.forEach(callback => callback(positionCopy));
    }
  }
  
  window.GPSSimulator = GPSSimulator;
