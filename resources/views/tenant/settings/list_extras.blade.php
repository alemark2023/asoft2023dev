@extends('tenant.layouts.app')

@section('content')
<div class="page-header pr-0">
    <h2>
        <a href="/dashboard">
            <i class="fas fa-home"></i>
        </a>
    </h2>
    <ol class="breadcrumbs">
        <li class="active">
            <span>Dashboard</span>
        </li>
        <li>
            <span class="text-muted">Extras</span>
        </li>
    </ol>
</div>

<div class="row">
    <div class="col-6 col-md-3 mb-4">
        <section class="card mt-4">
            <header class="card-header bg-{{in_array('hotels', $vc_modules) ? 'primary' : 'dark'}}">
                <div class="card-header-icon">
                    <i class="fas fa-hotel"></i>
                </div>
            </header>
            <div class="card-body text-center">
                <h3 class="font-weight-semibold mt-3 text-center">Hoteles</h3>

                <p class="text-center">Gestiona un edificio completo, sus pisos, habitaciones, caracteristicas de cada una y sus precios.</p>
                <span class="badge badge-{{in_array('hotels', $vc_modules) ? 'success' : 'default'}}">
                    {{in_array('hotels', $vc_modules) ? 'Activo' : 'Inactivo'}}
                </span>
                @if(!in_array('hotels', $vc_modules))
                    <small class="text-muted">Debe consultar con su administrador para poder habilitarlo</small>
                @endif
            </div>
        </section>
    </div>
    <div class="col-6 col-md-3 mb-4">
        <section class="card mt-4">
            <header class="card-header bg-{{in_array('documentary-procedure', $vc_modules) ? 'primary' : 'dark'}}">
                <div class="card-header-icon">
                    <i class="fas fa-archive"></i>
                </div>
            </header>
            <div class="card-body text-center">
                <h3 class="font-weight-semibold mt-3 text-center">Tramite documentario</h3>
                <p class="text-center">Los documentos puede pasar por varias etapas y ser aprobados en cada una de ellas.</p>
                <span class="badge badge-{{in_array('documentary-procedure', $vc_modules) ? 'success' : 'default'}}">
                    {{in_array('documentary-procedure', $vc_modules) ? 'Activo' : 'Inactivo'}}
                </span>
                <br>
                @if(!in_array('documentary-procedure', $vc_modules))
                    <small class="text-muted">Debe consultar con su administrador para poder habilitarlo</small>
                @endif
            </div>
        </section>
    </div>
    <div class="col-6 col-md-3 mb-4">
        <section class="card mt-4">
            <header class="card-header bg-{{in_array('digemid', $vc_modules) ? 'primary' : 'dark'}}">
                <div class="card-header-icon">
                    <i class="fas fa-book-medical"></i>
                </div>
            </header>
            <div class="card-body text-center">
                <h3 class="font-weight-semibold mt-3 text-center">Farmacia</h3>
                <p class="text-center">Compara tus productos con los del listado de digemid y exporta tu listado para tenerlos actualizado.</p>
                <span class="badge badge-{{in_array('digemid', $vc_modules) ? 'success' : 'default'}}">
                    {{in_array('digemid', $vc_modules) ? 'Activo' : 'Inactivo'}}
                </span>
                @if(!in_array('digemid', $vc_modules))
                    <small class="text-muted">Debe consultar con su administrador para poder habilitarlo</small>
                @endif
            </div>
        </section>
    </div>
</div>
@endsection
