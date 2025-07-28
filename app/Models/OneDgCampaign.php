<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $onedg_campaign_config_id
 * @property string $channel_link
 * @property \Illuminate\Support\Carbon|null $expires_at
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\OneDgCampaignConfig $campaignConfig
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneDgCampaign newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneDgCampaign newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneDgCampaign query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneDgCampaign whereChannelLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneDgCampaign whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneDgCampaign whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneDgCampaign whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneDgCampaign whereOnedgCampaignConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneDgCampaign whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneDgCampaign whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class OneDgCampaign extends Model
{
    use HasFactory;

    protected $table = 'onedg_campaigns'; // Specify the table name

    protected $fillable = [
        'onedg_campaign_config_id',
        'channel_link',
        'expires_at',
        'status',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function campaignConfig()
    {
        // Updated relationship
        return $this->belongsTo(OneDgCampaignConfig::class, 'onedg_campaign_config_id');
    }
}
