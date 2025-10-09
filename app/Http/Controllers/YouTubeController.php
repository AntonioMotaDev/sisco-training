<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class YouTubeController extends Controller
{
    /**
     * Get YouTube video information including duration
     */
    public function getVideoInfo(Request $request)
    {
        $request->validate([
            'video_id' => 'required|string|size:11'
        ]);

        $videoId = $request->video_id;
        
        $apiKey = env('YOUTUBE_API_KEY');
        
        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'YouTube API key no configurado. Contacta al administrador.',
                'duration_seconds' => null
            ], 500);
        }

        try {
            // Llamada a la YouTube Data API
            $response = Http::get('https://www.googleapis.com/youtube/v3/videos', [
                'id' => $videoId,
                'key' => $apiKey,
                'part' => 'contentDetails,snippet'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if (empty($data['items'])) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Video no encontrado o no es público.',
                        'duration_seconds' => null
                    ], 404);
                }

                $video = $data['items'][0];
                $duration = $video['contentDetails']['duration'] ?? null;
                $title = $video['snippet']['title'] ?? '';
                
                if ($duration) {
                    // Convertir duración ISO 8601 (PT4M13S) a segundos
                    $durationSeconds = $this->convertISO8601ToSeconds($duration);
                    $formattedDuration = $this->formatDuration($durationSeconds);
                    
                    return response()->json([
                        'success' => true,
                        'duration_seconds' => $durationSeconds,
                        'formatted_duration' => $formattedDuration,
                        'title' => $title,
                        'message' => 'Duración obtenida exitosamente'
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'No se pudo obtener la duración del video.',
                        'duration_seconds' => null
                    ], 400);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al conectar con YouTube API.',
                    'duration_seconds' => null
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'duration_seconds' => null
            ], 500);
        }
    }

    /**
     * Convert ISO 8601 duration format to seconds
     * Example: PT4M13S = 253 seconds
     */
    private function convertISO8601ToSeconds($duration)
    {
        $interval = new \DateInterval($duration);
        return ($interval->h * 3600) + ($interval->i * 60) + $interval->s;
    }

    /**
     * Format seconds to human readable duration
     */
    private function formatDuration($seconds)
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = $seconds % 60;

        if ($hours > 0) {
            return sprintf('%d:%02d:%02d', $hours, $minutes, $secs);
        } else {
            return sprintf('%d:%02d', $minutes, $secs);
        }
    }
}