<?php
namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class GzipMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Check if the response is a BinaryFileResponse
        if ($response instanceof BinaryFileResponse) {
            return $response; // Skip gzip for file responses
        }

        // Check if the client accepts gzip encoding
        if (strpos($request->header('Accept-Encoding'), 'gzip') !== false) {
            // Set the Content-Encoding header
            $response->header('Content-Encoding', 'gzip');

            // Compress the content
            $content = $response->getContent();
            $compressedContent = gzencode($content, 6);

            // Set the compressed content
            $response->setContent($compressedContent);

            // Update the Content-Length header
            $response->header('Content-Length', strlen($compressedContent));
        }

        return $response;
    }
}
