@extends('tenant.layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <div>
                        <h4 class="card-title">Consulta de Documentos</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div>
                        <form action="{{route('tenant.search')}}" class="el-form demo-form-inline el-form--inline" method="GET">
                            <tenant-calendar :document_types="{{json_encode($documentTypes)}}" :establishments="{{json_encode($establishments)}}" establishment="{{$establishment ?? null}}" data_d="{{$d ?? ''}}" data_a="{{$a ?? ''}}" td="{{$td ?? null}}"></tenant-calendar>
                        </form>
                    </div>
                    @if(!empty($reports) && $reports->count())
                    <div class="box">
                        <div class="box-body no-padding">
                            <div style="margin-bottom: 10px">
                                @if(isset($reports))
                                    <form action="{{route('tenant.report_pdf')}}" class="d-inline" method="POST">
                                        {{csrf_field()}}
                                        <input type="hidden" value="{{$d}}" name="d">
                                        <input type="hidden" value="{{$a}}" name="a">
                                        <input type="hidden" value="{{$td}}" name="td">
                                        <input type="hidden" value="{{$establishment}}" name="establishment">
                                        <button class="btn btn-custom   mt-2 mr-2" type="submit"><i class="fa fa-file-pdf"></i> Exportar PDF</button>
                                        {{-- <label class="pull-right">Se encontraron {{$reports->count()}} registros.</label> --}}
                                    </form>
                                <form action="{{route('tenant.report_excel')}}" class="d-inline" method="POST">
                                    {{csrf_field()}}
                                    <input type="hidden" value="{{$d}}" name="d">
                                    <input type="hidden" value="{{$td}}" name="td">
                                    <input type="hidden" value="{{$a}} " name="a">
                                    <input type="hidden" value="{{$establishment}}" name="establishment">
                                    <button class="btn btn-custom   mt-2 mr-2" type="submit"><i class="fa fa-file-excel"></i> Exportar Excel</button>
                                    {{-- <label class="pull-right">Se encontraron {{$reports->count()}} registros.</label> --}}
                                </form>
                                @endif
                            </div>
                            @php
                                $acum_total_taxed=0;
                                $acum_total_igv=0;
                                $acum_total=0;
<<<<<<< HEAD
                              
                                $serie_affec = '';

                                $acum_total_exonerado=0;
                                $acum_total_inafecto=0;
                             
                                $acum_total_free=0;



                            

=======

                                $acum_total_taxed_usd=0;
                                $acum_total_igv_usd=0;
                                $acum_total_usd=0;
>>>>>>> 834e088a74a30e449b98e830f1e5af66c68b01bd
                            @endphp
                            <table width="100%" class="table table-striped table-responsive-xl table-bordered table-hover">
                                <thead class="">
                                    <tr>
                                        <th class="">#</th>
                                        <th class="">Tipo Documento</th>
                                        <th class="">Comprobante</th>
                                        <th class="">Fecha emisión</th>
                                        <th class="">Cliente</th>
                                        <th class="">RUC</th>
                                        <th class="">Estado</th>
<<<<<<< HEAD
                                        <th class="">Total Exonerado</th>
                                        <th class="">Total Inafecto</th>
                                        <th class="">Total Gratutio</th>
=======
                                        <th class="">Moneda</th>
>>>>>>> 834e088a74a30e449b98e830f1e5af66c68b01bd
                                        <th class="">Total Gravado</th>
                                      
                                        <th class="">Total IGV</th>
                                        <th class="">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reports as $key => $value)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$value->document_type->id}}</td>
                                        <td>{{$value->series}}-{{$value->number}}</td>
                                        <td>{{$value->date_of_issue->format('Y-m-d')}}</td>
