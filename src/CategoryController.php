<?php

namespace QuetzalArc\Admin\Category;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use QuetzalArc\Admin\Category\Category;

class CategoryController extends Controller
{
    protected $request;

    protected $search = [
        'query' => '',
        'sort' => ['name', 'asc']
    ];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        if (!is_null($this->request->input('query'))){
            $this->search = array_set($this->search, 'query', $this->request->input('query'));
        }

        if (!is_null($this->request->input('sort'))) {
            $this->search = array_set($this->search, 'sort', $this->request->input('sort'));
        }

        $categories = Category::where(function ($query) {
            return $query->where('name', 'like', '%'.$this->search['query'].'%')
                ->orwhere('description', 'like', '%'.$this->search['query'].'%');
        })
        ->orderBy($this->search['sort'][0], $this->search['sort'][1])
        ->paginate(25);

        $categoriesList = Category::select('id', 'name')
            ->where('parent_id', null)
            ->orderBy('name', 'asc')
            ->get();

        $categories->appends($this->search);

        return view('admin-category::category', compact(
            'categories', 'categoriesList'
        ));
    }

    public function store()
    {
        $rules = [
            'name' => 'required|max:255'
        ];

        $this->validate($this->request, $rules);

        $category = new Category;
        $category->name = $this->request->name;
        $category->slug = (empty($this->request->slug)) ? str_slug($this->request->name) : $this->request->slug;
        $category->parent_id = ($this->request->parent == 0) ? null : $this->request->parent;
        $category->description = (empty($this->request->description)) ? null : $this->request->description;
        $category->save();

        session()->flash('success', 'New category saved.');

        return redirect('/admin/categories');
    }

    public function edit($id)
    {
        $category = Category::find($id);

        if (is_null($category)) {
            session()->flash('error', 'Category not found.');

            return redirect('/admin/categories');
        }

        $categoriesList = Category::select('id', 'name')
            ->where('id', '!=', $id)
            ->where('parent_id', null)
            ->orderBy('name', 'asc')
            ->get();

        return view('admin-category::category_edit', compact(
            'category', 'categoriesList'
        ));
    }

    public function update($id)
    {
        $rules = [
            'name' => 'required|max:255'
        ];

        $this->validate($this->request, $rules);

        $category = Category::find($id);
        
        if (is_null($category)) {
            session()->flash('error', 'Category not found.');

            return redirect('/admin/categories');
        }

        $category->name = $this->request->name;
        $category->slug = $this->request->slug;
        $category->parent_id = ($this->request->parent == 0) ? null : $this->request->parent;
        $category->description = $this->request->description;
        $category->save();

        session()->flash('success', 'Category updated.');

        return redirect('/admin/categories');
    }

    public function delete($id)
    {
        $category = Category::find($id);

        if (is_null($category)) {
            session()->flash('error', 'Category not found.');

            return redirect('/admin/categories');
        }

        $category->children()->update([
            'parent_id' => null
        ]);

        $category->delete();

        session()->flash('success', 'Category deleted.');

        return redirect('/admin/categories');
    }
}
