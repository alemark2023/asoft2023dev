<?php


    namespace Modules\Production\Models;


    use App\Models\Tenant\Item;
    use App\Models\Tenant\ModelTenant;
    use App\Models\Tenant\User;
    use Carbon\Carbon;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Collection;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\BelongsToMany;

    /**
     * Class Mill
     *
     * @property int               $id
     * @property Carbon|null       $date_start
     * @property Carbon|null       $time_start
     * @property Carbon|null       $date_end
     * @property Carbon|null       $time_end
     * @property int|null          $user_id
     * @property Carbon|null       $created_at
     * @property Carbon|null       $updated_at
     * @property User|null         $user
     * @property Collection|Item[] $items
     * @package Modules\Production\Models
     */
    class Mill extends ModelTenant
    {
        use UsesTenantConnection;

        protected $table = 'mill';
        protected $perPage = 25;

        protected $casts = [
            'user_id' => 'int'
        ];

        protected $dates = [
            'date_start',
            'time_start',
            'date_end',
            'time_end'
        ];

        protected $fillable = [
            'date_start',
            'time_start',
            'date_end',
            'time_end',
            'user_id'
        ];

        /**
         * @return BelongsTo
         */
        public function user()
        {
            return $this->belongsTo(User::class);
        }

        /**
         * @return BelongsToMany
         */
        public function items()
        {
            return $this->belongsToMany(Item::class, 'mill_items')
                ->withPivot('id', 'height_to_mill', 'total_height')
                ->withTimestamps();
        }
    }
