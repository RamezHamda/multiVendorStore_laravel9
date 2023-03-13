@extends('layouts.dashboard')

@section('title', 'Categories')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Categories</li>
    <li class="breadcrumb-item active">Add Category</li>
@endsection

@section('content')

    <form action="{{ route('dashboard.categories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        @include('dashboard.categories._form',[
            'button_label' => 'Create'
        ])
    </form>

@endsection
