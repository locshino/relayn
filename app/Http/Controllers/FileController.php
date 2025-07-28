<?php

// app/Http/Controllers/FileController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FileController extends Controller
{
    /**
     * Handle the request to download the mass order template file.
     */
    public function downloadMassOrderTemplate(): BinaryFileResponse
    {
        // Define the path to the sample file inside the storage directory.
        $path = storage_path('app/public/templates/mass_order_template.xlsx');

        // Check if the file exists before attempting to download.
        if (! File::exists($path)) {
            // If the file is not found, abort with a 404 error.
            abort(404, 'File not found.');
        }

        // Return the file as a download response.
        return response()->download($path);
    }
}
