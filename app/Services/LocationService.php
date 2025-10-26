<?php

namespace App\Services;

class LocationService
{
    /**
     * Calculate distance between two points using Haversine formula
     * Returns distance in kilometers
     */
    public static function calculateDistance($lat1, $lng1, $lat2, $lng2)
    {
        // Check if coordinates are valid
        if (!$lat1 || !$lng1 || !$lat2 || !$lng2) {
            return null;
        }

        // Convert to float and validate ranges
        $lat1 = (float) $lat1;
        $lng1 = (float) $lng1;
        $lat2 = (float) $lat2;
        $lng2 = (float) $lng2;

        // Validate coordinate ranges
        if ($lat1 < -90 || $lat1 > 90 || $lng1 < -180 || $lng1 > 180 ||
            $lat2 < -90 || $lat2 > 90 || $lng2 < -180 || $lng2 > 180) {
            return null;
        }

        // Check if coordinates are not zero (invalid)
        if ($lat1 == 0 && $lng1 == 0) return null;
        if ($lat2 == 0 && $lng2 == 0) return null;

        $earthRadius = 6371; // Earth's radius in kilometers

        $lat1Rad = deg2rad($lat1);
        $lng1Rad = deg2rad($lng1);
        $lat2Rad = deg2rad($lat2);
        $lng2Rad = deg2rad($lng2);

        $deltaLat = $lat2Rad - $lat1Rad;
        $deltaLng = $lng2Rad - $lng1Rad;

        $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
             cos($lat1Rad) * cos($lat2Rad) *
             sin($deltaLng / 2) * sin($deltaLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Get distance category and color for a job
     */
    public static function getDistanceInfo($userLat, $userLng, $jobLat, $jobLng)
    {
        // Check if user has location
        if (!$userLat || !$userLng) {
            return [
                'distance' => null,
                'category' => 'no_user_location',
                'color' => '#f59e0b', // orange
                'bg_color' => '#fef3c7',
                'text_color' => '#92400e',
                'label' => 'Weka eneo lako'
            ];
        }

        // Check if job has location
        if (!$jobLat || !$jobLng) {
            return [
                'distance' => null,
                'category' => 'no_job_location',
                'color' => '#ef4444', // red
                'bg_color' => '#fecaca',
                'text_color' => '#dc2626',
                'label' => 'Eneo haujulikani'
            ];
        }

        $distance = self::calculateDistance($userLat, $userLng, $jobLat, $jobLng);
        
        if ($distance === null) {
            return [
                'distance' => null,
                'category' => 'unknown',
                'color' => '#6b7280', // gray
                'bg_color' => '#f3f4f6',
                'text_color' => '#6b7280',
                'label' => 'Umbali haujulikani'
            ];
        }

        if ($distance <= 5) {
            return [
                'distance' => round($distance, 1),
                'category' => 'near',
                'color' => '#10b981', // green
                'bg_color' => '#d1fae5',
                'text_color' => '#065f46',
                'label' => 'Karibu sana'
            ];
        } elseif ($distance <= 10) {
            return [
                'distance' => round($distance, 1),
                'category' => 'moderate',
                'color' => '#f59e0b', // orange
                'bg_color' => '#fef3c7',
                'text_color' => '#92400e',
                'label' => 'Umbali wa wastani'
            ];
        } else {
            return [
                'distance' => round($distance, 1),
                'category' => 'far',
                'color' => '#ef4444', // red
                'bg_color' => '#fecaca',
                'text_color' => '#dc2626',
                'label' => 'Umbali mkubwa'
            ];
        }
    }

    /**
     * Format distance for display
     */
    public static function formatDistance($distance)
    {
        if ($distance === null) {
            return 'N/A';
        }

        if ($distance < 1) {
            return round($distance * 1000) . 'm';
        }

        return round($distance, 1) . 'km';
    }
}
