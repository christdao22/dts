<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mall;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductSubCategory;
use App\Models\Store;
use App\Models\StoreType;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $store = $request->get('store');
        if ($store) {
            $store_products = Product::where('store_id', $store)->get();
            // if (!count($store_products)) {
            //     $this->populateProduct(Store::find($store));
            // }
        }

        $malls = Mall::all();
        $categories = StoreType::all();

        $selectedMalls = $request->get('malls') ? $request->get('malls') : [];

        $products = Product::query();
        $products->with(['sub_category', 'sub_category.category', 'store']);
        $perpage = 10;

        $products->leftJoin('product_sub_categories', 'products.sub_category_id', 'product_sub_categories.id');
        $products->leftJoin('product_categories', 'product_sub_categories.product_category_id', 'product_categories.id');
        $products->leftJoin('stores', 'products.store_id', 'stores.id');
        $products->leftJoin('malls', 'stores.mall_id', 'malls.id');

        if ($store) {
            $products->where('products.store_id', $store);
        }

        if (count($selectedMalls)) {
            $selectedMalls = array_map(function ($item) { return intval($item); }, $selectedMalls);
            $products->whereIn('stores.mall_id', $selectedMalls);
        }

        if ($request->has('search')) {
            $search = $request->get('search');
            $products->where(function ($q) use ($search) {
                $q->where('products.name', 'like', "%{$search}%")
                ->orWhere('products.product_code', 'like', "%{$search}%");
            });
        }
        if ($request->has('sort')) {
            $products->orderBy($request->get('sort'), $request->get('order'));
        }

        if ($request->has('per_page') && $request->get('per_page') != 'all') {
            $perpage = intval($request->get('per_page'));
        } elseif ($request->has('per_page') && $request->get('per_page') == 'all') {
            $perpage = $products->count();
        }

        $products = $products->select(['products.*', 'product_categories.name as product_category', 'stores.name as storename', 'malls.name as mallname', 'product_variants.is_in_stock'])->paginate($perpage)->withQueryString();

        return Inertia::render('Admin/Product/Index', [
            'products' => $products,
            'perpage' => $perpage,
            'sort' => $request->get('sort'),
            'order' => $request->get('order'),
            'search' => $request->get('search'),
            'filter_mall' => $selectedMalls,
        ]);
    }

    public function productByStore(Request $request)
    {
        // return $request->all();
        $store_type = $store_type = StoreType::where('type_desc', 'Stores')->first();
        $prod_query = $this->queryProduct($request, $store_type);
        $products = $prod_query['query'];
        $perpage = $prod_query['per_page'];
        $malls = $prod_query['malls'];
        $selectedMalls = $prod_query['selectedMalls'];
        $product_categories = $prod_query['product_categories'];
        $selectedCategories = $prod_query['selectedCategories'];
        // $products = $products->select(['products.*', 'product_categories.name as product_category', 'stores.name as storename', 'malls.name as mallname'])->get();
        $products = $products->select(['products.*', 'categories.category_desc as product_category', 'stores.store_name as storename', 'malls.mall_name as mallname', 'product_variants.is_in_stock'])
        ->groupby('products.product_name')
        ->distinct()
        ->paginate($perpage)->withQueryString();
        // return $products;

        return Inertia::render('Admin/Product/Store', [
            'products' => $products,
            'perpage' => $perpage,
            'sort' => $request->get('sort'),
            'order' => $request->get('order'),
            'search' => $request->get('search'),
            'malls' => $malls,
            'storename' => $request->get('store'),
            'product_categories' => $product_categories,
            'filter_categories' => $selectedCategories,
            'filter_malls' => $selectedMalls,
        ]);
    }

    public function productBySupermarket(Request $request)
    {
        $store_type = $store_type = StoreType::where('type_desc', 'Supermarket')->first();
        $prod_query = $this->queryProduct($request, $store_type);
        $products = $prod_query['query'];
        $perpage = $prod_query['per_page'];
        $malls = $prod_query['malls'];
        $selectedMalls = $prod_query['selectedMalls'];
        $selectedCategories = $prod_query['selectedCategories'];
        $product_categories = $prod_query['product_categories'];

        $products = $products->select(['products.*', 'categories.category_desc as product_category', 'stores.store_name as storename', 'malls.mall_name as mallname', 'product_variants.is_in_stock'])->groupby('products.product_name')->distinct()->paginate($perpage)->withQueryString();

        return Inertia::render('Admin/Product/Supermarket', [
            'products' => $products,
            'perpage' => $perpage,
            'sort' => $request->get('sort'),
            'order' => $request->get('order'),
            'search' => $request->get('search'),
            'malls' => $malls,
            'storename' => $request->get('store'),
            'product_categories' => $product_categories,
            'filter_categories' => $selectedCategories,
            'filter_malls' => $selectedMalls,
        ]);
    }

    public function populateProduct(Store $store)
    {
        // try {
        DB::beginTransaction();

        $morePage = true;
        $page = 1;
        while ($morePage) {
            $url = env('MALLDASH_API_URL')."product-variants/by-store/{$store->api_ref_id}";
            // $url = env('MALLDASH_API_URL').'product-variants/by-store/1';
            $response = Http::withOptions([
                'verify' => false,
            ])->get($url, [
                'page' => $page,
                'length' => 50,
            ]);
            if ($response->getStatusCode() == 200) {
                $result = $response->json();

                // if($result["links"]["next"]){
                    //     $morePage = true;
                    //     $page++;
                // }else{
                $morePage = false;
                // }

                $products = $result['data'];
                if (count($products)) {
                    foreach ($products as $product) {
                        $product_sub_cat = null;
                        $product_category = null;
                        if ($product['category_id']) {
                            $product_category = ProductCategory::where('api_ref_id', $product['category_id'])->first();
                            if (!$product_category) {
                                $product_category = ProductCategory::create([
                                    'api_ref_id' => $product['category_id'],
                                    'name' => $product['category_name'],
                                    'category_code' => $product['category_code'],
                                    'url_code' => $product['category_url_code'],
                                    'store_id' => $store->id,
                                ]);
                            }
                        }
                        if ($product['subcategory_id']) {
                            $product_sub_cat = ProductSubCategory::where('api_ref_id', $product['subcategory_id'])->first();
                            if (!$product_sub_cat) {
                                $product_sub_cat = ProductSubCategory::create([
                                    'api_ref_id' => $product['subcategory_id'],
                                    'name' => $product['subcategory_name'],
                                    'sub_category_code' => $product['subcategory_code'],
                                    'sub_url_code' => $product['subcategory_url_code'],
                                    'product_category_id' => $product_category->id,
                                ]);
                            }
                        }

                        Product::create([
                            'api_ref_id' => intval($product['id']),
                            'name' => $product['name'],
                            'product_code' => $product['item_code'],
                            'category_id' => $product_category ? $product_category->id : $product_category,
                            'sub_category_id' => $product_sub_cat ? $product_sub_cat->id : $product_sub_cat,
                            'store_id' => $store->id,
                            'price' => $product['price'],
                            'primary_variant' => $product['primary_variant'],
                            'size' => $product['product_size'],
                            'is_active' => $product['is_active'],
                            'is_in_stock' => $product['is_in_stock'],
                            'is_on_sale' => $product['is_on_sale'],
                            'tag' => $product['product_tags'],
                            'require_prescription' => $product['requires_prescription'],
                            'enable_instruction' => $product['enable_additional_instruction'],
                            'description' => $product['item_description'],
                            'image' => null,
                            'image_src' => $product['img_src'],
                            'max_sell' => $product['max_sell'],
                            'substitute_by_category' => $product['substitute_by_subcategory'],
                            'substitute_by_tag' => $product['substitute_by_tag'],
                            'sale_message' => $product['sale_msg'],
                        ]);
                    }
                }
            } else {
                $morePage = false;
            }
        }

        DB::commit();
        // }catch(\Exception $ex) {
        //     DB::rollBack();
        //     dd("error");
        // }
    }

    public function queryProduct(Request $request, StoreType $store_type)
    {
        $storename = $request->get('store');
        $store_id = $request->get('store_id');

        $selectedMalls = $request->get('malls') ? $request->get('malls') : [];
        $selectedCategories = $request->get('categories') ? $request->get('categories') : [];

        $store_ids = [];
        if ($storename) {
            // $stores = Store::where('store_name', $storename);
            $stores = DB::table('stores')
            ->where('stores.store_name', 'like', '%' . $storename . '%')
            ->where('is_active', 1);

            if (count($selectedMalls)) {
                $stores->whereIn('mall_id', $selectedMalls);
            }
            $stores = $stores->get();
            if (count($stores)) {
                foreach ($stores as $store) {
                    array_push($store_ids, $store->id);
                    $store_products = Product::where('store_id', $store->id)->get();
                    // if (!count($store_products)) {
                    //     $this->populateProduct($store);
                    // }
                }
            }
        }

        $product_categories = Category::whereIn('store_id', $store_ids)->select('*')->groupBy('category_desc')->get();
        // $product_categories = DB::table('category_product')->whereIn('store_id', $store_ids)->select('*')->groupBy('name')->get();

        $malls = Mall::select( 'id', 'uuid', 'mall_name', 'mall_code' )->get();
        $products = Product::query();
        // $products->with(['sub_category', 'sub_category.category', 'store']);
        $perpage = 10;

        $products->leftJoin('product_variants', 'product_variants.product_id', 'products.id');
        $products->leftJoin('subcategories', 'products.subcategory_id', 'subcategories.id');
        $products->leftJoin('categories', 'products.category_id', 'categories.id');
        $products->leftJoin('stores', 'products.store_id', 'stores.id');
        $products->leftJoin('malls', 'stores.mall_id', 'malls.id');
        $products->where('product_variants.is_active', 1);

        // $products->leftJoin('product_sub_categories', 'products.sub_category_id', 'product_sub_categories.id');
        // $products->leftJoin('product_categories', 'products.category_id', 'product_categories.category_id');
        // $products->leftJoin('stores', 'products.store_id', 'stores.id');
        // $products->leftJoin('malls', 'stores.mall_id', 'malls.id');

        if ($storename) {
            $products->where('stores.store_name', $storename);
        }

        if (count($selectedMalls)) {
            $selectedMalls = array_map(function ($item) { return intval($item); }, $selectedMalls);
            $products->whereIn('stores.mall_id', $selectedMalls);
        }

        if (count($selectedCategories)) {
            $selectedCategories = array_map(function ($item) { return intval($item); }, $selectedCategories);
            $products->whereIn('products.category_id', $selectedCategories);
        }

        // $products->where('stores.store_type_id', $store_type->id);
        if ($request->has('search')) {
            $search = $request->get('search');
            $products->where(function ($q) use ($search, $request) {
                $q->where('products.product_name', 'like', "%{$search}%")
                ->orWhere('products.id', 'like', "%{$search}%")
                ->orWhere('categories.category_desc', 'like', "%{$search}%")
                ->orWhere('malls.mall_name', 'like', "%{$search}%");

                if (!$request->has('store')) {
                $q->orWhere('stores.store_name', 'like', "%{$search}%");
                }

            });
        }
        if ($request->has('sort')) {
            $products->orderBy($request->get('sort'), $request->get('order'));
        }

        if ($request->has('per_page') && $request->get('per_page') != 'all') {
            $perpage = intval($request->get('per_page'));
        } elseif ($request->has('per_page') && $request->get('per_page') == 'all') {
            $perpage = $products->count();
        }

        foreach ($malls as $key => $mall) {
            $selected = $selectedMalls ? in_array($mall->id, $selectedMalls) : false;
            $malls[$key]['selected'] = $selected;
        }

        foreach ($product_categories as $key => $category) {
            $selected = $selectedCategories ? in_array($category->id, $selectedCategories) : false;
            $product_categories[$key]['selected'] = $selected;
        }

        return [
            'query' => $products,
            'per_page' => $perpage,
            'malls' => $malls,
            'selectedMalls' => $selectedMalls,
            'selectedCategories' => $selectedCategories,
            'product_categories' => $product_categories,
        ];
    }
}
