window.gameLogic = function() {
    return {
        selections: [
            { planet: null, vehicle: null, travelTime: null },
            { planet: null, vehicle: null, travelTime: null },
            { planet: null, vehicle: null, travelTime: null },
            { planet: null, vehicle: null, travelTime: null }
        ],
        allPlanets: [],
        allVehicles: [],
        
        init() {
            this.allPlanets = window.gameData.planets || [];
            this.allVehicles = JSON.parse(JSON.stringify(window.gameData.vehicles || []));
            
            
            window.addEventListener('reset-game', () => {
                console.log('Reset game triggered in game component');
                this.resetGame();
            });
        },

        resetGame() {        
            this.selections = this.selections.map(() => ({ planet: null, vehicle: null, travelTime: null }));
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

        calculateTravelTime(planet, vehicle) {
            if (!planet || !vehicle) return null;
            return Math.round(planet.distance / vehicle.speed);
        },

        selectPlanet(index, planet, closeModal) {
            if (this.selections[index].planet !== planet) {
                if (this.selections[index].vehicle) {
                    const prevVehicle = this.allVehicles.find(
                        v => v.name === this.selections[index].vehicle.name
                    );
                    if (prevVehicle) {
                        prevVehicle.total_no++;
                    }
                }
                this.selections[index].vehicle = null;
                this.selections[index].travelTime = null;
            }
            this.selections[index].planet = planet;

            return closeModal;
        },

        selectVehicle(index, vehicle, closeModal) {
            if (!this.selections[index].planet) {
                alert('Please select a planet first');
                return false;
            }

            const availableVehicles = this.availableVehicles(this.selections[index].planet);
            const selectedVehicle = availableVehicles.find(v => v.name === vehicle.name);

            if (!selectedVehicle) {
                alert('Vehicle not available for the selected planet');
                return false;
            }

            if (this.selections[index].vehicle) {
                const prevVehicle = this.allVehicles.find(
                    v => v.name === this.selections[index].vehicle.name
                );
                if (prevVehicle) {
                    prevVehicle.total_no++;
                }
            }

            selectedVehicle.total_no--;
            this.selections[index].vehicle = selectedVehicle;

            this.selections[index].travelTime = this.calculateTravelTime(
                this.selections[index].planet,
                selectedVehicle
            );

            return closeModal;
        },

        get totalTravelTime() {
            return this.selections.reduce((total, selection) => 
                total + (selection.travelTime || 0), 0);
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