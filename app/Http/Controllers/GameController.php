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
    public function instructions()
    {
        return view('instruction');
    }
    
    public function story()
    {
        return view('story');
    }
    
    //Function for after submitting the post request 
    public function findFalcone(Request $request)
    {
        try {
                 // Decode JSON input with fallback to empty arrays for protection against null values
            $selectedPlanets = json_decode($request->input('selected_planets', '[]'), true);
            $selectedVehicles = json_decode($request->input('selected_vehicles', '[]'), true);
            
            $this->validateGameSelections($selectedPlanets, $selectedVehicles);
            
              // Extract just the names for the external API which only accepts name strings
            $planetNames = array_column($selectedPlanets, 'name');
            $vehicleNames = array_column($selectedVehicles, 'name');
    
            $result = $this->gameService->findFalcone($planetNames, $vehicleNames);
            $timeTaken = $this->calculateTimeTaken($selectedPlanets, $selectedVehicles);
            
            // Convert arrays into single structured arrays for the view
            $selections = array_map(fn($planet, $vehicle) => [
                'planet' => $planet,
                'vehicle' => $vehicle
            ], $planetNames, $vehicleNames);
    
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