<<<<<<< HEAD
                                         
                                        @if($value->document_type_id == "07" && $value->note)

                                          @php
                                            $serie = $value->note->affected_document->series;
                                            $number =  $value->note->affected_document->number;
                                            $serie_affec = $serie.' - '.$number;

                                          @endphp
                                        

                                        @endif
                                        
                                       
                                        <td>{{$serie_affec}} </td>
                                        <td>{{$value->person->name}}</td>
                                        <td>{{$value->person->number}}</td>
                                        <td>{{$value->state_type->description}}</td>

                                        @php
                                         $signal = $value->document_type_id;
                                        @endphp
                                      
                                        
                                        <td>{{$signal == '07' ? "-" : ""  }}{{$value->total_exonerated}} </td>
                                        <td>{{$signal == '07' ? "-" : ""  }}{{$value->total_unaffected}}</td>
                                        <td>{{$signal == '07' ? "-" : ""  }}{{$value->total_free}}</td>
                                        <td>{{$signal == '07' ? "-" : ""  }}{{$value->total_taxed}}</td>
                                      
                                        <td>{{$signal == '07' ? "-" : ""  }}{{$value->total_igv}}</td>
                                        <td>{{$signal == '07' ? "-" : ""  }}{{$value->total}}</td>
                                    </tr>
                                    @php
                                        $acum_total_taxed += $value->total_taxed;
                                        $acum_total_igv += $value->total_igv;
                                       

                                        if($signal == '07')
                                        {
                                           $acum_total -= $value->total;
                                        }
                                        else{
                                            $acum_total += $value->total;
                                        }

                                        $acum_total_exonerado += $value->total_exonerated;
                                      
                                        
                                        $acum_total_inafecto +=  $value->total_unaffected;
                                     
                                       
                                        $acum_total_free += $value->total_free;

                                        $serie_affec =  '';
=======
                                        <td>{{$value->person->name}}</td>
                                        <td>{{$value->person->number}}</td>
                                        <td>{{$value->state_type->description}}</td>
                                        <td>{{$value->currency_type_id}}</td>
                                        <td>{{$value->total_taxed}}</td>
                                        <td>{{$value->total_igv}}</td>
                                        <td>{{$value->total}}</td>
                                    </tr>
                                    @php
                                        if($value->currency_type_id == 'PEN'){
                                            $acum_total_taxed += $value->total_taxed;
                                            $acum_total_igv += $value->total_igv;
                                            $acum_total += $value->total;
                                        }else if($value->currency_type_id == 'USD'){
                                            $acum_total_taxed_usd += $value->total_taxed;
                                            $acum_total_igv_usd += $value->total_igv;
                                            $acum_total_usd += $value->total;
                                        }

>>>>>>> 834e088a74a30e449b98e830f1e5af66c68b01bd
                                    @endphp
                                    @endforeach
                                    <tr>
                                        <td colspan="7"></td>
<<<<<<< HEAD
                                      
                                        <td>Totales</td>
                                        <td>{{$acum_total_exonerado}}</td>
                                        <td>{{$acum_total_inafecto}}</td>
                                        <td>{{$acum_total_free}}</td>
=======
                                        <td >Totales PEN</td>
>>>>>>> 834e088a74a30e449b98e830f1e5af66c68b01bd
                                        <td>{{$acum_total_taxed}}</td>
                                        <td>{{$acum_total_igv}}</td>
                                        <td>{{$acum_total}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="7"></td>
                                        <td >Totales USD</td>
                                        <td>{{$acum_total_taxed_usd}}</td>
                                        <td>{{$acum_total_igv_usd}}</td>
                                        <td>{{$acum_total_usd}}</td>
                                    </tr>
                                </tbody>
                            </table>
                            Total {{$reports->total()}}
                            <label class="pagination-wrapper ml-2">
                                {{-- {{ $reports->appends(['search' => Session::get('form_document_list')])->render()  }} --}}
                                {{$reports->appends($_GET)->render()}} 
                            </label>
                        </div>
                    </div>
                    @else
                    <div class="box box-body no-padding">
                        <strong>No se encontraron registros</strong>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script></script>
@endpush
