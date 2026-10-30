<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('assigned_computers')
            ->whereNull('system_unit_id')
            ->whereNull('peripheral_id')
            ->where(function ($query): void {
                $query->where(function ($query): void {
                    $query->whereNotNull('keyboard_id')
                        ->whereNull('mouse_id')
                        ->whereNull('monitor_id')
                        ->whereNull('ups_id');
                })->orWhere(function ($query): void {
                    $query->whereNull('keyboard_id')
                        ->whereNotNull('mouse_id')
                        ->whereNull('monitor_id')
                        ->whereNull('ups_id');
                })->orWhere(function ($query): void {
                    $query->whereNull('keyboard_id')
                        ->whereNull('mouse_id')
                        ->whereNotNull('monitor_id')
                        ->whereNull('ups_id');
                })->orWhere(function ($query): void {
                    $query->whereNull('keyboard_id')
                        ->whereNull('mouse_id')
                        ->whereNull('monitor_id')
                        ->whereNotNull('ups_id');
                });
            })
            ->update(['assignment_type' => 'individual']);
    }

    public function down(): void
    {
        DB::table('assigned_computers')
            ->where('assignment_type', 'individual')
            ->whereNull('system_unit_id')
            ->whereNull('peripheral_id')
            ->where(function ($query): void {
                $query->whereNotNull('keyboard_id')
                    ->orWhereNotNull('mouse_id')
                    ->orWhereNotNull('monitor_id')
                    ->orWhereNotNull('ups_id');
            })
            ->update(['assignment_type' => 'computer_set']);
    }
};
