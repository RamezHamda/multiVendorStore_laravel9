@extends('layouts.dashboard')

@section('title','Categories')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Categories</li>
@endsection

@section('content')

<div class="d-flex mb-4">
@can('categories.create')
<a href="{{route('dashboard.categories.create')}}" class="btn btn-primary mr-3">Add New Category</a>
@endcan

@can('categories.delete.force')
<a href="{{ route('dashboard.categories.trash') }}" class="btn btn-danger">Trash</a>
@endcan

</div>
<x-alert type='success' />
<x-alert type='danger' />

<form action="{{ URL::current() }}" method="get" class="d-flex justify-content-between mb-4">
    <x-form.input name="name" placeholder="Name" class="mx-2" :value="request('name')" />
    <select name="status" class="form-control mx-2">
        <option value="">All</option>
        <option value="active" @selected(request('status') == 'active')>Active</option>
        <option value="archived" @selected(request('status') == 'archived')>Archived</option>
    </select>
    <button class="btn btn-dark mx-2">Filter</button>
</form>


<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Image</th>
            <th>ID</th>
            <th>Name</th>
            <th>Parent</th>
            <th>Products #</th>
            <th>Status</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
            @forelse ($categories as $category)
                <tr>
                    <td><img src="{{asset('uploads/' .$category->image)}}" alt="" height="40"></td>
                    <td>{{$category->id}}</td>
                    <td><a href="{{ route('dashboard.categories.show', $category->id) }}">{{ $category->name }}</a></td>
                    <td>{{$category->parent->name}}</td>
                    <td>{{$category->products_number}}</td>
                    <td>{{$category->status}}</td>
                    <td>{{$category->created_at}}</td>
                    <td>
                        <form action="{{route('dashboard.categories.destroy',$category->id)}}" method="post">
                            @can('categories.update')
                            <a class="btn btn-sm btn-outline-success" href="{{route('dashboard.categories.edit',$category->id)}}">Edit</a>
                            @endcan

                            @csrf
                            @method('DELETE')
                            @can('categories.delete')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            @endcan
                        </form>
                    </td>
                </tr>
                @empty
                <td colspan="8">No Date Found</td>
            @endforelse

    </tbody>
</table>

{{$categories->withQueryString()->links('pagination::bootstrap-4')}}
@endsection
