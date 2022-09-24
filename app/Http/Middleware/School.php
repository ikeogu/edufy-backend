<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class School
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $school_slug = $request->route('school_slug');
        $school = \App\Models\School::where('slug', $school_slug)->first();

        if (!$school) {
            return response()->json(['status' => 'failed', 'message' => 'School not found'], 403);
        }

        $user = auth()->user();
        if (!$user->belongs_to_school($school)) {
            return response()->json(['status' => 'failed',
                'message' => 'You are not a member of this school'],
             403);
        }
        return $next($request);
    }
}
