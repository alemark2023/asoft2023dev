@extends('tenant.layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <div>
                        <h4 class="card-title">Consulta kardex</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div>
                        <form action="{{route('reports.kardex.search')}}" class="el-form demo-form-inline el-form--inline" method="POST">
                            {{csrf_field()}}
                            <div class="box ">
                                <div class="box-body no-padding">
                                    {{Form::label('item_id', 'Producto')}}
                                    {{Form::select('item_id', $items->pluck('description', 'id'), old('item_id', request()->item_id), ['class' => 'form-control col-md-6'])}}
                                </div>
                                <div class="el-form-item col-xs-12">
                                    <div class="el-form-item__content">
                                        <button class="btn btn-custom" type="submit"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    @if(!empty($reports) && $reports->count())
                    <div class="box">
                        <div class="box-body no-padding">
                            <div style="margin-bottom: 10px">
                                @if(isset($reports))
                                    <form action="{{route('reports.kardex.pdf')}}" class="d-inline" method="POST">
                                        {{csrf_field()}}
                                        <input type="hidden" name="item_id" value="{{old('item_id', request()->item_id)}}">
                                        <button class="btn btn-custom   mt-2 mr-2" type="submit"><i class="fa fa-file-pdf"></i> Exportar PDF</button>
                                        {{-- <label class="pull-right">Se encontraron {{$reports->count()}} registros.</label> --}}
                                    </form>
                                <form action="{{route('reports.kardex.report_excel')}}" class="d-inline" method="POST">
                                    {{csrf_field()}}
                                    <input type="hidden" name="item_id" value="{{old('item_id', request()->item_id)}}">
                                    <button class="btn btn-custom   mt-2 mr-2" type="submit"><i class="fa fa-file-excel"></i> Exportar Excel</button>
                                    {{-- <label class="pull-right">Se encontraron {{$reports->count()}} registros.</label> --}}
                                </form>
                                @endif
                            </div>
                            <table width="100%" class="table table-striped table-responsive-xl table-bordered table-hover">
                                <thead class="">
                                    <tr>
                                        <th>#</th>
                                        <th>Fecha y hora</th>
                                        <th>Tipo transacción</th>
                                        <th>Número</th>
                                        <th>Entrada</th>
                                        <th>Salida</th>
                                        <th>Saldo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reports as $key => $value)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$value->created_at}}</td>
                                        <td>

                                            @switch($value->inventory_kardexable_type)
                                                @case($models[0])
                                                    {{($value->quantity < 0) ? "Venta":"Anulación"}}
                                                    @break
                                                @case($models[1])
                                                    {{"Compra"}}                                                    
                                                    @break 
                                                    
                                                @case($models[2])
                                                    {{"Nota de venta"}}                                                    
                                                    @break  

                                                @case($models[3])
                                                    {{$value->inventory_kardexable->description}}                                                    
                                                    @break  
                                            @endswitch

                                            
                                        </td>
                                        <td>
                                            @switch($value->inventory_kardexable_type)
                                                @case($models[0])
                                                    {{ optional($value->inventory_kardexable)->series.'-'.optional($value->inventory_kardexable)->number }}
                                                    @break
                                                @case($models[1])
                                                    {{ optional($value->inventory_kardexable)->series.'-'.optional($value->inventory_kardexable)->number }}
                                                    @break
                                                @case($models[2])
                                                    {{ optional($value->inventory_kardexable)->series.'-'.optional($value->inventory_kardexable)->number }}
                                                    @break
                                                @case($models[3])
                                                    {{"-"}}                                                 
                                                    @break  
                                            @endswitch
 
                                        </td>
                                        <td>
                                            @switch($value->inventory_kardexable_type) 

                                                @case($models[0])
                                                    {{ ($value->quantity > 0) ?  $value->quantity:"-"}}
                                                    @break

                                                @case($models[1])
                                                    {{  $value->quantity }}                                                    
                                                    @break 
                                                    
                                                @case($models[3])
                                                    {{ ($value->inventory_kardexable->type == 1) ? $value->quantity : "-" }}                                                    
                                                    @break  

                                                @default
                                                    {{"-"}}                                                 
                                                    @break  
                                            @endswitch
                                        </td>
                                        <td>
                                        
                                            @switch($value->inventory_kardexable_type) 
                                                @case($models[0])
                                                    {{ ($value->quantity < 0) ?  $value->quantity:"-" }}                                                    
                                                    @break 
                                                @case($models[2])
                                                    {{  $value->quantity }}                                                    
                                                    @break      
                                                @case($models[3])
                                                    {{ ($value->inventory_kardexable->type == 2 || $value->inventory_kardexable->type == 3) ? $value->quantity : "-" }}                                                    
                                                    @break  
                                                @default
                                                    {{"-"}}                                                 
                                                    @break  
                                            @endswitch
                                        
                                        </td>
                                        @php                  
                                            $balance += $value->quantity;    
                                        @endphp
                                        <td>{{number_format($balance, 4)}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper">
                                {{-- {{ $reports->appends(['search' => Session::get('form_document_list')])->render()  }} --}}
                                {{-- {{$reports->links()}} --}}
                            </div>
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
