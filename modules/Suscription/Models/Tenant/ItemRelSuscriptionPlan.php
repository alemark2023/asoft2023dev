<?php

    /**
     */

    namespace Modules\Suscription\Models\Tenant;


    use App\Models\Tenant\Item;
    use App\Models\Tenant\ModelTenant;
    use Carbon\Carbon;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Builder;

    /**
 * Class ItemRelSuscriptionPlan
 *
 * @property int                  $id
 * @property int|null             $item_id
 * @property int|null             $suscription_plan_id
 * @property Carbon|null          $created_at
 * @property Carbon|null          $updated_at
 * @property Item|null            $item
 * @property SuscriptionPlan|null $suscription_plan
 * @package App\Models\Tenant\ModelTenant
 * @method static Builder|ItemRelSuscriptionPlan newModelQuery()
 * @method static Builder|ItemRelSuscriptionPlan newQuery()
 * @method static Builder|ItemRelSuscriptionPlan query()
 * @mixin \Eloquent
 */
    class ItemRelSuscriptionPlan extends ModelTenant
    {
        use UsesTenantConnection;

        protected $casts = [
            'item_id' => 'int',
            'suscription_plan_id' => 'int'
        ];

        protected $fillable = [
            'item_id',
            'suscription_plan_id'
        ];

        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function item()
        {
            return $this->belongsTo(Item::class);
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function suscription_plan()
        {
            return $this->belongsTo(SuscriptionPlan::class);
        }

        /**
         * @return int|null
         */
        public function getItemId(): ?int
        {
            return $this->item_id;
        }

        /**
         * @param int|null $item_id
         *
         * @return ItemRelSuscriptionPlan
         */
        public function setItemId(?int $item_id): ItemRelSuscriptionPlan
        {
            $this->item_id = $item_id;
            return $this;
        }

        /**
         * @return int|null
         */
        public function getSuscriptionPlanId(): ?int
        {
            return $this->suscription_plan_id;
        }

        /**
         * @param int|null $suscription_plan_id
         *
         * @return ItemRelSuscriptionPlan
         */
        public function setSuscriptionPlanId(?int $suscription_plan_id): ItemRelSuscriptionPlan
        {
            $this->suscription_plan_id = $suscription_plan_id;
            return $this;
        }

        /**
         * @return Item|null
         */
        public function getItem(): ?Item
        {
            return $this->item;
        }

        /**
         * @param Item|null $item
         *
         * @return ItemRelSuscriptionPlan
         */
        public function setItem(?Item $item): ItemRelSuscriptionPlan
        {
            $this->item = $item;
            return $this;
        }

        /**
         * @return SuscriptionPlan|null
         */
        public function getSuscriptionPlan(): ?SuscriptionPlan
        {
            return $this->suscription_plan;
        }

        /**
         * @param SuscriptionPlan|null $suscription_plan
         *
         * @return ItemRelSuscriptionPlan
         */
        public function setSuscriptionPlan(?SuscriptionPlan $suscription_plan): ItemRelSuscriptionPlan
        {
            $this->suscription_plan = $suscription_plan;
            return $this;
        }
    }
