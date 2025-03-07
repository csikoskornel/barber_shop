<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::all();
        return response()->json($appointments, 200, [], JSON_UNESCAPED_UNICODE);
    }
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'barber_id' => 'required|integer|exists:barbers,id',
                'appointment' => 'required|date'
            ],[
                'required' => 'A(z) :attribute megadása kötelező!',
                'string' => 'A(z) :attribute csak szöveg lehet.',
                'max' => 'A(z) :attribute maximum :max karakter hosszú lehet.',
                'integer' => 'A(z) :attribute csak szám lehet!',
                'exists' => 'A megadott :attribute nem létezik!',
                'date' => 'A(z) :attribute nem megfelelő dátum formátum.'
            ],[
                'name' => 'név',
                'barber_id' => 'fodrász azonosító',
                'appointment' => 'időpont'
            ]);

            $appointment = Appointment::create($request->all());
            return response()->json(['success' => true, 'message' => 'Az időpont sikeresen rögzítve!'], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->errors()], 400, [], JSON_UNESCAPED_UNICODE);
        }
    }
    public function destroy(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|integer|exists:appointments,id'
            ],[
                'id.required' => 'A(z) :attribute megadása kötelező!',
                'id.integer' => 'A(z) :attribute csak szám lehet!',
                'id.exists' => 'A megadott :attribute nem létezik!'
            ],[
                'id' => 'foglalás azonosító'
            ]);

            $appointment = Appointment::findOrFail($request->id);
            $appointment->delete();
            return response()->json(['success' => true, 'message' => 'Az időpont sikeresen törölve!'], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->errors()], 400, [], JSON_UNESCAPED_UNICODE);
        }

    }
}
