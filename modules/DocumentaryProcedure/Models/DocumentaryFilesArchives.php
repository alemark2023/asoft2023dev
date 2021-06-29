<?php

    namespace Modules\DocumentaryProcedure\Models;

    use App\Models\Tenant\ModelTenant;
    use App\Models\Tenant\User;
    use Illuminate\Database\Eloquent\Builder;

    /**
     * Modules\DocumentaryProcedure\Models\DocumentaryFilesArchives
     *
     * @method static Builder|DocumentaryFilesArchives newModelQuery()
     * @method static Builder|DocumentaryFilesArchives newQuery()
     * @method static Builder|DocumentaryFilesArchives query()
     * @mixin \Eloquent
     */
    class DocumentaryFilesArchives extends ModelTenant {
        protected $table = 'documentary_files_archives';

        protected $fillable = [
            'user_id',
            'documentary_file_id',
            'documentary_office_id',
            'observation',
            'attached_file',
        ];

        protected static function boot() {
            parent::boot();
            static::creating(function (DocumentaryFilesArchives $model) {
                if (auth() and auth()->user() and auth()->user()->id) {
                    $model->user_id = auth()->user()->id;
                } else {
                    $model->user_id = 0;
                }


            });
        }

        /**
         * @return int
         */
        public function getUserId()
        : int {
            return $this->user_id;
        }

        /**
         * @param int $user_id
         *
         * @return DocumentaryFilesArchives
         */
        public function setUserId(int $user_id)
        : DocumentaryFilesArchives {
            $this->user_id = $user_id;
            return $this;
        }

        /**
         * @return int
         */
        public function getDocumentaryFileId()
        : int {
            return $this->documentary_file_id;
        }

        /**
         * @param int $documentary_file_id
         *
         * @return DocumentaryFilesArchives
         */
        public function setDocumentaryFileId(int $documentary_file_id)
        : DocumentaryFilesArchives {
            $this->documentary_file_id = $documentary_file_id;
            return $this;
        }

        /**
         * @return int
         */
        public function getDocumentaryOfficeId()
        : int {
            return $this->documentary_office_id;
        }

        /**
         * @param int $documentary_office_id
         *
         * @return DocumentaryFilesArchives
         */
        public function setDocumentaryOfficeId(int $documentary_office_id)
        : DocumentaryFilesArchives {
            $this->documentary_office_id = $documentary_office_id;
            return $this;
        }

        /**
         * @return string
         */
        public function getObservation()
        : string {
            return $this->observation;
        }

        /**
         * @param string $observation
         *
         * @return DocumentaryFilesArchives
         */
        public function setObservation(string $observation)
        : DocumentaryFilesArchives {
            $this->observation = $observation;
            return $this;
        }

        /**
         * @return string
         */
        public function getAttachedFile()
        : string {
            return $this->attached_file;
        }

        /**
         * @param string $attached_file
         *
         * @return DocumentaryFilesArchives
         */
        public function setAttachedFile(string $attached_file)
        : DocumentaryFilesArchives {
            $this->attached_file = $attached_file;
            return $this;
        }

        public function getCollectionData() {
            $data = $this->toArray();
            $data['user'] = User::find($this->user_id);
            $data['documentary_office'] = DocumentaryOffice::find($this->documentary_office_id);
            $data['documentary_file'] = DocumentaryFile::find($this->documentary_file_id);
            return $data;
        }
    }
