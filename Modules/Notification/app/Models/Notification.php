<?php

namespace Modules\Notification\Models;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Modules\Base\Models\BaseModel;
use Yajra\DataTables\Facades\DataTables;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Admin\Models\Admin;
use Modules\Auth\Models\User;
use Modules\Notification\Enums\NotificationChannels;
use Modules\Notification\Enums\NotificationStatuses;
use Modules\Notification\Enums\permissions\NotificationPermissions;

class Notification extends BaseModel
{
    use Translatable, SoftDeletes, HasFactory;

    // Start Properties

    const VIEW_PATH = 'notifications';

    protected $fillable = [
        'notifiable_id',
        'notifiable_type',
        'added_by',
        'topic',
        'link',
    ];

    public $timestamps = true;

    public $translatedAttributes = [
        'title',
        'body',
        'long_template'
    ];

    protected $with = [
        'translations',
        'notificationChannels'
    ];

    protected $appends = [
        'channels',
        'created_at_format',
    ];

    // End Properties

    // Start Relationships
    public function notifiable()
    {
        return $this->morphTo();
    }

    public function notificationChannels()
    {
        return $this->hasMany(NotificationChannel::class, 'notification_id');
    }
    // End Relationships

    // Start Scopes
    public function scopeSimpleSearch($query, $search)
    {
        return
        $query->where(function($query) use($search) {
            $query->where('id', $search)
            ->orWhereTranslationLike('title', '%' . $search . '%');
        });
    }

    public function scopeAdvancedSearch($query, $search)
    {
        return $query->when(!empty($search['channels']), function ($q) use ($search) {
            $q->whereHas('notificationChannels', function ($q2) use ($search) {
                $q2->where(function ($q3) use ($search) {
                    match ($search['channels']) {
                        NotificationChannels::FCM_MOBILE => $q3->where('is_fcm_mobile', true),
                        NotificationChannels::FCM_WEB    => $q3->where('is_fcm_web', true),
                        NotificationChannels::MAIL       => $q3->where('is_email', true),
                        NotificationChannels::SMS        => $q3->where('is_sms', true),
                        default                          => null,
                    };
                });
            });
        })
        ->when(!empty($search['added_by']), fn ($q) => $q->where('added_by', $search['added_by']))
        ->when(!empty($search['group']), function ($q) use ($search) {
            $type = $search['group'] === 'users' ? User::class : Admin::class;
            $q->where('notifiable_type', $type);
        })
        ->when(!empty($search['user_id']), fn ($q) => $q->where('notifiable_id', $search['user_id']))
        ->when(!empty($search['admin_id']), fn ($q) => $q->where('notifiable_id', $search['admin_id']));
    }


    public function scopeSuccessfully($query)
    {
        return $query->whereHas('notificationChannels', fn($q) =>  $q->successfully());
    }
    // End Scopes

    // Start Get Data From Model

    public function formAjaxArray($selected = true)
    {
        return [
            'id'            => $this->id,
            'web_channel_id'=> $this->notificationChannels->where('is_fcm_web', true)->first()?->id,
            'title'         => $this->smartTrans('title'),
            'body'          => $this->smartTrans('body'),
            'sent_at'       => $this->created_at_format,
            'is_read'       => $this->notificationChannels->where('is_fcm_web', true)->first()?->status == NotificationStatuses::READ,
            'icon'          =>  getSetting('app_favicon', asset('images/default/logos/app_logo.svg')),
            'link'          => $this->link,
            'selected'      => $selected
        ];
    }

    public function getModel(int $id, bool $withTrashed = false, bool $withDisabled = false) : Notification
    {
        $model = $this::query();

        if($withTrashed && $withDisabled) {
            return $model->withTrashed()->withDisabled()->findOrFail($id);
        } elseif($withTrashed) {
            return $model->withTrashed()->findOrFail($id);
        } elseif($withDisabled) {
            return $model->withDisabled()->findOrFail($id);
        }

        return $model->findOrFail($id);
    }

    public function getDataTable(array $data) : JsonResponse
    {
        $model = $this::with('notifiable');

        if($this->shouldShowTrash($data, NotificationPermissions::VIEW_TRASH)) {
            $model = $model->onlyTrashed();
        }

        return DataTables::of($model)
            ->filter(function ($query) use ($data) {
                if(isset($data['search']['value']) && !empty($data['search']['value'])){
                    $query->simpleSearch($data['search']['value']);
                }
                if(isset($data['advanced_search']) && !empty($data['advanced_search'])){
                    $query->advancedSearch($data['advanced_search']);
                }
            })
            ->toJson();
    }

    // End Get Data From Model

    // Start Mutators & Accessors
    protected function channels(): Attribute
    {
        return Attribute::make(
            get: function($value, $attributes) {
                return $this->notificationChannels?->map(function($channel) {
                    $type = '';

                    if($channel->is_fcm_mobile) {
                        $type .= ' FCM Mobile, ';
                    }
                    if($channel->is_fcm_web) {
                        $type .= ' FCM Web, ';
                    }
                    if($channel->is_email) {
                        $type .= ' Email, ';
                    }
                    if($channel->is_sms) {
                        $type .= ' SMS, ';
                    }
                    if($channel->is_database) {
                        $type .= ' Database, ';
                    }

                    return rtrim($type, ', ');
                });
            }
        );
    }

    protected function createdAtFormat(): Attribute
    {
        return Attribute::make(
            get: function($value, $attributes) {
                return  Carbon::parse($attributes['created_at'])->diffForHumans();
            }
        );
    }

    // End Mutators & Accessors
}
