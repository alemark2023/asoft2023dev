<?php

    /**
     */

    namespace Modules\Suscription\Models\Tenant;


    use App\Models\Tenant\ModelTenant;
    use App\Models\Tenant\User;
    use Carbon\Carbon;
    use Hyn\Tenancy\Traits\UsesTenantConnection;

    /**
 * Class UserRelSuscriptionPlan
 *
 * @property int                  $id
 * @property int|null             $user_id
 * @property int|null             $suscription_plan_id
 * @property int|null             $cat_period_id
 * @property string               $items_text
 * @property string               $items
 * @property bool                 $editable
 * @property bool                 $deletable
 * @property string|null          $start_date
 * @property Carbon|null          $created_at
 * @property Carbon|null          $updated_at
 * @property CatPeriod|null       $cat_period
 * @property SuscriptionPlan|null $suscription_plan
 * @property User|null            $user
 * @package App\Models\Tenant\ModelTenant
 * @method static \Illuminate\Database\Eloquent\Builder|UserRelSuscriptionPlan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRelSuscriptionPlan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRelSuscriptionPlan query()
 * @mixin \Eloquent
 */
    class UserRelSuscriptionPlan extends ModelTenant
    {
        use UsesTenantConnection;

        protected $casts = [
            'user_id' => 'int',
            'suscription_plan_id' => 'int',
            'cat_period_id' => 'int',
            'editable' => 'bool',
            'deletable' => 'bool'
        ];

        protected $dates = [
            // 'start_date'
        ];

        protected $fillable = [
            'user_id',
            'suscription_plan_id',
            'cat_period_id',
            'items_text',
            'items',
            'editable',
            'deletable',
            'start_date'
        ];

        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function cat_period()
        {
            return $this->belongsTo(CatPeriod::class);
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function suscription_plan()
        {
            return $this->belongsTo(SuscriptionPlan::class);
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function user()
        {
            return $this->belongsTo(User::class);
        }

        /**
         * @return int
         */
        public function getUserId(): int
        {
            return $this->user_id;
        }

        /**
         * @param int $user_id
         *
         * @return UserRelSuscriptionPlan
         */
        public function setUserId(int $user_id): UserRelSuscriptionPlan
        {
            $this->user_id = $user_id;
            return $this;
        }

        /**
         * @return int
         */
        public function getSuscriptionPlanId(): int
        {
            return $this->suscription_plan_id;
        }

        /**
         * @param int $suscription_plan_id
         *
         * @return UserRelSuscriptionPlan
         */
        public function setSuscriptionPlanId(int $suscription_plan_id): UserRelSuscriptionPlan
        {
            $this->suscription_plan_id = $suscription_plan_id;
            return $this;
        }

        /**
         * @return int
         */
        public function getCatPeriodId(): int
        {
            return $this->cat_period_id;
        }

        /**
         * @param int $cat_period_id
         *
         * @return UserRelSuscriptionPlan
         */
        public function setCatPeriodId(int $cat_period_id): UserRelSuscriptionPlan
        {
            $this->cat_period_id = $cat_period_id;
            return $this;
        }

        /**
         * @return string
         */
        public function getItemsText(): string
        {
            return $this->items_text;
        }

        /**
         * @param string $items_text
         *
         * @return UserRelSuscriptionPlan
         */
        public function setItemsText(string $items_text): UserRelSuscriptionPlan
        {
            $this->items_text = $items_text;
            return $this;
        }

        /**
         * @return string
         */
        public function getItems(): string
        {
            return $this->items;
        }

        /**
         * @param string $items
         *
         * @return UserRelSuscriptionPlan
         */
        public function setItems(string $items): UserRelSuscriptionPlan
        {
            $this->items = $items;
            return $this;
        }

        /**
         * @return bool
         */
        public function isEditable(): bool
        {
            return $this->editable;
        }

        /**
         * @param bool $editable
         *
         * @return UserRelSuscriptionPlan
         */
        public function setEditable(bool $editable): UserRelSuscriptionPlan
        {
            $this->editable = $editable;
            return $this;
        }

        /**
         * @return bool
         */
        public function isDeletable(): bool
        {
            return $this->deletable;
        }

        /**
         * @param bool $deletable
         *
         * @return UserRelSuscriptionPlan
         */
        public function setDeletable(bool $deletable): UserRelSuscriptionPlan
        {
            $this->deletable = $deletable;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getStartDate(): ?string
        {
            return $this->start_date;
        }

        /**
         * @param string|null $start_date
         *
         * @return UserRelSuscriptionPlan
         */
        public function setStartDate(?string $start_date): UserRelSuscriptionPlan
        {
            if (empty($start_date)) $start_date = Carbon::now()->format('Y-m-d');
            $this->start_date = $start_date;
            return $this;
        }


    }
