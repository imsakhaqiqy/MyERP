<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

use App\Category;
use App\Family;
class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(\Auth::user()->can('category-module'))
        {
            $categories = Category::all();
            return view('category.index')
             ->with('categories', $categories);
        }else{
            return view('403');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(\Auth::user()->can('create-category-module'))
        {
            $category = Category::all();
            $code_fix = '';
            if(count($category) > 0)
            {
                $code_category = Category::all()->max('id');
                $sub_str = $code_category+1;
                $code_fix = 'CAT0'.$sub_str;
            }else
            {
                $code_category = count($category)+1;
                $code_fix = 'CAT0'.$code_category;
            }
            $family = Family::lists('name','id');
            return view('category.create')
                ->with('family',$family)
                ->with('code_fix',$code_fix);
        }else{
            return view('403');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request)
    {
        $category = new Category;
        $category->code = strtoupper(str_replace(' ', '',$request->code));
        $category->name = $request->name;
        $category->family_id = $request->family_id;
        $category->save();
        return redirect('category')
        ->with('successMessage', 'Category has been added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::findOrFail($id);
        return view('category.show')
            ->with('category', $category);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(\Auth::user()->can('edit-category-module'))
        {
            $category = Category::findOrFail($id);
            $family = Family::lists('name','id');
            return view('category.edit')
             ->with('category', $category)
             ->with('family',$family);
        }else{
            return view('403');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        $category = Category::find($id);
        $category->code = strtoupper(str_replace(' ', '',$request->code));
        $category->name = $request->name;
        $category->family_id = $request->family_id;
        $category->save();
        return redirect('category/'.$id.'/edit')
            ->with('successMessage', 'Category has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $category = Category::findOrFail($request->category_id);
        $category->delete();
        return redirect('category')
            ->with('successMessage', 'Category has been deleted');
    }

}
