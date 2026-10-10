<?php

namespace App\Support;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminAudit
{
    public static function record(string $entity, int $id, string $action): void
    {
        DB::table('tbauditoria')->insert([
            'user_id' => Auth::id(),
            'entity' => $entity,
            'entity_id' => $id,
            'action' => $action,
            'created_at' => now(),
        ]);
    }
}