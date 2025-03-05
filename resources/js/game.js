window.gameLogic = function() {
    return {
        selections: [
            { planet: null, vehicle: null },
            { planet: null, vehicle: null },
            { planet: null, vehicle: null },
            { planet: null, vehicle: null }
        ],
        allPlanets: [],
        allVehicles: [],
        
        init() {
            // Deep clone the initial vehicles to maintain original counts
            this.allPlanets = window.gameData.planets || [];
            this.allVehicles = JSON.parse(JSON.stringify(window.gameData.vehicles || []));
        },

        get availablePlanets() {
            return this.allPlanets.filter(planet => 
                !this.selections.some(s => s.planet?.name === planet.name)
            );
        },

        availableVehicles(selectedPlanet) {
            if (!selectedPlanet) return [];
            return this.allVehicles.filter(vehicle => 
                vehicle.total_no > 0 && vehicle.max_distance >= selectedPlanet.distance
            );
        },

        selectPlanet(index, planet, closeModal) {
            // If a different planet is selected, reset the vehicle
            if (this.selections[index].planet !== planet) {
                // Return the previously selected vehicle's count back if it exists
                if (this.selections[index].vehicle) {
                    const prevVehicle = this.allVehicles.find(
                        v => v.name === this.selections[index].vehicle.name
                    );
                    if (prevVehicle) {
                        prevVehicle.total_no++;
                    }
                }
                this.selections[index].vehicle = null;
            }
            this.selections[index].planet = planet;

            // Return whether modal should close (when both planet and vehicle are selected)
            return closeModal;
        },

        selectVehicle(index, vehicle, closeModal) {
            // First, check if the planet is selected
            if (!this.selections[index].planet) {
                alert('Please select a planet first');
                return false;
            }

            // Check vehicle availability
            const availableVehicles = this.availableVehicles(this.selections[index].planet);
            const selectedVehicle = availableVehicles.find(v => v.name === vehicle.name);

            if (!selectedVehicle) {
                alert('Vehicle not available for the selected planet');
                return false;
            }

            // If a vehicle was previously selected, return its count
            if (this.selections[index].vehicle) {
                const prevVehicle = this.allVehicles.find(
                    v => v.name === this.selections[index].vehicle.name
                );
                if (prevVehicle) {
                    prevVehicle.total_no++;
                }
            }

            // Reduce the count of the newly selected vehicle
            selectedVehicle.total_no--;
            this.selections[index].vehicle = selectedVehicle;

            // Return whether modal should close
            return closeModal;
        },

        get selectedPlanetsJson() {
            return JSON.stringify(this.selections.map(s => s.planet));
        },

        get selectedVehiclesJson() {
            return JSON.stringify(this.selections.map(s => s.vehicle));
        },

        get isSubmittable() {
            return this.selections.every(s => s.planet && s.vehicle);
        }
    };
};