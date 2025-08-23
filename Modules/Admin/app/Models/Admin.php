<?php

namespace Modules\Admin\Models;

use Illuminate\Http\JsonResponse;
use Spatie\MediaLibrary\HasMedia;
use Modules\Base\Enums\Gender;
use Illuminate\Foundation\Auth\User;
use Modules\Admin\Traits\UserTrait;
use Modules\Base\Trait\Disableable;
use Modules\Base\Trait\ModelHelper;
use Modules\Permission\Models\Role;
use Illuminate\Notifications\Notifiable;
use Yajra\DataTables\Facades\DataTables;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Admin\database\seeders\AdminSeeder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Silber\Bouncer\Database\HasRolesAndAbilities;
use Modules\Permission\Enums\SystemDefaultRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Admin\Enums\permissions\AdminPermissions;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Admin extends User implements HasMedia, Auditable
{
    use UserTrait, SoftDeletes, Disableable, ModelHelper, HasFactory, Notifiable, HasRolesAndAbilities, InteractsWithMedia, AuditableTrait;

    // Start Properties

    const VIEW_PATH = 'admins';

    protected $fillable = [
        'status',
        'full_name',
        'username',
        'phone_number',
        'email',
        'password',
        'lang',
        'last_login_at',
        'gender',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = [
        'avatar_url',
        'email_format',
        'role_name',
        'fcm_topic',
        'un_read_web_notifications_count',
        'delivered_web_notifications_count',
        'status_format',
        'last_login_format',
        'created_at_format',
    ];

    /**
     * Attributes to exclude from the Audit.
     *
     * @var array
     */
    protected $auditExclude = [
        'remember_token',
        'last_login_at',
        'ip_address',
    ];

    public const MEDIA_COLLECTION = 'admin_avatar';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Modules\Admin\database\factories\AdminFactory::new();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaCollection(self::MEDIA_COLLECTION);
    }

    // End Properties

    // Start Relationships

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'assigned_roles', 'entity_id', 'role_id')->withoutGlobalScope('withoutRoot')->withPivot('scope');
    }

    // End Relationships

    // Start Scopes
    public function scopeSimpleSearch($query, $search)
    {
        return
        $query->whereAny(
            ['id', 'full_name', 'username', 'phone_number', 'email'],
            'LIKE',
            '%' . $search . '%'
        );
    }

    public function scopeAdvancedSearch($query, $search)
    {
        return $query
            ->when(!empty($search['status']), fn($q) => $q->where('status', $search['status']))
            ->when(!empty($search['role'])  , fn($q) => $q->whereRelation('roles', 'role_id', $search['role']))
            ->when(!empty($search['gender']), fn($q) => $q->where('gender', $search['gender']));
    }

    public function scopeExceptRoot($query)
    {
        return $query->where('username', '!=', AdminSeeder::$systemAdmins[SystemDefaultRoles::ROOT_ROLE]['username']);
    }

    public function scopeExceptCurrentAdmin($query)
    {
        return $query->where('id', '!=', app('admin')->id);
    }
    // End Scopes

    // Start Get Data From Model

    public function formAjaxArray($selected = true)
    {
        return [
            'id'            => $this->id,
            'text'          => $this->email,
            'selected'      => $selected
        ];
    }

    public function getModel(int $id, bool $withTrashed = false, bool $withDisabled = false) : Admin
    {
        $model = $this::query()->exceptRoot()->exceptCurrentAdmin();

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
        $model = $this::with('roles.translations')->exceptRoot()->exceptCurrentAdmin()->withDisabled();

        if($this->shouldShowTrash($data, AdminPermissions::VIEW_TRASH)) {
            $model = $model->onlyTrashed();
        }

        $canLoginToAnotherAccount   = app('owner') || app('admin')->can(AdminPermissions::LOGIN_TO_ANOTHER_ACCOUNT);
        $additionalActions          = [];

        return DataTables::of($model)
            ->filter(function ($query) use ($data) {
                if(isset($data['search']['value']) && !empty($data['search']['value'])){
                    $query->simpleSearch($data['search']['value']);
                }
                if(isset($data['advanced_search']) && !empty($data['advanced_search'])){
                    $query->advancedSearch($data['advanced_search']);
                }
            })
            ->addColumn('actions', function ($model) use($canLoginToAnotherAccount, $additionalActions){
                $excludeActions = [VIEW_ACTION];

                if($canLoginToAnotherAccount) {
                    $additionalActions[] = app('customDataTable')->addAction('login_to_another_account', 'fas fa-sign-in-alt', 99, $model->id, color: '#f1416c', route: route('admin.profile.loginToAnotherAccount', ['model' => $model->id]));
                }

                return
                    app('customDataTable')
                    ->routePrefix('admin.admins')
                    ->of($model, AdminPermissions::PERMISSION_NAMESPACE)
                    ->excludeActions($excludeActions)
                    ->getDatatableActions(additionalActions: $additionalActions, withMainCrudActions: true);
            })
            ->toJson();
    }

    // End Get Data From Model

    // Start Mutators & Accessors

    public function isRoot()
    {
        return $this->isA(SystemDefaultRoles::ROOT_ROLE);
    }

    public function isSystemAdmin()
    {
        return $this->isA(SystemDefaultRoles::SYSTEM_ADMIN_ROLE);
    }

    protected function avatarUrl(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->getFirstMedia(self::MEDIA_COLLECTION)?->getUrl() ?? asset('images/default/avatars/' . $attributes['gender'] == Gender::FEMALE ? asset('images/default/avatars/ms_admin.png') : asset('images/default/avatars/mr_admin.png')),
        );
    }

    protected function roleName(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => !empty($role = $this->roles()->first()) ? $role->smartTrans('title') : '----',
        );
    }
    // End Mutators & Accessors
}
