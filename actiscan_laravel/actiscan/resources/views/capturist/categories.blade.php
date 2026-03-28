@extends('layouts.capturist')

@section('title', 'Categorias de Activos')
@section('nav_categories', 'active')

@section('content')
    <div class="page-toolbar">
        <div>
            <h1 class="page-title">Categorias de Activos</h1>
            <p class="page-subtitle">Gestion y clasificacion de tipos de activos fijos.</p>
        </div>

        <a class="soft-button" href="{{ route('capturist.categories.create') }}">
            <i class="fa-solid fa-plus"></i>
            <span>Nueva categoria</span>
        </a>
    </div>

    <div class="table-card">
        <div class="table-tools compact">
            <input class="auth-input" type="text" placeholder="Filtrar por nombre o descripcion" style="margin: 0;">
            <button class="filter-btn" type="button">Filtros avanzados</button>
        </div>

        <table class="simple-table">
            <thead>
                <tr>
                    <th>Icono</th>
                    <th>Categoria</th>
                    <th>Descripcion</th>
                    <th>Total activos</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><i class="fa-solid fa-laptop"></i></td>
                    <td><span class="badge-soft">Computadora</span></td>
                    <td>Equipos de computo portatiles y de escritorio.</td>
                    <td>55</td>
                    <td class="option-icons">
                        <a href="{{ route('capturist.categories.create') }}" title="Editar categoria"><i class="fa-regular fa-pen-to-square"></i></a>
                    </td>
                </tr>
                <tr>
                    <td><i class="fa-solid fa-server"></i></td>
                    <td><span class="badge-soft">Servidor</span></td>
                    <td>Servidores y equipos de almacenamiento.</td>
                    <td>43</td>
                    <td class="option-icons">
                        <a href="{{ route('capturist.categories.create') }}" title="Editar categoria"><i class="fa-regular fa-pen-to-square"></i></a>
                    </td>
                </tr>
                <tr>
                    <td><i class="fa-solid fa-network-wired"></i></td>
                    <td><span class="badge-soft">Red</span></td>
                    <td>Equipos de networking y comunicaciones.</td>
                    <td>22</td>
                    <td class="option-icons">
                        <a href="{{ route('capturist.categories.create') }}" title="Editar categoria"><i class="fa-regular fa-pen-to-square"></i></a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
