<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute; // Import Attribute
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt; // Import Crypt facade

/**
 * @property int $id
 * @property string $name
 * @property string $api_url
 * @property string $api_key
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiConnection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiConnection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiConnection query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiConnection whereApiKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiConnection whereApiUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiConnection whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiConnection whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiConnection whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiConnection whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiConnection whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class ApiConnection extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'api_url',
        'api_key',
        'is_active',
    ];

    /**
     * Interact with the api_key attribute.
     *
     * This will automatically encrypt the key when setting it,
     * and decrypt it when getting it.
     */
    protected function apiKey(): Attribute
    {
        return Attribute::make(
            // Getter: Decrypt the value when you access it
            get: fn (string $value) => Crypt::decryptString($value),

            // Setter: Encrypt the value before saving to the database
            set: fn (string $value) => Crypt::encryptString($value),
        );
    }
}
