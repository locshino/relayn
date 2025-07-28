<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property array<array-key, mixed> $services
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OneDgCampaign> $campaigns
 * @property-read int|null $campaigns_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneDgCampaignConfig newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneDgCampaignConfig newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneDgCampaignConfig query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneDgCampaignConfig whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneDgCampaignConfig whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneDgCampaignConfig whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneDgCampaignConfig whereServices($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneDgCampaignConfig whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class OneDgCampaignConfig extends Model
{
    use HasFactory;

    protected $table = 'onedg_campaign_configs'; // Specify the table name

    protected $fillable = ['name', 'services'];

    protected $casts = [
        'services' => 'array',
    ];

    public function campaigns()
    {
        // Updated relationship
        return $this->hasMany(OneDgCampaign::class, 'onedg_campaign_config_id');
    }
}
