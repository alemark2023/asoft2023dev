<?php

namespace App\Models\Tenant;

use App\Notifications\Tenant\PasswordResetNotification;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Modules\LevelAccess\Models\ModuleLevel;
use Modules\Sale\Models\UserCommission;


class User extends Authenticatable
{
    use Notifiable, UsesTenantConnection;

    protected $with = ['establishment'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'establishment_id', 'type', 'locked', 'identity_document_type_id', 'number',
        'address', 'telephone',
         'document_id',
         'series_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
    ];

    protected $casts = [
        'series_id'=> 'int'
    ];

    public function modules()
    {
        return $this->belongsToMany(Module::class);
    }

    public function levels()
    {
        return $this->belongsToMany(ModuleLevel::class);
    }

    public function authorizeModules($modules)
    {
        if ($this->hasAnyModule($modules)) {
            return true;
        }
        abort(401, 'Esta acción no está autorizada.');
    }

    public function hasAnyModule($modules)
    {
        if (is_array($modules)) {
            foreach ($modules as $module)
            {
                if ($this->hasModule($module)) {
                    return true;
                }
            }
        } else {
            if ($this->hasModule($modules)) {
                return true;
            }
        }
        return false;
    }

    public function hasModule($module)
    {
        if ($this->modules()->where('name', $module)->first()) {
            return true;
        }
        return false;
    }



    public function getModule()
    {
        $module = $this->modules()->orderBy('id')->first();
        if ($module) {
            return $module->value;
        }
        return null;
    }

    public function getModules()
    {
        $modules = $this->modules()->get();
        if ($modules) {
            return $modules;
        }
        return null;
    }


    public function searchModule($module)
    {
        if ($this->modules()->where('value', $module)->first()) {
            return true;
        }
        return false;
    }


    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }


    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function sale_notes()
    {
        return $this->hasMany(SaleNote::class);
    }

    public function scopeWhereTypeUser($query)
    {
        $user = auth()->user();
        return ($user->type == 'seller') ? $query->where('id', $user->id) : null;
    }



    public function getLevel()
    {
        $level = $this->levels()->orderBy('id')->first();
        if ($level) {
            return $level->value;
        }
        return null;
    }

    public function getLevels()
    {
        $levels = $this->levels()->get();
        if ($levels) {
            return $levels;
        }
        return null;
    }


    public function searchLevel($Level)
    {
        if ($this->levels()->where('value', $Level)->first()) {
            return true;
        }
        return false;
    }

    public function user_commission()
    {
        return $this->hasOne(UserCommission::class);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordResetNotification($token));
    }

    /**
     * @return mixed
     */
    public function getDocumentId() {
        return $this->document_id;
    }

    /**
     * @param mixed $document_id
     *
     * @return User
     */
    public function setDocumentId($document_id) {
        $this->document_id = $document_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSeriesId() {
        return $this->series_id;
    }

    /**
     * @param mixed $series_id
     *
     * @return User
     */
    public function setSeriesId($series_id) {
        $this->series_id = $series_id;
        return $this;
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function global_payments_relations() {
        return $this->hasOne(GlobalPaymentsRelations::class, 'notes_id');
    }
}
