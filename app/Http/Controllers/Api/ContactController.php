<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'required|digits:10',
            'message' => 'required|string|max:2000',
        ]);

        $payload = array_merge($validated, [
            'created_at' => now()->toDateTimeString(),
        ]);

        $dir = storage_path('app/leads');
        File::ensureDirectoryExists($dir);
        File::append($dir . '/contacts.jsonl', json_encode($payload) . PHP_EOL);

        return response()->json([
            'success' => true,
            'message' => 'Thank you! We will get back to you shortly.',
        ]);
    }
}
