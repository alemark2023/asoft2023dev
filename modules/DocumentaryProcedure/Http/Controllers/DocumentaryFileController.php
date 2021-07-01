<?php

    namespace Modules\DocumentaryProcedure\Http\Controllers;

    use App\Models\Tenant\Person;
    use Carbon\Carbon;
    use Illuminate\Http\Request;
    use Illuminate\Http\UploadedFile;
    use Illuminate\Routing\Controller;
    use Modules\DocumentaryProcedure\Http\Requests\FileRequest;
    use Modules\DocumentaryProcedure\Models\DocumentaryAction;
    use Modules\DocumentaryProcedure\Models\DocumentaryDocument;
    use Modules\DocumentaryProcedure\Models\DocumentaryFile;
    use Modules\DocumentaryProcedure\Models\DocumentaryFileOffice;
    use Modules\DocumentaryProcedure\Models\DocumentaryFilesArchives;
    use Modules\DocumentaryProcedure\Models\DocumentaryOffice;
    use Modules\DocumentaryProcedure\Models\DocumentaryProcess;
    use Modules\DocumentaryProcedure\Models\RelUserToDocumentaryOffices;
    use Throwable;

    /**
     * Class DocumentaryFileController
     *
     * @package Modules\DocumentaryProcedure\Http\Controllers
     */
    class DocumentaryFileController extends Controller {

        public function getData(Request $request, $id = 0) {


            $files = $this->getDocumentaryFile($request);
            if ($request->has('subject')) {
                $files->where('subject', 'like', "%".$request->subject."%");
            }
            if ($request->has('documentary_office_id') && !empty($request->documentary_office_id)) {
                $files->where('documentary_office_id', $request->documentary_office_id);

                /*
                $files->whereHas('offices', function ($query) use ($request) {
                    $query->where('documentary_office_id', $request->documentary_office_id);
                });
                */
            }
            if ($id != 0) {
                $files->where('id', $id);
                $files = $files->first();

                if (!empty($files)) {
                    $files = ['item' => $files->getCollectionData()];
                }
            } else {
                $files = json_encode($files->get()->transform(function ($row) {
                    return $row->getCollectionData();
                }));
            }

            return $files;
        }

        /**
         * @param \Illuminate\Http\Request $request
         *
         * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Modules\DocumentaryProcedure\Models\DocumentaryFile
         */
        public function getDocumentaryFile(Request $request) {
            $files = DocumentaryFile::with('offices')
                                    ->orderBy('id', 'DESC');
            $dateStart = ($request->has('date_start')) ? $request->date_start : Carbon::now()->format('Y-m-d');
            $dateEnd = ($request->has('date_end')) ? $request->date_end : Carbon::now()->format('Y-m-d');

            $files->where('date_register', '>=', $dateStart);
            $files->where('date_register', '<=', $dateEnd);

            $userType = auth()->user()->type;
            if ($userType !== 'admin') {
                $etapas = RelUserToDocumentaryOffices::where([
                                                                 'user_id' => auth()->user()->id,
                                                             ])->get()->pluck('documentary_office_id');

                $files->wherein('documentary_office_id', $etapas);
            }
            /*
dd([
    $files->toSql(),
    $files->getBindings()
   ]);
            */
            return $files;

        }

        public function index(Request $request) {


            $files = $this->getDocumentaryFile($request);

            if (request()->ajax()) {
                $filter = request('subject');
                if ($filter) {
                    $files = $files->where('subject', 'like', "%$filter%");
                }
                $office = request('documentary_office_id');
                if ($office) {
                    $files = $files->whereHas('offices', function ($query) use ($office) {
                        $query->where('documentary_office_id', $office);
                    });
                }
                $files = $files->get()->transform(function ($row) {
                    return $row->getCollectionData();
                });
                return response()->json(['data' => $files], 200);
            }
            $files = $files->get();

            $files = $files->transform(function ($row) {
                /** @var DocumentaryFile $row */
                return $row->getCollectionData();
            });

            $processes = DocumentaryProcess::orderBy('name')
                                           ->whereActive(true)
                                           ->get()
                                           ->transform(function ($row) {
                                               return $row->getCollectionData();
                                           });

            $actions = DocumentaryAction::orderBy('name')
                                        ->whereActive(true)
                                        ->get()
                                        ->transform(function ($row) {
                                            return $row->getCollectionData();
                                        });

            $customers = Person::with('addresses')
                               ->whereIsEnabled()
                               ->orderBy('name')
                               ->take(20)
                               ->get()
                               ->transform(function ($row) {
                                   return $row->getCollectionData();
                               });

            $offices = DocumentaryOffice::orderBy('id')
                                        ->whereActive(true)
                                        ->get()
                                        ->transform(function ($row) {
                                            /** @var DocumentaryOffice $row */
                                            return $row->getCollectionData();
                                        });


            $documentTypes = DocumentaryDocument::orderBy('name')
                                                ->whereActive(true)
                                                ->get()
                                                ->transform(function ($row) {
                                                    return $row->getCollectionData();
                                                });
            return view('documentaryprocedure::files', compact(
                'files',
                'processes',
                'documentTypes',
                'actions',
                'customers',
                'offices'));
        }

        public function store(FileRequest $request) {

            try {
                $sender = json_decode($request->person);
                if ($request->hasFile('attachFile')) {
                    $request->merge(['attached_file' => $this->storeFile($request->file('attachFile'))]);
                }
                $request->merge(['sender' => $sender]);

                $file = new DocumentaryFile($request->all());
                /*

                $file = DocumentaryFile::create($request->only(
                    'documentary_office_id',
                    'documentary_document_id',
                    'documentary_process_id',
                    'number',
                    'year',
                    'invoice',
                    'date_register',
                    'time_register',
                    'person_id',
                    'sender',
                    'subject',
                    'attached_file',
                    'observation'
                ));
                */

                $file->load('offices');
                $file->push();
                $file_id = $file->id;


                if ($request->has('attachments')) {
                    foreach ($request->attachments as $file) {
                        /** @var \Illuminate\Http\UploadedFile $file */
                        $data = [
                            'user_id'               => auth()->user()->id,
                            'documentary_file_id'   => $file_id,
                            'documentary_office_id' => 0,
                            'observation'           => '',
                            'attached_file'         => $this->storeFile($file),
                        ];
                        $files = new DocumentaryFilesArchives($data);
                        $files->push();
                    }

                }

                return response()->json([
                                            'data'    => $file,
                                            'message' => 'Expediente guardada de forma correcta.',
                                            'succes'  => true,
                                        ], 200);
            } catch (Throwable $th) {

                return response()->json([
                                            'message' => 'Ocurrió un error al procesar su petición. Detalles: '.$th->getMessage(),
                                            'succes'  => false,
                                        ], 500);
            }
        }

        private function storeFile(UploadedFile $file)
        : string {
            $ext = $file->getClientOriginalExtension();
            $filenameOriginal = str_replace('.'.$ext, '', $file->getClientOriginalName());
            $name = $filenameOriginal.'-'.time().'.'.$ext;
            $path = 'storage/uploads/files/';
            $fullpath = $path.$name;
            $file->storeAs('public/uploads/files', $name);

            return $fullpath;
        }

        public function nextStep(Request $request) {

            $documentary_action_id = (int)$request->documentary_action_id;
            $documentary_office_id = (int)$request->documentary_office_id;
            $observation = (string)$request->observation;
            if (empty($observation)) $observation = '';

            $office = DocumentaryFile::find($request->id);
            $current_office = $office->documentary_office_id;

            $next = DocumentaryOffice::where('id', '>', $office->documentary_office_id)->first();

            $record = new DocumentaryFileOffice();
            $record
                ->setDocumentaryOfficeId((int)$office->documentary_office_id)
                ->setDocumentaryActionId((int)$office->documentary_action_id)
                ->setDocumentaryFileId((int)$office->id)
                ->setObservation($office->getObservation());
            $record->push();
            $office
                ->setDocumentaryOfficeId($next->id)
                ->setObservation($observation);
            $office->push();

            $files = $this->getDocumentaryFile($request)
                          ->get()
                          ->transform(function ($row) {
                              /** @var DocumentaryFile $row */
                              return $row->getCollectionData();
                          });


            if ($request->has('file')) {
                foreach ($request->file as $file) {
                    /** @var \Illuminate\Http\UploadedFile $file */
                    $data = [
                        'user_id'               => auth()->user()->id,
                        'documentary_file_id'   => $request->id,
                        'documentary_office_id' => $current_office,
                        'observation'           => $observation,
                        'attached_file'         => $this->storeFile($file),
                    ];
                    $newFile = new DocumentaryFilesArchives($data);
                    $newFile->push();
                }

            }
            return response()->json([
                                        'data'           => $office,
                                        // 'request'   => $request->all(),
                                        'files'          => $files,
                                        'current_office' => $current_office,
                                        'next_office'    => $office->documentary_office_id,
                                        // 'next'   => $next,
                                        'message'        => 'Expediente guardada de forma correcta.',
                                        'succes'         => true,
                                    ], 200);
        }

        public function backStep(Request $request) {

            $documentary_action_id = (int)$request->documentary_action_id;
            $documentary_office_id = (int)$request->documentary_office_id;
            $observation = (string)$request->observation;
            if (empty($observation)) $observation = '';

            $office = DocumentaryFile::find($request->id);
            $back = DocumentaryOffice::find($office->documentary_office_id)->getBack();
            $current_office = $office->documentary_office_id;

            $record = new DocumentaryFileOffice();
            $record
                ->setDocumentaryOfficeId((int)$office->documentary_office_id)
                ->setDocumentaryActionId((int)$office->documentary_action_id)
                ->setDocumentaryFileId((int)$office->id)
                ->setObservation($office->getObservation());
            $record->push();
            $office->setObservation($request->observation);
            if (!empty($back)) {
                $office->setDocumentaryOfficeId($back);
            }
            $office->push();

            if ($request->has('file')) {
                foreach ($request->file as $file) {
                    /** @var \Illuminate\Http\UploadedFile $file */
                    $data = [
                        'user_id'               => auth()->user()->id,
                        'documentary_file_id'   => $request->id,
                        'documentary_office_id' => $current_office,
                        'observation'           => $observation,
                        'attached_file'         => $this->storeFile($file),
                    ];
                    $newFile = new DocumentaryFilesArchives($data);
                    $newFile->push();
                }

            }
            $files = $this->getDocumentaryFile($request)
                          ->get()
                          ->transform(function ($row) {
                              /** @var DocumentaryFile $row */
                              return $row->getCollectionData();
                          });

            return response()->json([
                                        'data'           => $office,
                                        'files'          => $files,
                                        'message'        => 'Expediente guardada de forma correcta.',
                                        'back'           => $back,
                                        'current_office' => $current_office,
                                        'succes'         => true,
                                    ], 200);
        }

        public function addOffice(Request $request, $fileId) {
            request()->validate([
                                    'documentary_office_id' => 'required|numeric',
                                    'documentary_action_id' => 'required|numeric',
                                    'observation'           => 'max:300',
                                ]);

            $file = DocumentaryFile::findOrFail($fileId);

            /*
            $office = $file->offices()->create(request()->only('documentary_office_id', 'documentary_action_id',
                                                               'observation'));
            */


            $files = $this->getDocumentaryFile($request)
                          ->get()
                          ->transform(function ($row) {
                              /** @var DocumentaryFile $row */
                              return $row->getCollectionData();
                          });

            return response()->json([
                                        'data'    => $office,
                                        'files'   => $files,
                                        'message' => 'Expediente guardada de forma correcta.',
                                        'succes'  => true,
                                    ], 200);
        }

        public function update(FileRequest $request, $id) {
            $sender = json_decode($request->person);
            if ($request->hasFile('attachFile') && $request->file('attachFile')->isValid()) {
                $request->merge(['attached_file' => $this->storeFile($request->file('attachFilefile'))]);
            }
            $request->merge(['sender' => $sender]);

            $file = DocumentaryFile::findOrFail($id);
            if (!empty($file)) {

                $file
                    ->setDocumentaryOfficeId($request->documentary_office_id)
                    ->setDocumentaryDocumentId($request->documentary_document_id)
                    ->setDocumentaryProcessId($request->documentary_process_id)
                    ->setSubject($request->subject)
                    ->setNumber($request->number)
                    ->setYear($request->year)
                    ->setInvoice($request->invoice)
                    ->setDateRegister($request->date_register)
                    ->setTimeRegister($request->time_register)
                    ->setPersonId($request->person_id)
                    ->setAttachedFile($request->attached_file)
                    ->setObservation($request->observation);
                $file->fill([
                                'sender' => $request->sender,
                            ]);
            }
            $file->push();
            $file_id = $file->id;
            $file_documentary_office_id = $file->documentary_office_id;
            if ($request->has('attachments')) {
                foreach ($request->attachments as $file) {
                    /** @var \Illuminate\Http\UploadedFile $file */
                    $data = [
                        'user_id'               => auth()->user()->id,
                        'documentary_file_id'   => $file_id,
                        'documentary_office_id' => $file_documentary_office_id,
                        'observation'           => '',
                        'attached_file'         => DocumentaryFilesArchives::saveFile($file),
                        //'attached_file'         => $this->storeFile($file),
                    ];
                    $files = new DocumentaryFilesArchives($data);
                    $files->push();
                }

            }

            return response()->json([
                                        'data'    => $file,
                                        'message' => 'Expediente actualizada de forma correcta.',
                                        'succes'  => true,
                                    ], 200);
        }

        public function destroy($id) {
            try {
                $file = DocumentaryFile::findOrFail($id);
                $file->delete();

                return response()->json([
                                            'data'    => null,
                                            'message' => 'Expediente eliminada de forma correcta.',
                                            'succes'  => true,
                                        ], 200);
            } catch (Throwable $th) {
                return response()->json([
                                            'success' => false,
                                            'data'    => 'Ocurrió un error al procesar su petición. Detalles: '.$th->getMessage(),
                                        ], 500);
            }
        }

        public function tables() {
            $documentTypes = DocumentaryDocument::orderBy('name')
                                                ->whereActive(true)
                                                ->get()
                                                ->transform(function ($row) {
                                                    return $row->getCollectionData();
                                                });

            $processes = DocumentaryProcess::orderBy('name')
                                           ->whereActive(true)
                                           ->get()
                                           ->transform(function ($row) {
                                               return $row->getCollectionData();
                                           });

            $actions = DocumentaryAction::orderBy('name')
                                        ->whereActive(true)
                                        ->get()
                                        ->transform(function ($row) {
                                            return $row->getCollectionData();
                                        });

            $customers = Person::with('addresses')
                               ->whereIsEnabled()
                               ->orderBy('name')
                               ->take(20)
                               ->get()
                               ->transform(function ($row) {
                                   return $row->getCollectionData();
                                   return [
                                       'id'                          => $row->id,
                                       'description'                 => $row->number.' - '.$row->name,
                                       'name'                        => $row->name,
                                       'number'                      => $row->number,
                                       'identity_document_type_id'   => $row->identity_document_type_id,
                                       'identity_document_type_code' => $row->identity_document_type->code,
                                       'addresses'                   => $row->addresses,
                                       'address'                     => $row->address,
                                       'internal_code'               => $row->internal_code,
                                   ];
                               });

            $offices = DocumentaryOffice::orderBy('id')
                                        ->whereActive(true)
                                        ->get()
                                        ->transform(function ($row) {
                                            /** @var DocumentaryOffice $row */
                                            return $row->getCollectionData();
                                        });

            return response()->json([
                                        'success' => true,
                                        'message' => 'Información procesada de forma correcta',
                                        'data'    => [
                                            'document_types' => $documentTypes,
                                            'processes'      => $processes,
                                            'customers'      => $customers,
                                            'offices'        => $offices,
                                            'actions'        => $actions,
                                        ],
                                    ], 200);
        }

        public function create() {
            $lastId = DocumentaryFile::count();

            return response()->json([
                                        'success' => true,
                                        'message' => 'Información procesada de forma correcta',
                                        'data'    => [
                                            'next_id'      => $lastId + 1,
                                            'current_year' => date('Y'),
                                        ],
                                    ], 200);
        }

        public function getDocumentNumber() {
            request()->validate([
                                    'document_id' => 'required|numeric',
                                ]);

            $countForDocumentType = DocumentaryFile::where('documentary_document_id', request('document_id'))
                                                   ->count();

            return response()->json([
                                        'success' => true,
                                        'message' => 'Información procesada de forma correcta',
                                        'data'    => [
                                            'number' => $countForDocumentType + 1,
                                        ],
                                    ], 200);
        }
    }
