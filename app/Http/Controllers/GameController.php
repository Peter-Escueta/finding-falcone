<?php

namespace App\Http\Controllers;

use App\Services\GameService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class GameController extends Controller
{
    private $gameService;

    //initialize construct for utilization of GameService towards functions
    public function __construct(GameService $gameService)
    {
        $this->gameService = $gameService;
    }
    
    //Displays the main page
    public function index()
    {
        return view('game',[ 
            'planets' => $this->gameService->getPlanets(),
            'vehicles'=> $this->gameService->getVehicles()
        ]);
    }
    
    //Function for after submitting the post request 
    public function findFalcone(Request $request)
    {
        try {
            // Decode and validate the selections
            $selectedPlanets = json_decode($request->input('selected_planets'), true);
            $selectedVehicles = json_decode($request->input('selected_vehicles'), true);
            
            $this->validateGameSelections($selectedPlanets, $selectedVehicles);
            
            // Format planet and vehicle names for API request
            $planetNames = array_map(function($planet) {
                return $planet['name'];
            }, $selectedPlanets);
            
            $vehicleNames = array_map(function($vehicle) {
                return $vehicle['name'];
            }, $selectedVehicles);
    
            // Call service to find Falcone
            $result = $this->gameService->findFalcone($planetNames, $vehicleNames);
            
            // Calculate total time taken
            $timeTaken = $this->calculateTimeTaken($selectedPlanets, $selectedVehicles);
            
            // Create selections array for display
            $selections = [];
            for ($i = 0; $i < count($planetNames); $i++) {
                $selections[] = [
                    'planet' => $planetNames[$i],
                    'vehicle' => $vehicleNames[$i]
                ];
            }
    
            return view('result', compact('result', 'timeTaken', 'selections'));
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->getMessage());
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('An error occurred: ' . $e->getMessage());
        }
    }
    private function validateGameSelections($selectedPlanets, $selectedVehicles)
    {
        if (!is_array($selectedPlanets) || !is_array($selectedVehicles)) {
            throw ValidationException::withMessages([
                'selections' => 'Invalid selection format.'
            ]);
        }

        if (count($selectedPlanets) !== 4 || count($selectedVehicles) !== 4) {
            throw ValidationException::withMessages([
                'selections' => 'You must select exactly 4 planets and 4 vehicles.'
            ]);
        }

        // Check for null values in selections
        foreach ($selectedPlanets as $planet) {
            if ($planet === null) {
                throw ValidationException::withMessages([
                    'selections' => 'All planets must be selected.'
                ]);
            }
        }

        foreach ($selectedVehicles as $vehicle) {
            if ($vehicle === null) {
                throw ValidationException::withMessages([
                    'selections' => 'All vehicles must be selected.'
                ]);
            }
        }
    }
    
    private function calculateTimeTaken($planets, $vehicles)
    {
        $totalTime = 0;
        
        for ($i = 0; $i < count($planets); $i++) {
            $distance = $planets[$i]['distance'];
            $speed = $vehicles[$i]['speed'];
            $totalTime += $distance / $speed;
        }
        
        return $totalTime;
    }



}
