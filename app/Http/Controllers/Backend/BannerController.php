<?php
namespace App\Http\Controllers\Backend;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class BannerController extends Controller
{
    public function index(){
        $banner = Banner::orderBy('id', 'desc')->get();
        return view('backend.manage-banner.index', compact('banner'));
    }

    public function create(Request $request){
        $form ='
        <div class="modal-body">
            <form method="POST" action="'.route('manage-banner.store').'" accept-charset="UTF-8" enctype="multipart/form-data" id="addNewBanner">
                '.csrf_field().'
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="banner_title" class="form-label">Banner Title</label>
                            <input type="text" id="banner_title" name="banner_title" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="banner_image" class="form-label">Banner Image For Desktop *</label>
                            <input type="file" id="banner_image" name="banner_image" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="banner_image_mobile" class="form-label">Banner Image For Mobile *</label>
                            <input type="file" id="banner_image_mobile" name="banner_image_mobile" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="banner_path" class="form-label">Banner Path</label>
                            <input type="text" id="banner_path" name="banner_path" class="form-control">
                        </div>
                    </div>
                    
                    <!--<div class="mb-3 col-md-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="status" name="status">
                            <label class="form-check-label" for="status">Status</label>
                        </div>
                    </div>-->
                    
                    <div class="modal-footer pb-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
        ';
        return response()->json([
            'message' => 'Banner Form created successfully',
            'form' => $form,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'banner_title' => 'nullable|string|max:255',
            'banner_image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:6144',
            'banner_image_mobile' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:6144',
            'banner_path' => 'nullable|url|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $bannerTitleSlug = $request->input('banner_title') 
            ? Str::slug($request->input('banner_title'), '-') . '-himgiri' 
            : 'default-banner-himgiri';

        $timestamp = round(microtime(true) * 1000);
        $imagePath = public_path('images/banners');
        if (!file_exists($imagePath)) {
            mkdir($imagePath, 0755, true);
        }

        $desktopImageName = null;
        $mobileImageName = null;

        if ($request->hasFile('banner_image')) {
            $desktopImage = $request->file('banner_image');
            $desktopImageName = $bannerTitleSlug . '-desktop-' . $timestamp . '.webp';

            $desktopImageResized = Image::make($desktopImage->getRealPath())
                ->resize(1200, 600, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->encode('webp', 80);
            $desktopImageResized->save($imagePath . '/' . $desktopImageName);
        } 

        if ($request->hasFile('banner_image_mobile')) {
            $mobileImage = $request->file('banner_image_mobile');
            $mobileImageName = $bannerTitleSlug . '-mobile-' . $timestamp . '.webp';

            $mobileImageResized = Image::make($mobileImage->getRealPath())
                ->resize(600, 800, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->encode('webp', 80);
            $mobileImageResized->save($imagePath . '/' . $mobileImageName);
        }
        $banner = Banner::create([
            'title' => $request->input('banner_title'),
            'image_path_desktop' => $desktopImageName,
            'image_path_mobile' => $mobileImageName,
            'link_desktop' => $request->input('banner_path'),
            'status' => true,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Banner created successfully!',
            'data' => $banner,
        ]);
    }


    public function edit(Request $request, $id){
        $blogCategoryId = $request->input('blogCategoryId'); 
        $banner_row = Banner::findOrFail($id);
        $form ='
        <div class="modal-body">
            <form method="POST" action="'.route('manage-banner.update', ['manage_banner' => $banner_row->id]).'" accept-charset="UTF-8" enctype="multipart/form-data" id="editBanner">
                '.csrf_field().'
                <input type="hidden" name="_method" value="PUT">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="banner_title" class="form-label">Banner Title</label>
                            <input type="text" id="banner_title" name="banner_title" class="form-control" value="'.$banner_row->title.'">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="banner_image" class="form-label">Banner Image *</label>
                            <input type="file" id="banner_image" name="banner_image" class="form-control">
                            <img src="'.asset('images/banners/'.$banner_row->image_path_desktop).'" class="img-thumbnail" style="width: 150px; height: 70px;" alt="'.$banner_row->title.'">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="banner_image_mobile" class="form-label">Banner Image Mobile *</label>
                            <input type="file" id="banner_image_mobile" name="banner_image_mobile" class="form-control">
                            <img src="'.asset('images/banners/'.$banner_row->image_path_mobile).'" class="img-thumbnail" style="width: 150px; height: 70px;" alt="'.$banner_row->title.'">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="banner_path" class="form-label">Banner Path</label>
                            <input type="text" id="banner_path" name="banner_path" class="form-control" value="'.$banner_row->link_desktop.'">
                        </div>
                    </div>
                    
                    <!--<div class="mb-3 col-md-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="status" name="status">
                            <label class="form-check-label" for="status">Status</label>
                        </div>
                    </div>-->
                    
                    <div class="modal-footer pb-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
        ';
        return response()->json([
            'message' => 'Banner Form created successfully',
            'form' => $form,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'banner_title' => 'nullable|string|max:255',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:6144',
            'banner_image_mobile' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:6144',
            'banner_path' => 'required|url|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $banner = Banner::findOrFail($id);
        $bannerTitleSlug = Str::slug($request->input('banner_title'), '-');
        $timestamp = round(microtime(true) * 1000);
        $imagePath = public_path('images/banners');

        if (!file_exists($imagePath)) {
            mkdir($imagePath, 0755, true);
        }

        if ($request->hasFile('banner_image')) {
            if ($banner->image_path_desktop && file_exists($imagePath . '/' . $banner->image_path_desktop)) {
                unlink($imagePath . '/' . $banner->image_path_desktop);
            }

            $desktopImage = $request->file('banner_image');
            $desktopImageName = $bannerTitleSlug . '-desktop-' . $timestamp . '.webp';

            Image::make($desktopImage->getRealPath())
                ->resize(1200, 600, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->encode('webp', 80)
                ->save($imagePath . '/' . $desktopImageName);

            $banner->image_path_desktop = $desktopImageName;
        }

        if ($request->hasFile('banner_image_mobile')) {
            if ($banner->image_path_mobile && file_exists($imagePath . '/' . $banner->image_path_mobile)) {
                unlink($imagePath . '/' . $banner->image_path_mobile);
            }

            $mobileImage = $request->file('banner_image_mobile');
            $mobileImageName = $bannerTitleSlug . '-mobile-' . $timestamp . '.webp';

            Image::make($mobileImage->getRealPath())
                ->resize(600, 800, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->encode('webp', 80)
                ->save($imagePath . '/' . $mobileImageName);

            $banner->image_path_mobile = $mobileImageName;
        }

        // Update other fields
        $banner->title = $request->input('banner_title');
        $banner->link_desktop = $request->input('banner_path');
        $banner->status = true;
        $banner->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Banner updated successfully!',
            'data' => $banner,
        ]);
    }

    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);
        $imagePath = public_path('images/banners');
        if ($banner->image_path_desktop && file_exists($imagePath . '/' . $banner->image_path_desktop)) {
            unlink($imagePath . '/' . $banner->image_path_desktop);
        }
        /* Delete mobile image if exists */
        if ($banner->image_path_mobile && file_exists($imagePath . '/' . $banner->image_path_mobile)) {
            unlink($imagePath . '/' . $banner->image_path_mobile);
        }

        /* Delete the banner record */
        $banner->delete();
        return redirect()->back()->with('success', 'Banner and its images deleted successfully!');
    }


}
