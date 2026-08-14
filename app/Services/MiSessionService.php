<?php

namespace App\Services;

use App\Models\MiSession;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MiSessionService
{

    function __construct()
    {
    }

    /* @description SAVE MI SESSION CON TOKEN */
    public function save($request, $token)
    {
        // SAVE SESSION
        $session = new MiSession();
        $session->user_id = Auth::id();
        $session->ip_address = $request->ip();
        $session->user_agent = $this->getUserAgent($request);
        $session->payload = $token;
        $session->created_at = now();
        $session->save();

        return $session;
    }

    /* @description UPDATE MI SESSION CON TOKEN */
    public function update($request)
    {
        // NOW UPDATE MIS SESSION
        $bearerToken = trim($request->bearerToken());
        DB::table("mis_sessions")
            ->where("payload", $bearerToken)
            ->update([
                "last_activity" => Carbon::now(),
            ]);
    }

    public function getUserAgent($request)
    {
        // GET USER AGENT FOR ANALITICS
        $agent = new \Jenssegers\Agent\Agent();
        $agent->setUserAgent($request->header('User-Agent'));
        $OS = $agent->platform();
        $BROWS = $agent->browser();

        return $OS . " " . $agent->version($OS) . ", " . $BROWS . " " . $agent->version($BROWS);
    }
}
