<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AdminLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'admin_name',
        'action',
        'entity_type',
        'entity_id',
        'entity_name',
        'summary',
        'changes',
        'location',
        'changed_at',
    ];

    protected $casts = [
        'changes' => 'array',
        'changed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function changeSummary(): string
    {
        return $this->summary ?: 'Perubahan data oleh admin';
    }

    public function entityLabel(): string
    {
        $labels = [
            'destinasi' => 'Destinasi Wisata',
            'kuliner' => 'Kuliner',
            'penginapan' => 'Penginapan',
            'user' => 'Pengguna',
            'appeal' => 'Banding Akun',
            'penyedia_travel' => 'Penyedia Travel',
            'escrow' => 'Escrow / Payout',
            'komentar' => 'Komentar',
        ];

        return $labels[$this->entity_type] ?? ucfirst($this->entity_type ?? 'Data');
    }

    public function actionLabel(): string
    {
        $labels = [
            'create' => 'Tambah',
            'update' => 'Edit',
            'delete' => 'Hapus',
            'approve' => 'Setujui',
            'reject' => 'Tolak',
            'verify' => 'Verifikasi',
            'warning' => 'Teguran',
        ];

        return $labels[$this->action] ?? ucfirst($this->action ?? 'Aksi');
    }

    public function changedFieldLabels(): array
    {
        if (is_array($this->changes)) {
            return $this->changes;
        }

        return [];
    }

    public function subjectLocation(): ?string
    {
        return $this->location;
    }

    /**
     * Helper static method untuk mencatat aktivitas admin secara mudah.
     */
    public static function record(
        string $action,
        string $entityType,
        ?int $entityId,
        ?string $entityName,
        ?string $summary = null,
        array $changes = [],
        ?string $location = null
    ): ?self {
        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('admin_logs')) {
                return null;
            }

            $admin = Auth::user();

            return self::create([
                'user_id' => $admin?->id,
                'admin_name' => $admin ? ($admin->name ?: $admin->username) : 'Sistem Admin',
                'action' => $action,
                'entity_type' => $entityType,
                'entity_id' => $entityId,
                'entity_name' => $entityName,
                'summary' => $summary ?: ucfirst($action) . ' ' . ($entityName ?: $entityType),
                'changes' => $changes,
                'location' => $location,
                'changed_at' => now(),
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Failed to record admin log: ' . $e->getMessage());
            return null;
        }
    }
}
