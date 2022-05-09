<?php

namespace Dmpty\LaravelUtilities\Common\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @method Model increment($column, $amount = 1, array $extra = [])
 * @method Model decrement($column, $amount = 1, array $extra = [])
 */
class BaseModel extends Model
{
    protected $guarded = [];

    public function getCreatedAtAttribute(): string
    {
        return (new Carbon($this->attributes['created_at']))->toDateTimeString();
    }

    public function getUpdatedAtAttribute(): string
    {
        return (new Carbon($this->attributes['updated_at']))->toDateTimeString();
    }
}
