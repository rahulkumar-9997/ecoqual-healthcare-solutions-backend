@extends('backend.layouts.master')
@section('title','Edit Products')
@section('main-content')
@push('styles')
<link href="{{asset('backend/assets/plugins/select2/select2.css')}}" rel="stylesheet" type="text/css" media="screen" />
<link href="{{asset('backend/assets/plugins/multi-select/css/multi-select.css')}}" rel="stylesheet" type="text/css" media="screen" />
@endpush
<!-- Start Container Fluid -->
<div class="container-xxl">
   <form method="POST" action="{{ route('product.update', $data['product']->id) }}" accept-charset="UTF-8" id="product_form_edit" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <div class="row">
         <div class="col-xl-7 col-lg-7">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Product Information</h4>
               </div>
               <div class="card-body">
                  <div class="row">
                     <div class="col-lg-6">
                        <div class="mb-2">
                           <label for="product-categories" class="form-label">Product Categories *</label>
                           <select class="form-control" id="product_categories" data-choices data-choices-groups data-placeholder="Select Categories" name="product_categories" required="required">
                              <option value="">Choose a category</option>
                              @if ($data['product_category_list'] && $data['product_category_list']->isNotEmpty())
                              @foreach ($data['product_category_list'] as $category)
                              <option value="{{ $category->id }}" {{ $data['product']->category_id == $category->id ? 'selected' : '' }}>{{ $category->title }}</option>
                              @endforeach
                              @endif
                           </select>
                           @if($errors->has('product_categories'))
                           <div class="text-danger">{{ $errors->first('product_categories') }}</div>
                           @endif
                        </div>
                     </div>
                     <div class="col-lg-6">
                        <div class="mb-3">
                           <label for="product_subcategories" class="form-label">Subcategory</label>
                           <select
                              name="product_subcategories" id="product_subcategories" class="form-control">
                              <option value="">-- Select Subcategory --</option>
                              @foreach($data['product_subcategory_list'] as $subcategory)
                                    <option value="{{ $subcategory->id }}" 
                                       {{ $data['product']->subcategory_id == $subcategory->id ? 'selected' : '' }}>
                                       {{ $subcategory->title }}
                                    </option>
                              @endforeach
                           </select>
                        </div>
                     </div>
                     <div class="col-lg-12">
                        <div class="mb-2">
                           <label for="product_name" class="form-label">Product Name *</label>
                           <input type="text" name="product_name" required="required" id="product_name" class="form-control" placeholder="Items Name" value="{{ $data['product']->title }}">
                           @if($errors->has('product_name'))
                           <div class="text-danger">{{ $errors->first('product_name') }}</div>
                           @endif
                        </div>
                     </div>
                  </div>

                  <div class="row">
                     <div class="col-lg-6">
                        <div class="mb-2">
                           <label for="label" class="form-label">Label</label>
                           <select class="form-control" id="label" data-choices data-choices-groups data-placeholder="Select a Label" name="label">
                              <option value="">Choose a Label</option>
                              @if ($data['label_list'] && $data['label_list']->isNotEmpty())
                              @foreach ($data['label_list'] as $label)
                              <option value="{{ $label->id }}" {{ $data['product']->label_id == $label->id ? 'selected' : '' }}>{{ $label->title }}</option>
                              @endforeach
                              @endif
                           </select>
                        </div>
                     </div>
                     <div class="col-lg-6">
                        <div class="mb-2">
                           <label for="product_tags" class="form-label">Tags</label>
                           <select class="product_tags js-example-basic-single" name="product_tags" id="product_tags">
                              <option value="">Choose a Tags</option>
                              <option value="New" {{ $data['product']->product_tags == 'New' ? 'selected' : '' }}>New</option>
                              <option value="Digital" {{ $data['product']->product_tags == 'Digital' ? 'selected' : '' }}>Digital</option>
                           </select>
                        </div>
                     </div>

                  </div>
                  <div class="row">
                     <div class="col-lg-12">
                        <div class="mb-2">
                           <label for="product-categories" class="form-label">Stock Status *:</label><br>
                           <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" name="product_stock_status" value="1" id="product_stock_status" {{ $data['product']->product_stock_status == 1 ? 'checked' : '' }}>
                              <label class="form-check-label" for="product_stock_status">In Stock</label>
                           </div>
                           <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" name="product_stock_status" value="0" id="product_stock_status" {{ $data['product']->product_stock_status == 0 ? 'checked' : '' }}>
                              <label class="form-check-label" for="product_stock_status">Out of Stock</label>
                           </div>

                        </div>
                     </div>

                  </div>
               </div>
            </div>
            <!--Product Attributes-->
            <div class="card">
               <div class="card-header d-flex justify-content-between align-items-center gap-1">
                  <h4 class="card-title">Product Attributes <span class="text-success">(Optional)</span></h4>
                  <button class="btn btn-primary add-more-attributes btn-sm" type="button">Add More Product Attribute</button>
               </div>
               <div class="card-body add-more-attributes-append">
                  @foreach($data['product']->attributes as $index => $productAttribute)
                  <div class="row attribute-row" id="attribute-row-{{ $index }}">
                     <div class="col-lg-4">
                        <div class="mb-2">
                           <select class="product_attributes js-example-basic-single" name="product_attributes[{{ $index }}]" id="pro-att-{{ $index }}">
                              <option value="" disabled>Select an option</option>
                              @foreach($data['product_attributes_list'] as $attribute)
                              <option value="{{ $attribute->id }}" {{ $attribute->id == $productAttribute->attribute->id ? 'selected' : '' }}>
                                 {{ $attribute->title }}
                              </option>
                              @endforeach
                           </select>
                        </div>
                     </div>

                     <div class="col-lg-8">
                        <div class="mb-2">

                           @php
                           $attributes_values = $productAttribute->values->pluck('attributeValue.name')->implode(', ');
                           $attributes_value_id = $productAttribute->values->pluck('attributeValue.id')->implode(',');
                           @endphp

                           <input type="text" name="product_attributes_value[{{ $index }}][]"
                              id="pro-att-value-{{ $index }}"
                              class="form-control"
                              placeholder="Enter attributes value comma separated"
                              value="{{ $attributes_values }}">
                           <input type="hidden" value="{{$attributes_value_id}}" name="product_attributes_value_id[{{ $index }}][]">
                        </div>
                     </div>

                  </div>
                  @endforeach

               </div>
            </div>
            <!-- MATERIAL -->
            <div class="card">
               <div class="card-header d-flex justify-content-between align-items-center gap-1">
                  <h4 class="card-title">
                     Product Material <span class="text-success">(Optional)</span>
                  </h4>
                  <button class="btn btn-primary add-more-material btn-sm" type="button">
                     Add More Product Material
                  </button>
               </div>
               <div class="card-body add-more-material-append">
                  @forelse($data['product']->materials as $key => $material)
                  <div class="row" id="material-row-{{ $key }}">
                     <div class="col-lg-10">
                        <div class="mb-1">
                           <input type="text" name="product_material[]" id="product_material_{{ $key }}"
                              class="form-control" value="{{ $material->material ?? '' }}" placeholder="Enter material">
                        </div>
                     </div>
                     <div class="col-lg-2">
                        @if($key > 0)
                        <button type="button" class="btn btn-danger remove-material btn-sm" data-id="{{ $key }}">
                           Remove
                        </button>
                        @endif
                     </div>
                  </div>
                  @empty
                  <div class="row" id="material-row-0">
                     <div class="col-lg-12">
                        <div class="mb-1">
                           <input type="text" name="product_material[]" id="product_material_0"
                              class="form-control" placeholder="Enter material">
                        </div>
                     </div>
                  </div>
                  @endforelse
               </div>
            </div>


            <!-- INGREDIENTS -->
            <div class="card">
               <div class="card-header d-flex justify-content-between align-items-center gap-1">
                  <h4 class="card-title">
                     Product Ingredients <span class="text-success">(Optional)</span>
                  </h4>
                  <button class="btn btn-primary add-more-ingredients btn-sm" type="button">
                     Add More Product Ingredients
                  </button>
               </div>
               <div class="card-body add-more-ingredients-append">
                  @forelse($data['product']->ingredients as $key => $ingredient)
                  <div class="row" id="ingredients-row-{{ $key }}">
                     <div class="col-lg-10">
                        <div class="mb-1">
                           <input type="text" name="product_ingredients[]" id="product_ingredients_{{ $key }}"
                              class="form-control" value="{{ $ingredient->ingredient ?? '' }}" placeholder="Enter ingredient">
                        </div>
                     </div>
                     <div class="col-lg-2">
                        @if($key > 0)
                        <button type="button" class="btn btn-danger remove-ingredients btn-sm" data-id="{{ $key }}">
                           Remove
                        </button>
                        @endif
                     </div>
                  </div>
                  @empty
                  <div class="row" id="ingredients-row-0">
                     <div class="col-lg-12">
                        <div class="mb-1">
                           <input type="text" name="product_ingredients[]" id="product_ingredients_0"
                              class="form-control" placeholder="Enter ingredient">
                        </div>
                     </div>
                  </div>
                  @endforelse
               </div>
            </div>


            <!-- SPECIFICATIONS -->
            <div class="card">
               <div class="card-header d-flex justify-content-between align-items-center gap-1">
                  <h4 class="card-title">
                     Product Specifications <span class="text-success">(Optional)</span>
                  </h4>
                  <button class="btn btn-primary add-more-specifications btn-sm" type="button">
                     Add More Product Specifications
                  </button>
               </div>
               <div class="card-body add-more-specifications-append">
                  @forelse($data['product']->specifications as $key => $specification)
                  <div class="row" id="specifications-row-{{ $key }}">
                     <div class="col-lg-10">
                        <div class="mb-1">
                           <input type="text" name="product_specifications[]" id="product_specifications_{{ $key }}"
                              class="form-control" value="{{ $specification->specification ?? '' }}" placeholder="Enter specification">
                        </div>
                     </div>
                     <div class="col-lg-2">
                        @if($key > 0)
                        <button type="button" class="btn btn-danger remove-specifications btn-sm" data-id="{{ $key }}">
                           Remove
                        </button>
                        @endif
                     </div>
                  </div>
                  @empty
                  <div class="row" id="specifications-row-0">
                     <div class="col-lg-12">
                        <div class="mb-1">
                           <input type="text" name="product_specifications[]" id="product_specifications_0"
                              class="form-control" placeholder="Enter specification">
                        </div>
                     </div>
                  </div>
                  @endforelse
               </div>
            </div>


            <!-- ADDITIONAL FEATURES -->
            <div class="card">
               <div class="card-header d-flex justify-content-between align-items-center gap-1">
                  <h4 class="card-title">
                     Product Additional Feature <span class="text-success">(Optional)</span>
                  </h4>
                  <button class="btn btn-primary add-more-additional-feature btn-sm" type="button">
                     Add More Additional Feature
                  </button>
               </div>
               <div class="card-body add-more-additional-feature-append">
                  @forelse($data['product']->additionalFeatures as $key => $feature)
                  <div class="row" id="additional-feature-row-{{ $key }}">
                     <div class="col-lg-10">
                        <div class="mb-1">
                           <input type="text" name="additional_feature_key_value[]" id="additional-feature-key-value-{{ $key }}"
                              class="form-control" value="{{ $feature->product_additional_featur_value ?? '' }}" placeholder="Enter additional feature value">
                        </div>
                     </div>
                     <div class="col-lg-2">
                        @if($key > 0)
                        <button type="button" class="btn btn-danger remove-feature btn-sm" data-id="{{ $key }}">
                           Remove
                        </button>
                        @endif
                     </div>
                  </div>
                  @empty
                  <div class="row" id="additional-feature-row-0">
                     <div class="col-lg-12">
                        <div class="mb-1">
                           <input type="text" name="additional_feature_key_value[]" id="additional-feature-key-value-0"
                              class="form-control" placeholder="Enter additional feature value">
                        </div>
                     </div>
                  </div>
                  @endforelse
               </div>
            </div>

            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Main Information</h4>
               </div>
               <div class="card-body">
                  <div class="row">
                     <div class="col-lg-3">
                        <div class="mb-2">
                           <div class="form-check form-switch">
                              <input class="form-check-input" type="checkbox" role="switch" id="product_status" name="product_status" {{ $data['product']->product_status == 1 ? 'checked' : '' }}>
                              <label class="form-check-label" for="product_status">Product Status</label>
                           </div>
                        </div>
                     </div>
                     <div class="col-lg-4">
                        <div class="mb-2">
                           <div class="form-check form-switch">
                              <input class="form-check-input" type="checkbox" role="switch" id="warranty_status" name="warranty_status" {{ $data['product']->warranty_status == 1 ? 'checked' : '' }}>
                              <label class="form-check-label" for="warranty_status">Warranty available for this product?</label>
                           </div>
                        </div>
                     </div>
                     <div class="col-lg-5">
                        <div class="mb-2">
                           <div class="form-check form-switch">
                              <input class="form-check-input" type="checkbox" role="switch" id="attributes_show_status" name="attributes_show_status" {{ $data['product']->attributes_show_status == 1 ? 'checked' : '' }}>
                              <label class="form-check-label" for="attributes_show_status">Product Variant Show In Product Page</label>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <!--seo meta-->
            <div class="card">
               <div class="card-header d-flex justify-content-between align-items-center gap-1">
                  <h4 class="card-title">SEO Meta Tags <span class="text-success">(Optional)</span></h4>
               </div>
               <div class="card-body">
                  <div class="row">
                     <div class="col-lg-12">
                        <div class="mb-2">
                           <label for="meta_title" class="form-label">Meta Title</label>
                           <input type="text" id="meta_title" name="meta_title" class="form-control" placeholder="Meta title" value="{{ $data['product']->meta_title }}">
                        </div>
                     </div>

                     <div class="col-lg-12">
                        <div class="mb-2">
                           <label for="meta_title" class="form-label">Meta Description</label>
                           <textarea class="form-control bg-light-subtle" id="meta_description" rows="4" name="meta_description" placeholder="Short description about meta description">{{ $data['product']->meta_description }}</textarea>
                        </div>
                     </div>
                  </div>

               </div>
            </div>
            <!--seo meta-->
         </div>
         <div class="col-xl-5 col-lg-5">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">About Product</h4>
               </div>
               <div class="card-body">
                  <div class="row">
                     <div class="col-lg-12">
                        <div class="mb-2">
                           <h5 class="card-title mb-1 anchor">
                              Product Images *
                           </h5>
                           <div class="mb-3">
                              <input type="file" id="image-input" class="form-control" aria-label="file example" name="product_images[]" accept="image/*" multiple>
                           </div>
                           <div id="image-preview"></div>
                        </div>
                        <div class="row mb-3">
                           @if ($data['product']->images && $data['product']->images->isNotEmpty())
                           @foreach ($data['product']->images as $image)
                           <div class="col-md-3 mb-2">
                              <img src="{{ asset('images/product/thumb/'. $image->image_path) }}" class="img-thumbnail" alt="Product Image" style="width: 100px; height: 100px;">
                              <a href="{{ route('product.image.delete', $image->id) }}" class="btn btn-danger btn-sm mt-2" onclick="return confirm('Are you sure you want to delete this image?')">
                                 <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                              </a>
                           </div>
                           @endforeach
                           @else
                           <p>No images found for this product.</p>
                           @endif
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-lg-12">
                        <div class="mb-2">
                           <h5 class="card-title mb-1 anchor" id="quill-snow-editor">
                              Product Description
                           </h5>
                           <div class="mb-3">
                              <div class="snow-editor" style="height: 200px; width: 100%;">{!! $data['product']->product_description !!}</div>
                              <textarea name="product_description" class="hidden-textarea" style="display:none;">{!! $data['product']->product_description !!}</textarea>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-lg-12">
                        <div class="mb-2">
                           <h5 class="card-title mb-1 anchor" id="quill-snow-editor">
                              Product Specification
                           </h5>
                           <div class="mb-3">
                              <div class="snow-editor" style="height: 200px; width: 100%;">{!! $data['product']->product_specification !!}</div>
                              <textarea name="product_specification" class="hidden-textarea" style="display:none;">{!! $data['product']->product_specification !!}</textarea>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="card card_fixed">
               <div class="card-footer bg-light-subtle">
                  <div class="row g-2">
                     <div class="col-lg-6">
                        <input type="submit" value="Update Product" class="btn btn-outline-secondary w-100">
                     </div>
                     <div class="col-lg-6">
                        <a href="{{ route('product.index') }}" class="btn btn-primary w-100">Cancel</a>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </form>
