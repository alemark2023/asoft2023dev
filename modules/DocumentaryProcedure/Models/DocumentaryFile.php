<?php

    namespace Modules\DocumentaryProcedure\Models;

    use App\Models\Tenant\ModelTenant;
    use App\Models\Tenant\Person;
    use Illuminate\Database\Eloquent\Builder;

    /**
     * Modules\DocumentaryProcedure\Models\DocumentaryFile
     *
     * @property-read mixed                                                                                                 $active
     * @property mixed                                                                                                      $sender
     * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\DocumentaryProcedure\Models\DocumentaryFileOffice[] $offices
     * @property-read int|null                                                                                              $offices_count
     * @method static Builder|DocumentaryFile newModelQuery()
     * @method static Builder|DocumentaryFile newQuery()
     * @method static Builder|DocumentaryFile query()
     * @mixin \Eloquent
     */
    class DocumentaryFile extends ModelTenant {
        protected $table = 'documentary_files';

        protected $fillable = [
            'documentary_document_id',
            'documentary_process_id',
            'documentary_file_office_id',
            'number',
            'year',
            'invoice',
            'date_register',
            'time_register',
            'person_id',
            'sender',
            'subject',
            'attached_file',
            'observation',
        ];

        /**
         * @return int
         */
        public function getDocumentaryFileOfficeId()
        : int {
            return $this->documentary_file_office_id;
        }

        /**
         * @param int $documentary_file_office_id
         *
         * @return DocumentaryFile
         */
        public function setDocumentaryFileOfficeId(int $documentary_file_office_id)
        : DocumentaryFile {
            $this->documentary_file_office_id = $documentary_file_office_id;
            return $this;
        }

        public function getActiveAttribute($value) {
            return $value ? true : false;
        }

        public function getSenderAttribute($value) {
            return (is_null($value)) ? null : (object)json_decode($value);
        }

        public function setSenderAttribute($value) {
            $this->attributes['sender'] = (is_null($value)) ? null : json_encode($value);
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function offices() {
            return $this->hasMany(DocumentaryFileOffice::class, 'documentary_file_id');
        }

        public function getCollectionData() {
            $data = $this->toArray();
            $documentary_document = DocumentaryDocument::find($this->documentary_document_id);
            $documentary_process = DocumentaryProcess::find($this->documentary_process_id);
            $documentary_file_office = DocumentaryFileOffice::find($this->documentary_process_id);
            $data['documentary_document'] = $documentary_document->getCollectionData();
            $data['documentary_process'] = $documentary_process->getCollectionData();
            $data['documentary_file_office_id'] = $documentary_file_office->getCollectionData();
            $data['person'] = Person::find($this->person_id);
            return $data;

        }
    }
