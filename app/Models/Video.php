<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'topic_id',
        'url',
        'name',
        'code',
        'length_seconds',
    ];

    protected $casts = [
        'length_seconds' => 'integer',
    ];

    /**
     * Get the topic that owns the video.
     */
    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    /**
     * Get the YouTube video ID from the URL.
     */
    public function getYoutubeIdAttribute(): ?string
    {
        if (preg_match('/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $this->url, $matches)) {
            return $matches[1];
        }
        return null;
    }

    /**
     * Get the embed URL for YouTube.
     */
    public function getEmbedUrlAttribute(): ?string
    {
        $youtubeId = $this->youtube_id;
        return $youtubeId ? "https://www.youtube.com/embed/{$youtubeId}" : null;
    }

    /**
     * Get the thumbnail URL for YouTube.
     */
    public function getThumbnailUrlAttribute(): ?string
    {
        $youtubeId = $this->youtube_id;
        return $youtubeId ? "https://img.youtube.com/vi/{$youtubeId}/maxresdefault.jpg" : null;
    }

    /**
     * Get formatted duration (HH:MM:SS).
     */
    public function getFormattedDurationAttribute(): string
    {
        if (!$this->length_seconds) {
            return '00:00';
        }

        $hours = floor($this->length_seconds / 3600);
        $minutes = floor(($this->length_seconds % 3600) / 60);
        $seconds = $this->length_seconds % 60;

        if ($hours > 0) {
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        }

        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    /**
     * Scope a query to only include videos with valid URLs.
     */
    public function scopeWithValidUrl($query)
    {
        return $query->whereNotNull('url')->where('url', '!=', '');
    }
}
