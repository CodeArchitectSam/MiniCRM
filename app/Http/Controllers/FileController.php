<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function serveFile(Request $request)
    {
        $path = $request->query('path');
        $expires = $request->query('expires');
        $token = $request->query('token');

        // Validate expiration
        if (now()->timestamp > $expires) {
            abort(403, 'URL has expired.');
        }

        // Validate token (optional, for additional security)
        if (!$token || strlen($token) !== 32) {
            abort(403, 'Invalid token.');
        }

        // Ensure the file exists in private storage
        if (!Storage::disk('local')->exists("{$path}")) {
            abort(404, 'File not found.');
        }

        // Serve the file as a response
        return Storage::disk('local')->response("{$path}");
    }
}
