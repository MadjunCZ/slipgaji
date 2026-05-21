<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    use HasFactory;

    protected $table = 'api_logs';

    protected $fillable = [
        'endpoint',
        'method',
        'params',
        'user_id',
        'ip_address',
        'status_code',
        'response_data',
        'execution_time_ms',
        'error_message',
    ];

    protected $casts = [
        'status_code' => 'integer',
        'execution_time_ms' => 'float',
        'params' => 'array',
        'response_data' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get user yang melakukan request
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope untuk endpoint tertentu
     */
    public function scopeByEndpoint($query, string $endpoint)
    {
        return $query->where('endpoint', 'like', '%' . $endpoint . '%');
    }

    /**
     * Scope untuk method tertentu
     */
    public function scopeByMethod($query, string $method)
    {
        return $query->where('method', strtoupper($method));
    }

    /**
     * Scope untuk status code tertentu
     */
    public function scopeByStatusCode($query, int $statusCode)
    {
        return $query->where('status_code', $statusCode);
    }

    /**
     * Scope untuk request yang error
     */
    public function scopeError($query)
    {
        return $query->where('status_code', '>=', 400);
    }

    /**
     * Scope untuk request sukses
     */
    public function scopeSuccess($query)
    {
        return $query->where('status_code', '>=', 200)->where('status_code', '<', 300);
    }

    /**
     * Check jika request sukses
     */
    public function isSuccess(): bool
    {
        return $this->status_code >= 200 && $this->status_code < 300;
    }

    /**
     * Check jika request error
     */
    public function isError(): bool
    {
        return $this->status_code >= 400;
    }
}
