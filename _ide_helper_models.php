<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string|null $log_name
 * @property string $description
 * @property string|null $subject_type
 * @property int|null $subject_id
 * @property string|null $causer_type
 * @property int|null $causer_id
 * @property \Illuminate\Support\Collection<array-key, mixed>|null $properties
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $event
 * @property string|null $batch_uuid
 * @property-read \Illuminate\Database\Eloquent\Model|null $causer
 * @property-read \Illuminate\Support\Collection $changes
 * @property-read \Illuminate\Database\Eloquent\Model|null $subject
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog causedBy(\Illuminate\Database\Eloquent\Model $causer)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog forBatch(string $batchUuid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog forEvent(string $event)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog forSubject(\Illuminate\Database\Eloquent\Model $subject)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog hasBatch()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog inLog(...$logNames)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereBatchUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereCauserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereCauserType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereEvent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereLogName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereProperties($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereSubjectType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereUpdatedAt($value)
 */
	class ActivityLog extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $api_url
 * @property string $api_key
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
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
 * @mixin \Eloquent
 */
	class ApiConnection extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $onedg_campaign_config_id
 * @property string $channel_link
 * @property \Illuminate\Support\Carbon|null $expires_at
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\OneDgCampaignConfig $campaignConfig
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
 */
	class OneDgCampaign extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property array<array-key, mixed> $services
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OneDgCampaign> $campaigns
 * @property-read int|null $campaigns_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneDgCampaignConfig newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneDgCampaignConfig newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneDgCampaignConfig query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneDgCampaignConfig whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneDgCampaignConfig whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneDgCampaignConfig whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneDgCampaignConfig whereServices($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneDgCampaignConfig whereUpdatedAt($value)
 */
	class OneDgCampaignConfig extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 * @mixin \Eloquent
 */
	class User extends \Eloquent {}
}

