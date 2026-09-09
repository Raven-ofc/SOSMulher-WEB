<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;

class AdminAudit
{
    public static function record(string $entity, int $id, string $action): void
    {
        DB::table('administration_audit')->insert([
            'user_id' => auth()->id(), 'entity' => $entity, 'entity_id' => $id,
            'action' => $action, 'created_at' => now(),
        ]);
    }
}
