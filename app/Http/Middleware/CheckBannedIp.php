<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\BannedIp;

class CheckBannedIp
{
    public function handle(Request $request, Closure $next)
    {
        $ipAddress = $request->ip();

        $bannedIp = BannedIp::where('ip_address', $ipAddress)->first();

        if ($bannedIp) {
            return response()->json(['message' => 'You were banned on ' . $bannedIp->blocked_at], 403);
        }

        return $next($request);
    }
}
