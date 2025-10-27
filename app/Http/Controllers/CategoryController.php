<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * API: Fetch all categories
     * Public endpoint - no authentication required
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiIndex()
    {
        try {
            $categories = Category::orderBy('name', 'asc')->get();
            
            return response()->json([
                'success' => true,
                'data' => $categories,
                'count' => $categories->count(),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch categories',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
