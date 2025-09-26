<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Category;
use App\Models\Product;
use App\Models\Label;
use Exception;
class FrontPageController extends Controller
{
    public function menuCategory()
    {
        try {
            $categories = Category::select('id', 'title', 'slug', 'category_heading', 'description', 'image')
                ->where('status', 'on')
                ->orderBy('id', 'desc')
                ->get();

            if ($categories->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'No categories found.',
                    'data'    => []
                ], 200);
            }
            $categories->transform(function ($category) {
                if (!empty($category->image)) {
                    $category->image = asset('images/category/thumb/' . $category->image);
                } else {
                    $category->image = null;
                }
                return $category;
            });
            return response()->json([
                'success' => true,
                'message' => 'Categories fetched successfully.',
                'data'    => $categories
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }


    public function categoryProductList($slug)
    {
        try {
            $category = Category::select('id', 'title', 'slug', 'category_heading', 'description')
                ->where('slug', $slug)
                ->firstOrFail();
            $products = Product::with(['images' => function ($query) {
                    $query->select('id', 'product_id', 'image_path')
                        ->orderBy('sort_order')
                        ->limit(1);
                }])
                ->select('id', 'title', 'slug', 'product_description')
                ->where('category_id', $category->id)
                ->get();
            $products->transform(function ($product) {
                $product->product_description = $this->stripInlineStyles($product->product_description);
                if ($product->images->isNotEmpty()) {
                    $filename = $product->images[0]->image_path;
                    $product->image = asset('images/product/thumb/' . $filename);
                } else {
                    $product->image = null;
                }
                unset($product->images);
                return $product;
            });
            return response()->json([
                'success'  => true,
                'message'  => 'Products fetched successfully.',
                'category' => $category,
                'products' => $products
            ], 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found.'
            ], 404);

        } catch (\Exception $e) {
            Log::error('Category Product API Error: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function productDetails($slug)
    {
        try {
            $product = Product::with([
                'images' => function ($query) {
                    $query->select('id', 'product_id', 'image_path')->orderBy('sort_order');
                },
                'category' => function ($query) {
                    $query->select('id', 'title', 'slug');
                },
                'materials' => function ($query) {
                    $query->select('id', 'product_id', 'material');
                },
                'ingredients' => function ($query) {
                    $query->select('id', 'product_id', 'ingredient');
                },
                'specifications' => function ($query) {
                    $query->select('id', 'product_id', 'specification');
                },
                'additionalFeatures' => function ($query) {
                    $query->select('id', 'product_id', 'product_additional_featur_value');
                }
            ])
            ->select('id', 'title', 'slug', 'product_description', 'product_specification', 'video_id', 'meta_title', 'meta_description', 'category_id') 
            ->where('slug', $slug)
            ->where('product_status', 1)
            ->firstOrFail();

            $product->product_description = $this->stripInlineStyles($product->product_description);
            $product->product_specification = $this->stripInlineStyles($product->product_specification);
            $images = $product->images->map(function ($image) {
                return asset('images/product/large/' . $image->image_path);
            });
            $materials = $product->materials->pluck('material');
            $ingredients = $product->ingredients->pluck('ingredient');
            $specifications = $product->specifications->pluck('specification');
            $additional_features = $product->additionalFeatures->pluck('product_additional_featur_value');
            return response()->json([
                'success' => true,
                'message' => 'Product details fetched successfully.',
                'product' => [
                    'id' => $product->id,
                    'title' => $product->title,
                    'slug' => $product->slug,
                    'product_description' => $product->product_description,
                    'product_specification' => $product->product_specification,
                    'video_id' => $product->video_id,
                    'meta_title' => $product->meta_title,
                    'meta_description' => $product->meta_description,
                    'category' => $product->category,
                    'images' => $images,
                    'materials' => $materials,
                    'ingredients' => $ingredients,
                    'specifications' => $specifications,
                    'additional_features' => $additional_features
                ]
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found.'
            ], 404);

        } catch (\Exception $e) {
            Log::error('Product Details API Error: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. '.$e->getMessage()
            ], 500);
        }
    }

    public function homeProductList()
    {
        try {
            $labels = Label::where('slug', 'home-product')->firstOrFail(); 
            $home_product_label_id = $labels->id;
            $products = Product::with([
                    'category' => function ($query) {
                        $query->select('id', 'title', 'slug');
                    },
                    'images' => function ($query) {
                        $query->select('id', 'product_id', 'image_path')
                            ->orderBy('sort_order')
                            ->limit(1);
                    }
                ])
                ->select('id', 'title', 'slug', 'product_description', 'category_id', 'label_id')
                ->where('label_id', $home_product_label_id)
                ->take(8)
                ->get();
            $products->transform(function ($product) {
                $product->product_description = $this->stripInlineStyles($product->product_description);
                if ($product->images->isNotEmpty()) {
                    $filename = $product->images[0]->image_path;
                    $product->image = asset('images/product/thumb/' . $filename);
                } else {
                    $product->image = null;
                }

                unset($product->images);
                return $product;
            });

            return response()->json([
                'success'  => true,
                'message'  => 'Home Products fetched successfully.',
                'products' => $products
            ], 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found.'
            ], 404);

        } catch (\Exception $e) {
            Log::error('Product Product API Error: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }


    private function stripInlineStyles($html)
    {
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML(' ' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        $elements = $dom->getElementsByTagName('*');
        foreach ($elements as $el) {
            if ($el->hasAttribute('style')) {
                $el->removeAttribute('style');
            }
        }

        return $dom->saveHTML();
    }

}