</div>
<!-- End Container Fluid -->
<!-- Modal -->
@include('backend.layouts.common-modal-form')
<!-- modal--->
@endsection
@push('scripts')
<script src="{{asset('backend/assets/js/components/form-quilljs.js')}}"></script>
<script src="{{asset('backend/assets/plugins/select2/select2.min.js')}}" type="text/javascript"></script>
<script src="{{asset('backend/assets/plugins/multi-select/js/jquery.multi-select.js')}}" type="text/javascript"></script>
<script src="{{asset('backend/assets/plugins/multi-select/js/jquery.quicksearch.js')}}" type="text/javascript"></script>
<script src="{{asset('backend/assets/js/pages/product-edit-append.js')}}" type="text/javascript"></script> 
<script>
   $(document).ready(function() {
      var selectedFiles = [];
      $('#image-input').on('change', function() {
         selectedFiles = Array.from(this.files);
         displayImages();
      });

      function displayImages() {
         $('#image-preview').html('');
         selectedFiles.forEach(function(file, index) {
            var reader = new FileReader();
            reader.onload = function(e) {
               var imageContainer = $('<div>').addClass('image-container').css({
                  position: 'relative',
                  display: 'inline-block',
                  margin: '10px'
               });

               var img = $('<img>').attr('src', e.target.result).css({
                  width: '100px',
                  height: '100px',
                  border: '1px solid #ddd'
               });

               var sizeText = $('<p>').text('Size: ' + Math.round(file.size / 1024) + ' KB').css({
                  fontSize: '12px',
                  color: '#666',
                  marginTop: '5px',
                  textAlign: 'center'
               });
               var deleteBtn = $('<button>').html('×').css({
                  position: 'absolute',
                  top: '0',
                  right: '0',
                  backgroundColor: '#ff4444',
                  color: '#fff',
                  border: 'none',
                  width: '20px',
                  height: '20px',
                  fontSize: '16px',
                  cursor: 'pointer',
                  lineHeight: '20px',
                  textAlign: 'center'
               });

               deleteBtn.on('click', function() {
                  selectedFiles.splice(index, 1);
                  resetInputField();
                  displayImages();
               });
               imageContainer.append(img, sizeText, deleteBtn);
               $('#image-preview').append(imageContainer);
            }

            reader.readAsDataURL(file);
         });
      }

      function resetInputField() {
         var dataTransfer = new DataTransfer();
         selectedFiles.forEach(function(file) {
            dataTransfer.items.add(file);
         });
         $('#image-input')[0].files = dataTransfer.files;
      }

   });
