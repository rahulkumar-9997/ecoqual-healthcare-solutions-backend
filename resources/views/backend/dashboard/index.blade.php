@extends('backend.layouts.master')
@section('title','Dashboard')
@push('styles')
    <!-- <link href="{{ asset('css/styles.css') }}" rel="stylesheet"> -->
@endpush
@section('main-content')
   <div class="container-fluid">
      <!-- Start here.... -->
      <div class="row">
         <div class="col-xxl-12">
            <div class="row">
               
               <div class="col-md-4">
                  <div class="card overflow-hidden">
                     <div class="card-body">
                        <a href="{{route('category')}}">
                           <div class="row">
                              <div class="col-3">
                                 <div class="avatar-md bg-soft-primary rounded">
                                    <i class="bx bx-box avatar-title fs-24 text-primary"></i>
                                 </div>
                              </div>
                              <!-- end col -->
                              <div class="col-9 text-end">
                                 <p class="text-muted mb-0 text-truncate">Category</p>
                                 <h3 class="text-dark mt-1 mb-0">{{ $data['category_count'] }}</h3>
                              </div>
                              <!-- end col -->
                           </div>
                        </a>
                        <!-- end row-->
                     </div>
                  </div>
                  <!-- end card -->
               </div>
               <!-- end col -->
               <div class="col-md-4">
                  <div class="card overflow-hidden">
                     <div class="card-body">
                        <a href="{{ route('product.index') }}">
                           <div class="row">
                              <div class="col-3">
                                 <div class="avatar-md bg-soft-primary rounded">
                                    <i class="bx bx-cart avatar-title fs-24 text-primary"></i>
                                 </div>
                              </div>
                              <!-- end col -->
                              <div class="col-9 text-end">
                                 <p class="text-muted mb-0 text-truncate">Producs</p>
                                 <h3 class="text-dark mt-1 mb-0">{{ $data['product_count'] }}</h3></h3>
                              </div>
                              <!-- end col -->
                           </div>
                        </a>
                        <!-- end row-->
                     </div>
                  </div>
                  <!-- end card -->
               </div> 
            </div>
         </div>
         <!-- end col -->
         <div class="col-xxl-6">
            <div class="card">
               <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center">
                     <h4 class="card-title">Added Product Month Wise</h4>
                     <div>
                        <button type="button" class="btn btn-sm btn-outline-light" data-filter="all">ALL</button>
                        <button type="button" class="btn btn-sm btn-outline-light" data-filter="1M">1M</button>
                        <button type="button" class="btn btn-sm btn-outline-light" data-filter="6M">6M</button>
                        <button type="button" class="btn btn-sm btn-outline-light active" data-filter="1Y">1Y</button>
                     </div>

                  </div>
                  <!-- end card-title-->
                  <div dir="ltr">
                     <div id="product-chart" class="apex-charts"></div>
                  </div>
               </div>
               <!-- end card body -->
            </div>
            <!-- end card -->
         </div>
         
         <!-- end col -->
      </div>
   </div>

@endsection

@push('scripts')
<!-- <script src="{{asset('backend/assets/vendor/jsvectormap/js/jsvectormap.min.js')}}"></script> -->
<!-- <script src="{{asset('backend/assets/vendor/jsvectormap/maps/world-merc.js')}}"></script> -->
<!-- <script src="{{asset('backend/assets/vendor/jsvectormap/maps/world.js')}}"></script> -->
<!-- <script src="{{asset('backend/assets/js/pages/dashboard.js')}}"></script> -->
 <!-- <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script> -->
<script>
   $(document).ready(function () {
      var options = {
         series: [{
            name: 'Products',
            data: []
         }],
         chart: {
            type: 'bar',
            height: 350
         },
         xaxis: {
            categories: []
         },
         colors: ['#ff6c2f'],
         dataLabels: {
            enabled: false
         },
         
      };

      var chart = new ApexCharts(document.querySelector("#product-chart"), options);
      chart.render();
      $(".btn-outline-light").on("click", function () {
         let filter = $(this).data("filter");
         $(".btn-outline-light").removeClass("active");
         $(this).addClass("active");
         $.ajax({
               //url: "/dashboard/filtered-data",
               url: "{{ route('dashboard.filtered-data') }}",
               type: "GET",
               data: { filter: filter },
               success: function (response) {
                  chart.updateSeries([{
                     name: 'Products',
                     data: response.totals
                  }]);
                  chart.updateOptions({
                     xaxis: {
                        categories: response.months
                     }
                  });
               },
               error: function (xhr, status, error) {
                  console.error("Error fetching data:", error);
               }
         });
      });
      $(".btn-outline-light[data-filter='all']").click();
   });
   /**Visitor Graphs */


</script>
@endpush