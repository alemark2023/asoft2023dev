<?php

    /**
     */

    namespace Modules\Suscription\Models\Tenant;


    use App\Models\Tenant\Item;
    use App\Models\Tenant\ModelTenant;
    use App\Models\Tenant\User;
    use Carbon\Carbon;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Collection;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\BelongsToMany;

    /**
 * Class SuscriptionPlan
 *
 * @property int               $id
 * @property int|null          $cat_period_id
 * @property string            $name
 * @property string            $description
 * @property float|null        $total
 * @property Carbon|null       $created_at
 * @property Carbon|null       $updated_at
 * @property CatPeriod|null    $cat_period
 * @property Collection|Item[] $items
 * @property Collection|User[] $users
 * @package App\Models\Tenant\ModelTenant
 * @property-read int|null $items_count
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|SuscriptionPlan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SuscriptionPlan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SuscriptionPlan query()
 * @mixin \Eloquent
 */
    class SuscriptionPlan extends ModelTenant
    {
        use UsesTenantConnection;

        protected $perPage = 25;

        protected $casts = [
            'cat_period_id' => 'int',
            'total' => 'float'
        ];

        protected $fillable = [
            'cat_period_id',
            'name',
            'description',
            'total'
        ];

        /**
         * @return BelongsTo
         */
        public function cat_period()
        {
            return $this->belongsTo(CatPeriod::class);
        }

        /**
         * @return BelongsToMany
         */
        public function items()
        {
            return $this->belongsToMany(Item::class, 'item_rel_suscription_plans')
                ->withPivot('id')
                ->withTimestamps();
        }

        /**
         * @return BelongsToMany
         */
        public function users()
        {
            return $this->belongsToMany(User::class, 'user_rel_suscription_plans')
                ->withPivot('id', 'cat_period_id', 'items_text', 'items', 'editable', 'deletable', 'start_date')
                ->withTimestamps();
        }

        /**
         * @return string
         */
        public function getName(): string
        {
            return $this->name;
        }


        /**
         * @param string $name
         *
         * @return $this
         */
        public function setName(string $name): SuscriptionPlan
        {
            $this->name = ucfirst(trim($name));
            return $this;
        }

        /**
         * @return string|null
         */
        public function getDescription(): ?string
        {
            return $this->description;
        }

        /**
         * @param string|null $description
         *
         * @return SuscriptionPlan
         */
        public function setDescription(?string $description): SuscriptionPlan
        {
            $this->description = $description;
            return $this;
        }


        /**
         * @return float|null
         */
        public function getTotal(): ?float
        {
            return (float)$this->total;
        }

        /**
         * @param float|null $total
         *
         * @return SuscriptionPlan
         */
        public function setTotal(?float $total): SuscriptionPlan
        {
            $this->total = (float)$total;
            return $this;
        }
    }
