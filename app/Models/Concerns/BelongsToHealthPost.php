<?php

namespace App\Models\Concerns;

use App\Models\HealthPost;
use App\Models\Scopes\HealthPostScope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

trait BelongsToHealthPost
{
    public static function bootBelongsToHealthPost(): void
    {
        static::addGlobalScope(new HealthPostScope);

        static::creating(function ($model) {
            if (empty($model->health_post_id) && Auth::check() && Auth::user()->staff) {
                $model->health_post_id = Auth::user()->staff->health_post_id;
            }
        });
    }

    public function healthPost(): BelongsTo
    {
        return $this->belongsTo(HealthPost::class);
    }
}