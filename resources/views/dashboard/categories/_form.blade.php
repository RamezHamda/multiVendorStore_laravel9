@if($errors->any())
<div class="alert alert-danger">
    <h3>Error Occured!</h3>
    <ul>
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="form-group">
    <x-form.input class="form-control-lg" name="name" :value="$category->name" />
</div>

<div class="form-group">
    <label for="name">Category Parent</label>
    <select name="parent_id" class="form-control">
        <option value="">Primary Category</option>
        @foreach ($parents as $parent)
            <option value="{{ $parent->id }}" @selected(old('parent_id',$category->parent_id) == $parent->id)>{{ $parent->name }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <x-form.textarea label='Description' name="description" :value="$category->description" />
</div>

<div class="form-group">
    <x-form.label >Image</x-form.label>
    <input type="file" class="form-control" name="image" accept="image/*">
    <img class="mt-2" src="{{asset('uploads/' .$category->image)}}" alt="" height="40">
</div>

<div class="form-group">
    {{-- <label>Status</label> --}}
    <x-form.label :for='$category->id' >SSS</x-form.label>
    <div>
        <x-form.radio name="status" :id='$category->id' :checked="$category->status" :options="['active' => 'Active', 'archived' => 'Archived']" />
    </div>
</div>

<button type="submit" class="btn btn-primary">{{$button_label ?? 'Save'}}</button>
