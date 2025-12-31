@extends('backend.layouts.master')
@section('title','Create new Blog')
@section('main-content')
@push('styles')

@endpush

<!-- Start Container Fluid -->
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center gap-1">
                    <h4 class="card-title flex-grow-1"> Create new Blog</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{route('manage-blog.store')}}" accept-charset="UTF-8" enctype="multipart/form-data" id="addNewBlog" onsubmit="this.querySelector('button[type=submit]').disabled=true;">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="blog_category" class="form-label">Select Blog Category</label>
                                    <select class="form-control" id="blog_category" data-choices data-choices-groups data-placeholder="Select Blog Categories" name="blog_category">
                                        <option value="">Select Blog Category</option>
                                        @foreach ($blog_category as $blog_category_row)
                                        <option value="{{$blog_category_row->id}}" {{ old('blog_category') == $blog_category_row->id ? 'selected' : '' }}>
                                            {{$blog_category_row->title}}
                                        </option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('blog_category'))
                                    <div class="text-danger">{{ $errors->first('blog_category') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="blog_name" class="form-label">Blog Title Name *</label>
                                    <input type="text" id="blog_name" name="blog_name" class="form-control" value="{{ old('blog_name')}}">
                                    @if($errors->has('blog_name'))
                                    <div class="text-danger">{{ $errors->first('blog_name') }}</div>
                                    @endif                                    
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="blog_img" class="form-label">Blog Title Image *</label>
                                    <input type="file" id="blog_img" class="form-control" aria-label="file example" name="blog_img" accept="image/*" value="{{ old('blog_img')}}">
                                    @if($errors->has('blog_img'))
                                    <div class="text-danger">{{ $errors->first('blog_img') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-2">
                                    <h5 class="card-title mb-1 anchor" id="quill-snow-editor">
                                        Blog Description *
                                    </h5>
                                    <div class="mb-3">                                       
                                        <textarea name="blog_description" class="ckeditor4" > {{ old('blog_description')}}</textarea>
                                        @if($errors->has('blog_description'))
                                        <div class="text-danger">{{ $errors->first('blog_description') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="meta_title" class="form-label">Meta Title</label>
                                    <input type="text" id="meta_title" name="meta_title" class="form-control" value="{{ old('meta_title')}}">
                                    @if($errors->has('meta_title'))
                                    <div class="text-danger">{{ $errors->first('meta_title') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="meta_description" class="form-label">Meta Description</label>
                                    <textarea class="form-control" id="meta_description" rows="3" name="meta_description"> {{ old('meta_description')}}</textarea>
                                    @if($errors->has('meta_description'))
                                    <div class="text-danger">{{ $errors->first('meta_description') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="bg-indigo pt-1 pb-1 rounded-2">
                                    <h4 class="text-center text-light" style="margin-bottom: 0px;;">Blog Paragraphs</h4>
                                </div>

                                <table class="table align-middle mb-0 table-hover table-centered">
                                    <tr>
                                        <th style="width: 25%">Paragraphs Title</th>
                                        <th style="width: 25%"> Product Links</th>
                                        <th class="d-flex justify-content-between">
                                            <span>Paragraphs Description</span>
                                            <button class="btn btn-primary text-end add-more-blog-paragraphs btn-sm" type="button">Add More Blog Paragraphs</button>

                                        </th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="text" id="paragraphs_title" name="paragraphs_title[]" class="form-control" placeholder="Enter Paragraphs Title">
                                            @if($errors->has('paragraphs_title'))
                                            <div class="text-danger">{{ $errors->first('paragraphs_title') }}</div>
                                            @endif

                                        </td>
                                        <td>
                                            <!-- <input type="file" id="paragraphs_img" class="form-control" aria-label="file example"  name="paragraphs_img[]" accept="image/*">
                                        @if($errors->has('paragraphs_img'))
                                            <div class="text-danger">{{ $errors->first('paragraphs_img') }}</div>
                                        @endif -->
                                            <label for="blog_img" class="form-label">Select Blog Links One</label>
                                            <div class="position-relative autocompleted">
                                                <div class="input-group">
                                                    <input type="text" id="product_name" name="product_name[]" class="form-control product-autocomplete">

                                                    <span class="input-group-text">
                                                        <i class="ti ti-refresh"></i>
                                                        <div class="spinner-border spinner-border-sm product-loader" role="status" style="display: none;">
                                                            <span class="visually-hidden">Loading...</span>
                                                        </div>
                                                    </span>
                                                </div>
                                                <input type="hidden" name="product_id[]" class="product_id">
                                            </div>
                                            <!-- <input type="text" id="blog_links_one" name="blog_links_one[]" placeholder="Enter Blog Links One" class="form-control"> -->
                                            @if($errors->has('blog_links_one'))
                                            <div class="text-danger">{{ $errors->first('blog_links_one') }}</div>
                                            @endif
                                            <br>
                                            <label for="blog_img" class="form-label">Select Blog Links Two</label>
                                            <div class="position-relative autocompleted">
                                                <div class="input-group">
                                                    <input type="text" id="product_name" name="product_name[]" class="form-control product-autocomplete">

                                                    <span class="input-group-text">
                                                        <i class="ti ti-refresh"></i>
                                                        <div class="spinner-border spinner-border-sm product-loader" role="status" style="display: none;">
                                                            <span class="visually-hidden">Loading...</span>
                                                        </div>
                                                    </span>
                                                </div>
                                                <input type="hidden" name="product_id[]" class="product_id">
                                            </div>
                                            @if($errors->has('blog_links_two'))
                                            <div class="text-danger">{{ $errors->first('blog_links_two') }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            
                                            <textarea name="paragraphs_description[]" class="ckeditor4"></textarea>
                                            @if($errors->has('paragraphs_description'))
                                            <div class="text-danger">{{ $errors->first('paragraphs_description') }}</div>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="pb-0 pt-3 d-flex gap-2 justify-content-end">
                                <a href="{{ route('manage-blog.index') }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Container Fluid -->
<!-- Modal -->
@include('backend.layouts.common-modal-form')
<!-- modal--->
@endsection
@push('scripts')
<!--autocomplete-->
<link rel="stylesheet" href="{{asset('backend/assets/js/autocomplete/jquery-ui.css')}}">
<script src="{{asset('backend/assets/js/autocomplete/jquery-ui.min.js')}}"></script>
<script src="{{ asset('backend/assets/ckeditor-4/ckeditor.js') }}"></script>
<script>
    document.querySelectorAll('.ckeditor4').forEach(function(el) {
        CKEDITOR.replace(el, {
            removePlugins: 'exportpdf'
        });
    });
</script>
<script src="{{asset('backend/assets/js/pages/create-blog.js')}}" type="text/javascript"></script>

@endpush