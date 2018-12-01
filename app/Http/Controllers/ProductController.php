<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

//form requests
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

use App\Product;
use App\Category;
use App\Unit;
use App\Family;

class ProductController extends Controller
{

    protected $product_image = NULL;
    protected $product_image_to_be_deleted = '';

    public function __construct(){
        $this->middleware('auth');
    }

    public function index()
    {
        /*if (auth()->user()->can('show.product')) {
            $categories = Category::all();
            return view('product.index')
                ->with('category_selections', $categories);
        }
        return abort(403);*/

        $categories = Category::all();
            return view('product.index')
                ->with('category_selections', $categories);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category_options = Category::lists('name', 'id');
        $unit_options = Unit::lists('name', 'id');
        $family_options = Family::lists('name', 'id');
        return view('product.create')
            ->with('category_options', $category_options)
            ->with('unit_options', $unit_options)
            ->with('family_options', $family_options);
    }

    public function store(StoreProductRequest $request)
    {
        // $product = new Product;
        // $product->name = $request->name;
        // $product->description = $request->description;
        // $product->stock = $request->stock;
        // $product->minimum_stock = $request->minimum_stock;
        // $product->main_product_id = $request->main_product_id;
        // $main_product = MainProduct::findOrFail($request->main_product_id);
        // $product->save();
        return redirect('home')
            ->with('successMessage', "Product has been added");
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('product.show')
            ->with('product', $product);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $category_options = Category::lists('name', 'id');
        $family_options = Family::lists('name', 'id');
        $unit_options = Unit::lists('name', 'id');
        return view('product.edit')
            ->with('product', $product)
            ->with('category_options', $category_options)
            ->with('family_options', $family_options)
            ->with('unit_options', $unit_options);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, $id)
    {



        $product = Product::findOrFail($id);
        if($request->hasFile('image')){
            //if there is an uploaded image, fire the upload process,set the new product image name
            // and collect this product image name (to be deleted from the server).
            $this->upload_process($request);
            $this->product_image_to_be_deleted = $product->image;
        }
        else{
            //no image uploaded, it means the product image name stays still
            $this->product_image = $product->image;
        }

        $product->name = $request->name;
        $product->category_id = $request->category_id;
        $product->family_id = $request->family_id;
        $product->unit_id = $request->unit_id;
        $product->image = $this->product_image;
        $product->stock = $request->stock;
        $product->minimum_stock = $request->minimum_stock;
        $product->save();

        //now update the product code of the last inserted product
        $category_id = $request->category_id;
        $family_id = $request->family_id;
        $category_code = \DB::table('categories')->where('id',$category_id)->first()->code;
        $family_code = \DB::table('families')->where('id',$family_id)->first()->code;
        $product_code = $category_code.'-'.$family_code.'-'.$id;
        $update = \DB::table('products')->where('id', $id)->update(['code'=>$product_code]);

        //delete old product image and the thumbnail from the server if any
        \File::delete(public_path().'/img/products/'.$this->product_image_to_be_deleted);
        \File::delete(public_path().'/img/products/thumb_'.$this->product_image_to_be_deleted);

        return redirect('product/'.$id.'/edit')
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
        $product = Product::findOrFail($request->product_id);
        $image_file_name = $product->image;
        //delete product from database
        $product->delete();
        //delete image file and the thumbnail from the server if exists
        $image_file_location = public_path().'/img/products/'.$image_file_name;
        $thumbnail_location = public_path().'/img/products/thumb_'.$image_file_name;
        \File::delete($image_file_location);
        \File::delete($thumbnail_location);
        return redirect('product')
         ->with('successMessage', 'Product has been deleted');
    }


    //function to upload image and set the properti product_image
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


}
