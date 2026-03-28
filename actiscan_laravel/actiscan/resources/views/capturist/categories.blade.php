@extends('layouts.capturist')

@section('title', 'Categorias de Activos')
@section('page', 'categories')
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
            <input class="auth-input" id="categoriesSearchInput" type="text" placeholder="Filtrar por nombre o descripcion" style="margin: 0;">
            <button class="filter-btn" id="categoriesSearchButton" type="button">Aplicar filtro</button>
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
            <tbody id="categoriesTableBody"></tbody>
        </table>
    </div>
@endsection
