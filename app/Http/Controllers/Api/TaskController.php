<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function fivesEnemy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start'         => 'required|numeric',
            'end'        => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message'  => $validator->errors()->first(),
            ], 201);
        }

        $start = $request->start;
        $end = $request->end;
        if($start > $end)
        {
            return response()->json([
                'message'  => "Start number must be smaller than end number",
            ], 201);
        }

        $fiveEnemies = array();

        $range = range($start,$end);

        foreach($range as $number){
            if($number % 5 == 0 && $number % 2 != 0){
                continue;
            }else{
                array_push($fiveEnemies, $number);
            }
        }

        return response()->json([
            'result' => count($fiveEnemies)
        ]);
    }

    public function searchAlphabet(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'input_string' => 'required|string|min:1|max:3',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message'  => $validator->errors()->first(),
            ], 201);
        }

        $inputString = $request->input_string;
        $alphabeticStrings = array();
        array_push($alphabeticStrings, "0");

        for($alphabeticString = 'A'; $alphabeticString < 'ZZZ'; $alphabeticString++)
            array_push($alphabeticStrings, $alphabeticString);


        $key = array_search($inputString, $alphabeticStrings);

        if(!$key)
            return response()->json([
                'message'  => "String Not Found",
            ], 200);

        return $key;
    }

    public function theLeastStepsToZero(Request $request)
    {
        $counter = 0;
        $input = 10;
        $loopIterations = $input;

        $steps = $request->steps;

        for($loopIterations ; $loopIterations > 0 ; $loopIterations--)
        {
            if($input == 0)
            {
                break;
            }
            if($input % $steps == 0)
            {
                $counter++;
                $input = $input / 2;

            }else{

                $input = $input - 1;
                $counter++;

            }


        }return $counter;
    }
}
