<?php

    namespace Modules\DocumentaryProcedure\Models;

    use App\Models\Tenant\ModelTenant;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Builder;

    /**
     * Modules\DocumentaryProcedure\Models\DocumentaryOffice
     *
     * @property-read mixed $active
     * @method static Builder|DocumentaryOffice newModelQuery()
     * @method static Builder|DocumentaryOffice newQuery()
     * @method static Builder|DocumentaryOffice query()
     * @mixin \Eloquent
     */
    class DocumentaryOffice extends ModelTenant {
        protected $table = 'documentary_offices';
        use UsesTenantConnection;

        protected $fillable = [
            'description',
            'active',
            'name',
            'parent_id',
            'order',
        ];

        /**
         * @return string
         */
        public function getDescription()
        : string {
            return $this->description;
        }

        /**
         * @param string $description
         *
         * @return DocumentaryOffice
         */
        public function setDescription(string $description)
        : DocumentaryOffice {
            $this->description = $description;
            return $this;
        }

        /**
         * @return string
         */
        public function getName()
        : string {
            return $this->name;
        }

        /**
         * @param string $name
         *
         * @return DocumentaryOffice
         */
        public function setName(string $name)
        : DocumentaryOffice {
            $this->name = $name;
            return $this;
        }

        /**
         * @param int $parent_id
         *
         * @return DocumentaryOffice
         */
        public function setParentId(int $parent_id)
        : DocumentaryOffice {
            $this->parent_id = $parent_id;
            return $this;
        }

        /**
         * @return int
         */
        public function getOrder()
        : int {
            return $this->order;
        }

        /**
         * @param int $order
         *
         * @return DocumentaryOffice
         */
        public function setOrder(int $order)
        : DocumentaryOffice {
            $this->order = $order;
            return $this;
        }

        /**
         * @param $value
         *
         * @return bool
         */
        public function getActiveAttribute($value) {
            return $value ? true : false;
        }

        /**
         * @param false $extended
         *
         * @return array
         */
        public function getCollectionData($extended = false) {

            $data = $this->toArray();
            $parent = [];
            if ($this->getParentId() != 0) {
                $parent = self::find($this->getParentId());
                $parent = $parent->getCollectionData();
            }

            $data['parent'] = $parent;
            $data['rel_user_to_documentary_offices'] =
                RelUserToDocumentaryOffices::where('documentary_office_id',
                                                   $this->id)
                                           ->get()
                                           ->transform(function ($row) {
                                               return $row->getCollectionData();
                                           });
            $data['users'] = RelUserToDocumentaryOffices::
            where('documentary_office_id', $this->id)->where('active', 1)
                                                        ->get()->pluck('user_id');
            $data['print_name'] = $this->id." - ".$this->name;

            if ($extended === true) {

                $data['documentary_files_archives'] =
                    DocumentaryFilesArchives::where('documentary_office_id',
                                                    $this->id)
                                            ->get()
                                            ->transform(function ($row) {
                                                return $row->getCollectionData();
                                            });


                $data['documentary_file_offices'] =
                    DocumentaryFileOffice::where('documentary_office_id', $this->id)
                                         ->get()
                                         ->transform(function ($row) {
                                             return $row->getCollectionData();
                                         });
            }


            return $data;
        }

        /**
         * @return int
         */
        public function getParentId()
        : int {
            return (int)$this->parent_id;
        }

        public function getBack(){
            $parent = $this->getParentId();
            $work = self::where('id','<',$this->id);
            if($parent != 0){
                 $work->where('parent_id',$parent);
            }
            $work = $work->max('id');
            dd($work);

            return $work;
        }
    }
