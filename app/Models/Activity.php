<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_role',
        'action_type',
        'entity_type',
        'entity_id',
        'entity_name',
        'old_values',
        'new_values',
        'description',
        'metadata',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'metadata' => 'array',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper methods for logging activities
    public static function log($user, string $actionType, string $entityType, $entityId = null, string $entityName = null, array $oldValues = null, array $newValues = null, string $description = null, array $metadata = null)
    {
        if (!$user) return null;

        return static::create([
            'user_id' => $user->id,
            'user_role' => $user->role ?? 'unknown',
            'action_type' => $actionType,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'entity_name' => $entityName,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'description' => $description ?? self::generateDescription($actionType, $entityType, $entityName),
            'metadata' => $metadata,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    private static function generateDescription(string $actionType, string $entityType, string $entityName = null): string
    {
        $entity = $entityName ?: $entityType;
        $action = self::getActionVerb($actionType);

        return ucfirst($action) . ' ' . $entity;
    }

    private static function getActionVerb(string $actionType): string
    {
        return match($actionType) {
            'create' => 'created',
            'update' => 'updated',
            'delete' => 'deleted',
            'approve' => 'approved',
            'reject' => 'rejected',
            'publish' => 'published',
            'unpublish' => 'unpublished',
            'enroll' => 'enrolled in',
            'complete' => 'completed',
            'cancel' => 'cancelled',
            'restore' => 'restored',
            'login' => 'logged into',
            'logout' => 'logged out of',
            'reset_password' => 'reset password for',
            'change_password' => 'changed password for',
            'update_profile' => 'updated profile for',
            'send_message' => 'sent message to',
            'post_review' => 'posted review for',
            'book_session' => 'booked session with',
            'process_payout' => 'processed payout for',
            'generate_report' => 'generated report for',
            default => $actionType . 'd',
        };
    }

    // Scopes for filtering
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('user_role', $role);
    }

    public function scopeByAction($query, $actionType)
    {
        return $query->where('action_type', $actionType);
    }

    public function scopeByEntity($query, $entityType, $entityId = null)
    {
        $query->where('entity_type', $entityType);
        if ($entityId) {
            $query->where('entity_id', $entityId);
        }
        return $query;
    }

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}
