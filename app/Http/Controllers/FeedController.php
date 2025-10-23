<?php

namespace App\Http\Controllers;

use App\Models\Job;
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

        $jobs = Job::with('category','muhitaji')
            ->where('status','posted')
            ->when($cat, fn($q)=>$q->whereHas('category', fn($qq)=>$qq->where('slug',$cat)))
            ->latest()->paginate(12);

        return view('feed.index', compact('jobs','cat'));
    }
}
