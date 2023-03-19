<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();

       return $products;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
    }
    public function getImages(){
       $path = public_path('images/products/');
       $files = File::files($path);
      
        dd($files->getPathname());
       

       
    
    //dd($files);
       return $files;
    }
 

    public function image(Request $request)
    {
       
         $product = Product::max('id');
         $image_id = $product + 1;
         $addName = $image_id.'.'.$request->image->extension();  
         
         $request->image->move(public_path('images/products'), $addName);
        return response([
               
            'message' => 'new image added successfully',
            'status'=>'success',
            'name'=>$addName
            
        ], 200);
    }

    public function updateImage(Request $request)
    {
       
         $addName = $request->id.'.'.$request->image->extension();  
         
         $request->image->move(public_path('images/products'), $addName);
        return response([
               
            'message' => 'new image added successfully',
            'status'=>'success'
            //'name'=>$addName
            
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
             // Validate the inputs
             $request->validate([
                'name' => 'required',
                'category' => 'required',
                'stock' => 'required',
                'description' => 'required',
                'price' => 'required|numeric|max:2001',
                'image_path' => 'required',
            ]);
    
            $product = new Product([
                "name" => $request->get('name'),
                'category' => $request->get('category'),
                "stock" => $request->get('stock'),
                'description' => $request->get('description'),
                'price' => $request->get('price'),
                'image_path' => $request->get('image_path')
               
            ]);
            $product->save();
            return response([   
                'message' => 'new product added successfully',
                'status'=>'success'
            ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);

        return $product;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        $request->validate([
            'name' => 'required',
          //  'category' => 'required',
            'stock' => 'required',
            'description' => 'required',
            'price' => 'required|numeric|max:2001',
          //  'image_path' => 'required',
        ]);

      Product::where('id',$id)->update([
            'name' => $request->name,
           // 'category' => $request->get('category'),
            'stock' => $request->stock,
            'description' => $request->description,
            'price' => $request->price
            //'image_path' => $request->get('image_path')
           
        ]);
        
        return response([   
            'message' => 'product updated successfully',
            'status'=>'success'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Product::find($id)->delete();

        return response([   
            'message' => ' product deleted successfully',
            'status'=>'success'
        ], 200);
    }

    public function getGarments()
   {
    $product = Product::where('category','1')->get();
    return $product;
   }

   public function getWatches()
   {
    $product = Product::where('category','2')->get();
    return $product;
   }

   public function getFootwears()
   {
    $product = Product::where('category','3')->get();
    return $product;
   }
}
