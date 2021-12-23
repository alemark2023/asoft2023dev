<?php

    namespace Modules\LogisticOperator\Models;


    use App\Models\Tenant\ModelTenant;
    use Carbon\Carbon;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;

    /**
     * Class LogisticYobelApi
     *
     * @property int                $id
     * @property int|null           $logistic_yobel_id
     * @property string|null        $yobel_send
     * @property string|null        $command
     * @property string|null        $yobel_response
     * @property int                $status
     * @property Carbon|null        $last_check
     * @property Carbon|null        $created_at
     * @property Carbon|null        $updated_at
     * @property LogisticYobel|null $logistic_yobel
     * @mixin ModelTenant
     * @package App\Models
     * @method static Builder|LogisticYobelApi newModelQuery()
     * @method static Builder|LogisticYobelApi newQuery()
     * @method static Builder|LogisticYobelApi query()
     */
    class LogisticYobelApi extends ModelTenant
    {
        use UsesTenantConnection;

        protected $table = 'logistic_yobel_api';
        protected $perPage = 25;

        protected $casts = [
            'logistic_yobel_id' => 'int',
            'status' => 'int'
        ];

        protected $dates = [
            'last_check'
        ];

        protected $fillable = [
            'logistic_yobel_id',
            'command',
            'yobel_send',
            'yobel_response',
            'status',
            'last_check'
        ];

        /**
         * @return BelongsTo
         */
        public function logistic_yobel()
        {
            return $this->belongsTo(LogisticYobel::class);
        }
    }
