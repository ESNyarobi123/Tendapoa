<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Services\LocationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedController extends Controller
{
    public function index(Request $r)
    {
        $role = Auth::user()->role ?? null;
        if (!in_array($role, ['mfanyakazi','admin'], true)) {
            abort(403, 'Huna ruhusa (mfanyakazi/admin tu).');
        }

        $cat = $r->query('category');
        $distance = $r->query('distance');
        $user = Auth::user();

        $jobs = Job::with('category','muhitaji')
            ->where('status','posted')
            ->when($cat, fn($q)=>$q->whereHas('category', fn($qq)=>$qq->where('slug',$cat)))
            ->latest()->paginate(12);

        // Calculate distances for each job (only if user has location)
        if ($user->hasLocation()) {
            $jobs->getCollection()->transform(function ($job) use ($user) {
                $distanceInfo = LocationService::getDistanceInfo(
                    $user->lat, 
                    $user->lng, 
                    $job->lat, 
                    $job->lng
                );
                
                $job->distance_info = $distanceInfo;
                return $job;
            });
        } else {
            // If user doesn't have location, set default distance info
            $jobs->getCollection()->transform(function ($job) {
                $job->distance_info = [
                    'distance' => null,
                    'category' => 'unknown',
                    'color' => '#6b7280',
                    'bg_color' => '#f3f4f6',
                    'text_color' => '#6b7280',
                    'label' => 'Umbali haujulikani'
                ];
                return $job;
            });
        }

        // Filter by distance if specified
        if ($distance) {
            $maxDistance = (float) $distance;
            $filteredJobs = $jobs->getCollection()->filter(function ($job) use ($maxDistance) {
                return $job->distance_info['distance'] !== null && $job->distance_info['distance'] <= $maxDistance;
            });
            $jobs->setCollection($filteredJobs);
        }

        // Smart sorting: prioritize jobs with valid distances, then by distance
        $sortedJobs = $jobs->getCollection()->sortBy(function ($job) {
            $distance = $job->distance_info['distance'] ?? null;
            $category = $job->distance_info['category'] ?? 'unknown';
            
            // Priority order: near jobs first, then by distance, then unknown jobs last
            if ($category === 'near') return 0;
            if ($category === 'moderate') return 1;
            if ($category === 'far') return 2;
            if ($category === 'no_user_location') return 3;
            if ($category === 'no_job_location') return 4;
            if ($category === 'unknown') return 5;
            
            return $distance ?? 999;
        });

        $jobs->setCollection($sortedJobs);

        return view('feed.index', compact('jobs','cat'));
    }

    public function apiIndex(Request $r)
    {
        $role = Auth::user()->role ?? null;
        if (!in_array($role, ['mfanyakazi','admin'], true)) {
            return response()->json([
                'error' => 'Huna ruhusa (mfanyakazi/admin tu).',
                'status' => 'forbidden'
            ], 403);
        }

        $cat = $r->query('category');
        $distance = $r->query('distance');
        $user = Auth::user();

        $jobs = Job::with('category','muhitaji')
            ->where('status','posted')
            ->when($cat, fn($q)=>$q->whereHas('category', fn($qq)=>$qq->where('slug',$cat)))
            ->latest()->paginate(12);

        // Calculate distances for each job (only if user has location)
        if ($user->hasLocation()) {
            $jobs->getCollection()->transform(function ($job) use ($user) {
                $distanceInfo = LocationService::getDistanceInfo(
                    $user->lat, 
                    $user->lng, 
                    $job->lat, 
                    $job->lng
                );
                
                $job->distance_info = $distanceInfo;
                return $job;
            });
        } else {
            // If user doesn't have location, set default distance info
            $jobs->getCollection()->transform(function ($job) {
                $job->distance_info = [
                    'distance' => null,
                    'category' => 'unknown',
                    'color' => '#6b7280',
                    'bg_color' => '#f3f4f6',
                    'text_color' => '#6b7280',
                    'label' => 'Umbali haujulikani'
                ];
                return $job;
            });
        }

        // Filter by distance if specified
        if ($distance) {
            $maxDistance = (float) $distance;
            $filteredJobs = $jobs->getCollection()->filter(function ($job) use ($maxDistance) {
                return $job->distance_info['distance'] !== null && $job->distance_info['distance'] <= $maxDistance;
            });
            $jobs->setCollection($filteredJobs);
        }

        // Smart sorting: prioritize jobs with valid distances, then by distance
        $sortedJobs = $jobs->getCollection()->sortBy(function ($job) {
            $distance = $job->distance_info['distance'] ?? null;
            $category = $job->distance_info['category'] ?? 'unknown';
            
            // Priority order: near jobs first, then by distance, then unknown jobs last
            if ($category === 'near') return 0;
            if ($category === 'moderate') return 1;
            if ($category === 'far') return 2;
            if ($category === 'no_user_location') return 3;
            if ($category === 'no_job_location') return 4;
            if ($category === 'unknown') return 5;
            
            return $distance ?? 999;
        });

        $jobs->setCollection($sortedJobs);

        return response()->json([
            'jobs' => $jobs->items(),
            'pagination' => [
                'current_page' => $jobs->currentPage(),
                'last_page' => $jobs->lastPage(),
                'per_page' => $jobs->perPage(),
                'total' => $jobs->total(),
                'has_more' => $jobs->hasMorePages()
            ],
            'filters' => [
                'category' => $cat,
                'distance' => $distance
            ],
            'user_location' => $user->hasLocation() ? [
                'lat' => $user->lat,
                'lng' => $user->lng
            ] : null,
            'status' => 'success'
        ]);
    }

    public function apiMap(Request $r)
    {
        $role = Auth::user()->role ?? null;
        if (!in_array($role, ['mfanyakazi','admin'], true)) {
            return response()->json([
                'error' => 'Huna ruhusa (mfanyakazi/admin tu).',
                'status' => 'forbidden'
            ], 403);
        }

        $user = Auth::user();
        $jobs = Job::with('category','muhitaji')
            ->where('status','posted')
            ->whereNotNull('lat')
            ->whereNotNull('lng')
            ->where('lat', '!=', 0)
            ->where('lng', '!=', 0)
            ->get();

        // Calculate distances for each job
        if ($user->hasLocation()) {
            $jobs->transform(function ($job) use ($user) {
                $distanceInfo = LocationService::getDistanceInfo(
                    $user->lat, 
                    $user->lng, 
                    $job->lat, 
                    $job->lng
                );
                
                $job->distance_info = $distanceInfo;
                return $job;
            });
        } else {
            $jobs->transform(function ($job) {
                $job->distance_info = [
                    'distance' => null,
                    'category' => 'unknown',
                    'color' => '#6b7280',
                    'bg_color' => '#f3f4f6',
                    'text_color' => '#6b7280',
                    'label' => 'Umbali haujulikani'
                ];
                return $job;
            });
        }

        return response()->json([
            'jobs' => $jobs,
            'user_location' => $user->hasLocation() ? [
                'lat' => $user->lat,
                'lng' => $user->lng
            ] : null,
            'total_jobs' => $jobs->count(),
            'status' => 'success'
        ]);
    }
}
