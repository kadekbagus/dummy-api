<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Person;
use Response;
use StdClass;
use Validator;

class PersonAPIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $httpCode = 200;

        $take = isset($request->take) ? $request->take : 5;
        $skip = isset($request->skip) ? $request->skip : 0;

        // Validate the request
        $validator = Validator::make(
            array(
                'take'  => $take,
                'skip'  => $skip,
            ),
            array(
                'take' => 'numeric',
                'skip' => 'numeric',
            )
        );

        // Run the validation
        if ($validator->fails()) {
            $errorMessage = $validator->messages()->first();

            return response()->json([
                'code'    => 14,
                'message' => $errorMessage,
                'data' => null,
            ], $httpCode);
        }

        $persons = Person::whereNotNull('email');

        $_persons = clone $persons;

        $persons->take($take);
        $persons->skip($skip);

        $totalData = $_persons->count();
        $listOfData = $persons->get();

        $returnedData = new StdClass;
        $returnedData->total_records = $totalData;
        $returnedData->returned_records = count($listOfData);
        $returnedData->records = $listOfData;

        return response()->json([
            'code'    => 0,
            'message' => 'success',
            'data'    => $returnedData,
        ], $httpCode);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $httpCode = 200;

        // Validate the request
        $validator = Validator::make(
            array(
                'first_name' => $request->first_name,
                'last_name'  => $request->last_name,
                'email'      => $request->email,
            ),
            array(
                'first_name' => 'required|unique:persons',
                'last_name'  => 'required',
                'email'      => 'required|email',
            ),
            array(
            )
        );

        // Run the validation
        if ($validator->fails()) {
            $errorMessage = $validator->messages()->first();

            return response()->json([
                'code'    => 14,
                'message' => $errorMessage,
                'data' => null,
            ], $httpCode);
        }

        // Insert data
        $newPerson = new Person();
        $newPerson->first_name = $request->first_name;
        $newPerson->last_name = $request->last_name;
        $newPerson->email = $request->email;
        $newPerson->age = $request->age;
        $newPerson->gender = $request->gender;
        $newPerson->id_card_number = $request->id_card_number;
        $newPerson->date_of_birth = $request->date_of_birth;
        $newPerson->address = $request->address;
        $newPerson->save();

        return response()->json([
            'code'    => 0,
            'message' => 'success',
            'data'    => $newPerson,
        ], $httpCode);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $httpCode = 200;

        // Validate the request
        $validator = Validator::make(
            array(
                'id'  => $id,
            ),
            array(
                'id' => 'numeric',
            )
        );

        // Run the validation
        if ($validator->fails()) {
            $errorMessage = $validator->messages()->first();

            return response()->json([
                'code'    => 14,
                'message' => $errorMessage,
                'data' => null,
            ], $httpCode);
        }

        $person = Person::where('id_person', $id)->first();

        if (!$person) {
            $errorMessage = 'data not found';
            return response()->json([
                'code'    => 2,
                'message' => $errorMessage,
                'data' => null,
            ], $httpCode);
        }

        return response()->json([
            'code'    => 0,
            'message' => 'success',
            'data'    => $person,
        ], $httpCode);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
