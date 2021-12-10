<?php


    namespace Modules\Production\Models;


    use App\Models\Tenant\Item;
    use App\Models\Tenant\ModelTenant;
    use App\Models\Tenant\User;
    use Carbon\Carbon;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;

    /**
     * Class Production
     *
     * @property int         $id
     * @property int|null    $user_id
     * @property int|null    $item_id
     * @property float|null  $quantity
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @property Item|null   $item
     * @property User|null   $user
     * @package Modules\Production\Models
     */
    class Production extends ModelTenant
    {
        use UsesTenantConnection;

        protected $table = 'production';
        protected $perPage = 25;

        protected $casts = [
            'user_id' => 'int',
            'item_id' => 'int',
            'quantity' => 'float'
        ];

        protected $fillable = [
            'user_id',
            'item_id',
            'quantity'
        ];

        /**
         * @return BelongsTo
         */
        public function item()
        {
            return $this->belongsTo(Item::class);
        }

        /**
         * @return BelongsTo
         */
        public function user()
        {
            return $this->belongsTo(User::class);
        }
    }
