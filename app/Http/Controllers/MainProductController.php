<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\StoreMainProductRequest;
use App\Http\Requests\UpdateMainProductRequest;
use App\Category;
use App\Unit;
use App\Family;
use App\MainProduct;
use App\Product;

class MainProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     protected $product_image = NULL;
     protected $product_image_to_be_deleted = '';

    public function index()
    {
        if(\Auth::user()->can('product-module'))
        {
            return view('main_product.index');
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
        if(\Auth::user()->can('create-product-module'))
        {
            $unit_options = Unit::lists('name', 'id');
            $family_options = Family::lists('name', 'id');
            return view('main_product.create')
                ->with('unit_options', $unit_options)
                ->with('family_options', $family_options);
        }else{
            return view('403');
        }
    }

    public function callCategory(Request $request)
    {
        if($request->ajax()){
            $category = \DB::table('categories')->where('family_id',$request->family)->get();
            foreach ($category as $key) {
                $results[]="<option value=".$key->id.">".$key->name."</option>";
            }
            return response()->json($results);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMainProductRequest $request)
    {
        if($request->hasFile('image')){
            $this->upload_process($request);
        }

        $main_product = New MainProduct;
        $main_product->name = $request->code;
        $main_product->image = $this->product_image;
        $main_product->family_id = $request->family_id;
        $main_product->category_id = $request->category_id;
        $main_product->unit_id = $request->unit_id;
        $main_product->save();

        //Block Update product code
        $product_id = $main_product->id;
        $category_id = $request->category_id;
        $family_id = $request->family_id;
        $selected_category = \DB::table('categories')->where('id',$category_id)->first()->code;
        $selected_family = \DB::table('families')->where('id',$family_id)->first()->code;
        $product_code = $selected_category.'-'.$selected_family.'-'.$product_id;
        $update = \DB::table('main_products')->where('id', $product_id)->update(['code'=>$product_code]);
        //ENDBlock Update product code

        $mulai_dari = $request->mulai_dari;
        $sebanyak = $request->sebanyak;
        for($mulai_dari; $mulai_dari <= $sebanyak; $mulai_dari++ ){
            $sub_product[$mulai_dari] = New Product;
            $sub_product[$mulai_dari]->name = $request->code.'.'.'0'.$mulai_dari;
            $sub_product[$mulai_dari]->stock = 0;
            $sub_product[$mulai_dari]->minimum_stock = 0;
            $sub_product[$mulai_dari]->description = $request->deskripsi;
            $sub_product[$mulai_dari]->main_product_id = $main_product->id;
            $sub_product[$mulai_dari]->save();
        }

        return redirect('main-product/'.$main_product->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $main_product = MainProduct::findOrFail($id);
        $product = \DB::table('products')->where('main_product_id',$id)->get();
        return view('main_product.show')
            ->with('main_product',$main_product)
            ->with('product',$product);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(\Auth::user()->can('edit-product-module'))
        {
            $category_options = Category::lists('name', 'id');
            $unit_options = Unit::lists('name', 'id');
            $family_options = Family::lists('name', 'id');
            $main_product = MainProduct::findOrFail($id);
            $product = \DB::table('products')->where('main_product_id',$id)->get();
            return view('main_product.edit')
                ->with('category_options', $category_options)
                ->with('unit_options', $unit_options)
                ->with('family_options', $family_options)
                ->with('main_product',$main_product)
                ->with('product',$product);
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
    public function update(UpdateMainProductRequest $request, $id)
    {
        $main_product = MainProduct::findOrFail($id);
        if($request->hasFile('image')){
            //if there is an uploaded image, fire the upload process,set the new product image name
            // and collect this product image name (to be deleted from the server).
            $this->upload_process($request);
            $this->product_image_to_be_deleted = $main_product->image;
        }
        else{
            //no image uploaded, it means the product image name stays still
            $this->product_image = $main_product->image;
        }

        $main_product->name = $request->name;
        $main_product->image = $this->product_image;
        $main_product->family_id = $request->family_id;
        $main_product->category_id = $request->category_id;
        $main_product->unit_id = $request->unit_id;
        $main_product->save();

        //Block Update product code
        $product_id = $main_product->id;
        $category_id = $request->category_id;
        $family_id = $request->family_id;
        $selected_category = \DB::table('categories')->where('id',$category_id)->first()->code;
        $selected_family = \DB::table('families')->where('id',$family_id)->first()->code;
        $product_code = $selected_category.'-'.$selected_family.'-'.$product_id;
        $update = \DB::table('main_products')->where('id', $product_id)->update(['code'=>$product_code]);
        //ENDBlock Update product code

        $product = [];
        $description = $request->description;
        foreach ($request->id as $key => $value) {
            \DB::table('products')->where('id',$request->id[$key])->update(['name'=>$request->child_code_hidden[$key],'stock'=>$request->stock[$key],'minimum_stock'=>$request->stock_minimum[$key],'description'=>$description]);
        }

        //delete old product image and the thumbnail from the server if any
        \File::delete(public_path().'/img/products/'.$this->product_image_to_be_deleted);
        \File::delete(public_path().'/img/products/thumb_'.$this->product_image_to_be_deleted);


        return redirect('main-product/'.$id.'/edit')
        ->with('successMessage', "Product has been updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $main_product = MainProduct::findOrFail($request->main_product_id);
        $image_file_name = $main_product->image;
        //delete product from database
        $main_product->delete();
        \DB::table('products')->where('main_product_id',$request->main_product_id)->delete();
        //delete image file and the thumbnail from the server if exists
        $image_file_location = public_path().'/img/products/'.$image_file_name;
        $thumbnail_location = public_path().'/img/products/thumb_'.$image_file_name;
        \File::delete($image_file_location);
        \File::delete($thumbnail_location);
        return redirect('main-product')
         ->with('successMessage', 'Main Product has been deleted');
    }

    protected function upload_process(Request $request){
        $upload_directory = public_path().'/img/products/';
        $extension = $request->file('image')->getClientOriginalExtension();
        $product_image_to_be_inserted = time().'.'.$extension;
        $this->product_image = $product_image_to_be_inserted;
        $save_image = \Image::make($request->image)->save($upload_directory.$product_image_to_be_inserted);
        //make the thumbnail
        $thumbnail = \Image::make($request->image)->resize(171,180)->save($upload_directory.'thumb_'.$this->product_image);
        //free the memory
        $save_image->destroy();

    }

    public function store_product(Request $request)
    {
        $product = [];
        foreach ($request->id as $key => $value) {
            \DB::table('products')->where('id',$request->id[$key])->update(['stock'=>$request->stock[$key],'minimum_stock'=>$request->stock_minimum[$key]]);
            // $product[$key] = [
            //     'id' => $request->id[$key],'stock' => $request->stock[$key]
            // ];
        }


        //$product->save();
        return redirect('main-product/'.$request->parent_id.'/show')
            ->with('successMessage', "Product has been added");
    }

    public function update_product(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $product->name = $request->name;
        $product->description = $request->description;
        $product->stock = $request->stock;
        $product->minimum_stock = $request->minimum_stock;
        $product->save();
        return redirect('main-product/'.$product->main_product_id)
            ->with('successMessage','has been updated');
    }

    public function destroy_product(Request $request)
    {
        $product = Product::findOrFail($request->sub_product_id_delete);
        $product->delete();
        return redirect('main-product/'.$request->main_product_id_delete)
            ->with('successMessage','sub product has been deleted');
    }

    public function show_product($id)
    {
        $main_product = MainProduct::findOrFail($id);
        $product = \DB::table('products')->where('main_product_id',$id)->get();
        return view('main_product.show_main_product')
            ->with('main_product',$main_product)
            ->with('product',$product);
    }

    public function product_available(Request $request)
    {
        if(\Auth::user()->can('product-available'))
        {
            $main_products = MainProduct::get();
            $data_main_products = [];
            foreach ($main_products as $mp) {
                $data_main_products [] = [
                    'id'=>$mp->id,
                    'code'=>$mp->code,
                    'description'=>MainProduct::findOrFail($mp->id)->product->first()->description,
                    'name'=>$mp->name,
                    'image'=>$mp->image,
                    'sum'=>MainProduct::findOrFail($mp->id)->product->sum('stock'),
                    'family'=>MainProduct::findOrFail($mp->id)->family->name,
                    'category'=>MainProduct::findOrFail($mp->id)->category->name,
                    'unit'=>MainProduct::findOrFail($mp->id)->unit->name,
                    'sub_products'=>MainProduct::findOrFail($mp->id)->product,
                ];
            }
            return view('product.index_product_available')
                ->with('data_main_products',$data_main_products);
        }else{
            return view('403');
        }
    }

    public function product_all(Request $request)
    {
        if(\Auth::user()->can('product-all'))
        {
            $main_products = MainProduct::get();
            $data_main_products = [];
            foreach ($main_products as $mp) {
                $data_main_products [] = [
                    'id'=>$mp->id,
                    'code'=>$mp->code,
                    'description'=>MainProduct::findOrFail($mp->id)->product->first()->description,
                    'name'=>$mp->name,
                    'image'=>$mp->image,
                    'sum'=>MainProduct::findOrFail($mp->id)->product->sum('stock'),
                    'family'=>MainProduct::findOrFail($mp->id)->family->name,
                    'category'=>MainProduct::findOrFail($mp->id)->category->name,
                    'unit'=>MainProduct::findOrFail($mp->id)->unit->name,
                    'sub_products'=>MainProduct::findOrFail($mp->id)->product,
                ];
            }
            return view('product.index_product_all')
                ->with('data_main_products',$data_main_products);
        }else{
            return view('403');
        }
    }

}
