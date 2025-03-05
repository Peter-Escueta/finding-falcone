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
            $this->validateGameSelections($request);

            $selectedPlanets = json_decode($request->input('selected_planets'), true);
            $selectedVehicles = json_decode($request->input('selected_vehicles'), true);
    
            $result = $this->gameService->findFalcone($selectedPlanets, $selectedVehicles);
    
            return view('result', compact('result'));
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
    private function validateGameSelections(Request $request)
    {
        $selectedPlanets = json_decode($request->input('selected_planets'), true);
        $selectedVehicles = json_decode($request->input('selected_vehicles'), true);

        if (count($selectedPlanets) !== 4 || count($selectedVehicles) !== 4) {
            throw ValidationException::withMessages([
                'selections' => 'You must select exactly 4 planets and 4 vehicles.'
            ]);
        }

        // Add more validation as needed
    }



}
