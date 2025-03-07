<?php

namespace App\Http\Controllers;

use App\Models\Barber;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BarberController extends Controller
{
    public function index()
    {
        $barbers = Barber::all();
        return response()->json($barbers, 200, [], JSON_UNESCAPED_UNICODE);
    }
    public function store(Request $request)
    {
        try {
            $request->validate([
                'barber_name' => 'required|string|max:255'
            ],[
                'barber_name.required' => 'A :attribute megadása kötelező!',
                'barber_name.max' => 'A :attribute maximum :max karakter lehet.'
            ],
            [
                'barber_name' => 'fodrász név'
            ]);
    
            Barber::create($request->all());
            return response()->json(['success' => true, 'message' => 'A fodrász sikeresen rögzítve!'], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->errors()], 400, [], JSON_UNESCAPED_UNICODE);
        }
    }
    public function destroy(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|integer|exists:barbers,id'
            ],[
                'id.required' => 'A :attribute megadása kötelező!',
                'id.integer' => 'A :attribute csak szám lehet!',
                'id.exists' => 'A megadott :attribute nem létezik!'
            ],
            [
                'id' => 'fodrász azonosító'
            ]);
            
            $barber = Barber::findOrFail($request->id);
            $barber->delete();
            return response()->json(['success' => true, 'message' => 'A fodrász sikeresen törölve!'], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->errors()], 400, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
