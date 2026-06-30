<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BookingController extends Controller
{
    /**
     * Store a new booking.
     * In production: save to DB, send confirmation email/SMS.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'cab_id'       => 'required|integer',
            'cab_name'     => 'required|string',
            'source'       => 'required|string|max:255',
            'destination'  => 'required|string|max:255',
            'pickup_date'  => 'required|date|after_or_equal:today',
            'pickup_time'  => 'required',
            'trip_type'    => 'required|in:oneway,round-trip',
            'passengers'   => 'required|integer|min:1|max:17',
            'name'         => 'required|string|max:255',
            'phone'        => 'required|digits:10',
            'email'        => 'required|email|max:255',
            'address'      => 'nullable|string|max:500',
            'final_price'  => 'required|numeric|min:0',
        ]);

        // Generate a simple booking reference
        $reference = 'MT-' . strtoupper(substr(md5(uniqid()), 0, 8));

        $payload = array_merge($validated, [
            'reference' => $reference,
            'created_at' => now()->toDateTimeString(),
        ]);

        $dir = storage_path('framework/leads');
        File::ensureDirectoryExists($dir);

        try {
            File::append($dir . '/bookings.jsonl', json_encode($payload) . PHP_EOL);
        } catch (\Throwable $e) {
            Log::error('Booking lead write failed', [
                'path' => $dir . '/bookings.jsonl',
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Booking save nahi ho paayi. Please call or WhatsApp us directly.',
            ], 500);
        }

        return response()->json([
            'success'   => true,
            'message'   => 'Booking confirmed! We will contact you shortly.',
            'reference' => $reference,
            'data'      => $payload,
        ], 201);
    }
}
