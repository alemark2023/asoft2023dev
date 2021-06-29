<?php

    namespace Modules\DocumentaryProcedure\Models;

    use App\Models\Tenant\ModelTenant;
    use Illuminate\Database\Eloquent\Builder;

    /**
     * Modules\DocumentaryProcedure\Models\DocumentaryAction
     *
     * @property-read mixed $active
     * @method static Builder|DocumentaryAction newModelQuery()
     * @method static Builder|DocumentaryAction newQuery()
     * @method static Builder|DocumentaryAction query()
     * @mixin \Eloquent
     */
    class DocumentaryAction extends ModelTenant {
        protected $table = 'documentary_actions';

        protected $fillable = ['description', 'active', 'name'];

        public function getActiveAttribute($value) {
            return $value ? true : false;
        }

        public function getCollectionData() {
            $data = $this->toArray();
            return $data;
        }
    }
