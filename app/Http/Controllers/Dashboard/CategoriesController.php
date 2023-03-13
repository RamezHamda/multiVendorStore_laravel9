<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\CategoryRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!Gate::allows('categories.view')){
            abort(403);
        }

        $categories = Category::select('*')
        // ->where('status','archived')
        // ->WhereColumn('created_at','<>','updated_at')   //DATE_FORMAT(created_at,'%Y-%m'))
        // ->whereYear('created_at', '=', '2022')
        ->where([
            ["status",'archived'],
            [DB::raw("Year(created_at)"),'2023']
            ])
        // ->where('status','archived')
        // ->whereYear('created_at', '=', '2022')
        ->paginate(5);

        // ->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m'))"),"2022-07");


        // $categories = Category::with('parent');
        // leftJoin('categories as parents', 'parents.id', '=', 'categories.parent_id')
        // ->select([
        //     'categories.*',
        //     'parents.name as parent_name'
        // ])
        // ->withCount([
        //     'products as products_number' => function($query) {
        //         $query->where('status', '=', 'active');
        //     }
        // ])
        // ->filter($request->query())
        // ->orderBy('categories.name')
        // ->paginate(5);

        return view('dashboard.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        Gate::authorize('categories.create');

        $parents = Category::all();
        $category = new Category();
        $name = 'Ramez';
        return view('dashboard.categories.create', compact('name', 'category', 'parents'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(Category::ruels());

        $request->merge([
            'slug' => Str::slug($request->post('name'))
        ]);

        $data = $request->except('image');
        $data['image'] = $this->uploadImage($request);
        $category = Category::create($data);

        return redirect()->route('dashboard.categories.index')->with('success', 'Category Created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        // if (Gate::denies('categories.view')) {
        //     abort(403);
        // }
        return view('dashboard.categories.show', [
            'category' => $category
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Gate::authorize('categories.update');
        
        $category = Category::findorFail($id);
        // Select * from Categories where id <> $id AND (parent_id is NULL OR parent_id <> $id)
        $parents = Category::where('id', '<>', $id)
            ->where(function ($query) use ($id) {
                $query->whereNull('parent_id')
                    ->orWhere('parent_id', "<>", $id);
            })->get();

        return view('dashboard.categories.edit', compact('category', 'parents'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, $id)
    {
        $category = Category::findOrFail($id);
        $old_image = $category->image;
        $data = $request->except('image');

        $new_image = $this->uploadImage($request);
        if($new_image){
            $data['image'] = $new_image;
        }

        $category->update($data);

        if($old_image && $new_image) {
            Storage::disk('uploads')->delete($old_image);
        }

        return Redirect::route('dashboard.categories.index')
            ->with('success', 'Catergoy Updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Gate::authorize('categories.delete');
        //الطريقة الأولى للحذف بخطوتين
        $category = Category::findOrFail($id);
        $category->delete();

        // الطريقة الثانية للحذف بخطوة واحدة
        // Category::destroy($id);

        return Redirect::route('dashboard.categories.index')
            ->with('danger', 'Category Deleted');
    }





    protected function uploadImage(Request $request)
    {
        if (!$request->hasFile('image')) {
            return;
        }

        $file = $request->file('image');
        $path = $file->store('categories', [
            'disk' => 'uploads'
        ]);
        return $path;
    }

    public function trash()
    {
        $categories = Category::onlyTrashed()->paginate();
        return view('dashboard.categories.trash', compact('categories'));
    }

    public function restore(Request $request, $id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->restore();

        return redirect()->route('dashboard.categories.trash')
            ->with('succes', 'Category restored!');
    }

    public function forceDelete($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->forceDelete();

        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        return redirect()->route('dashboard.categories.trash')
            ->with('succes', 'Category deleted forever!');
    }
}