</script>
<script>
   var selectedAttributeIds = new Set();   
   $(document).ready(function() {
      initializeSelect2();
      var newDiv = `...`;
      $('.add-more-attributes').on('click', function() {
         var lastRow = $('.attribute-row').last();
         var attributeCount = lastRow.length ? parseInt(lastRow.attr('id').split('-')[2]) + 1 : 0;
         var newDiv = `
            <div class="row attribute-row" id="attribute-row-${attributeCount}">
               <div class="col-lg-4">
                  <div class="mb-2">
                        <select class="product_attributes js-example-basic-single" name="product_attributes[${attributeCount}]" id="pro-att-${attributeCount}">
                           <option value="" disabled selected>Select an option</option>
                              @foreach($data['product_attributes_list'] as $attributes_list_row)
                                 <option value="{{ $attributes_list_row->id }}">{{ $attributes_list_row->title }}</option>
                              @endforeach
                        </select>
                  </div>
               </div>
               <div class="col-lg-8">
                  <div class="mb-2">
                        <!--<select class="js-example-basic-multiple" name="product_attributes_value[${attributeCount}][]" id="pro-att-value-${attributeCount}" multiple="multiple">
                           <option value="" disabled selected>Select Product Attributes Value</option>
                        </select>-->
                        <input type="text" name="product_attributes_value[${attributeCount}][]" id="pro-att-value-${attributeCount}"
                        class="form-control" 
                        placeholder="Enter attributes value comma separated" 
                        >
                  </div>
               </div>
               <!--<div class="col-lg-2">
                  <button type="button" class="btn btn-sm btn-danger remove-attribute">Remove</button>
               </div>-->
            </div>`;
         $('.add-more-attributes-append').append(newDiv);
         //console.log('Added new attribute row:', newDiv);
         //console.log('Current attribute count:', attributeCount);
         initializeSelect2();
         updateAttributeOptions();
      });
      /*attributes select  */
      $(document).on('change', '.product_attributes', function() {
         updateAttributeOptions();
      });
      /*attributes select  */      
   });
   /**remove attributes code start */
   $('.add-more-attributes-append').on('click', '.remove-attribute', function() {
      $(this).closest('.attribute-row').remove();
   });
   /**remove attributes code end */


   function initializeSelect2() {

      $('.js-example-basic-single').each(function() {
         if (!$(this).data('select2')) {
            $(this).select2({
               placeholder: "Select an option",
               allowClear: true
            });
         }
      });
      $('.js-example-basic-multiple').each(function() {
         if (!$(this).data('select2')) {
            $(this).select2({
               placeholder: "Select Product Attributes Value",
               allowClear: true
            });
         }
      });
   }

   function updateAttributeOptions() {
      var selectedAttributes = [];
      $('.product_attributes').each(function() {
         var selectedValue = $(this).val();
         if (selectedValue) {
            selectedAttributes.push(selectedValue);
         }
      });

      $('.product_attributes').each(function() {
         const currentDropdown = $(this);
         const currentValue = currentDropdown.val();
         currentDropdown.find('option').each(function() {
            if ($(this).val()) {
               const optionValue = $(this).val();
               if (selectedAttributes.includes(optionValue) && optionValue !== currentValue) {
                  $(this).remove();
               } else if (!selectedAttributes.includes(optionValue)) {
                  if (currentDropdown.find(`option[value="${optionValue}"]`).length === 0) {
                     currentDropdown.append(`<option value="${optionValue}">${$(this).text()}</option>`);
                  }
               }
            }
         });
      });
   }
</script>
<script>
document.getElementById('product_categories').addEventListener('change', function() {
    let categoryId = this.value;
    fetch(`/get-subcategories/${categoryId}`)
        .then(res => res.json())
        .then(data => {
            let subcategorySelect = document.getElementById('product_subcategories');
            subcategorySelect.innerHTML = '<option value="">-- Select Subcategory --</option>';
            data.forEach(sub => {
                let option = document.createElement('option');
                option.value = sub.id;
                option.text = sub.title;
                subcategorySelect.appendChild(option);
            });
        });
});
</script>

@endpush