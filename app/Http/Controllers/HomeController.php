<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Category;
use App\Models\Subcategory;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $category = Category::distinct('title')->get();
        return view('home')->with('category',$category);
    }

    public function addProducts(Request $request)
    {
        $data=new Products();
        $data-> title=$request->get('title');
        $data-> description=$request->get('description');
        $data-> subcategory_id=$request->get('subcategory');
        $data-> price=$request->get('price');
        $data-> thumbnail=$request->get('thambnail');
        $data->save();
        return response()->json(
            [
                'message' => 'Data inserted successfully'
            ]
        );
    }

    public function productSearch()
    {
        // dd('hh');
        $products = Products::all();
        return datatables($products)->addColumn('action', function ($products) {
            return '<button class="btn btn-danger btn-sm" id="productDelete" data-id="'.$products->id.'">Delete</button>';
        })->toJson();
    }

    public function product_delete(Request $request){
        $deleteData = Products::find($request->id);
        $deleteData ->delete();
        return response()->json('success');
    }

    public function subCatSearch(Request $request)
    {
        $subCategory = Subcategory::where('category_id',$request->id)->distinct('title')->get();
        return response()->json( $subCategory);
    }
}
