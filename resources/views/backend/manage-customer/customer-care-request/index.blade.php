@extends('backend.layouts.master')
@section('title','Customer Care Request List')
@section('main-content')
@push('styles')
<link href="{{asset('backend/assets/vendor/datatables/css/jquery.dataTables.css')}}" rel="stylesheet" type="text/css" media="screen" />
<link href="{{asset('backend/assets/vendor/datatables/extensions/TableTools/css/dataTables.tableTools.min.css')}}" rel="stylesheet" type="text/css" media="screen" />
<link href="{{asset('backend/assets/vendor/datatables/extensions/Responsive/css/dataTables.responsive.css')}}" rel="stylesheet" type="text/css" media="screen" />
<link href="{{asset('backend/assets/vendor/datatables/extensions/Responsive/bootstrap/3/dataTables.bootstrap.css')}}" rel="stylesheet" type="text/css" media="screen" />
<link href="{{asset('backend/assets/plugins/select2/select2.css')}}" rel="stylesheet" type="text/css" media="screen" />
<link href="{{asset('backend/assets/plugins/multi-select/css/multi-select.css')}}" rel="stylesheet" type="text/css" media="screen" />
@endpush
<!-- Start Container Fluid -->
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center gap-1">
                    <h4 class="card-title flex-grow-1">All Customer Care Request List</h4>


                </div>
                <div class="card-body">
                    @if (isset($data['customer_care_request_list']) && $data['customer_care_request_list']->count() > 0)
                    <div class="table-responsive1">
                        <table id="example-1" class="table align-middle mb-0 table-hover table-centered">
                            <thead class="bg-light-subtle">
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Category/Model</th>
                                    <th>Name/Email/Phone</th>
                                    <th>Message</th>
                                    <th>Image/Pdf File</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $sr_no = 1;
                                @endphp
                                @foreach($data['customer_care_request_list'] as $customer_care_request_row)
                                <tr>
                                    <td>{{ $sr_no }}</td>
                                    <td>
                                    <p style="margin-bottom: 5px;"><strong>Ticket No :</strong> {{ $customer_care_request_row->ticket_id }}</p>
                                        @if(!empty($customer_care_request_row->category_name))
                                           <p style="margin-bottom: 5px;"><strong>Category :</strong> {{ $customer_care_request_row->category_name }}</p>
                                        @endif
                                        @if(!empty($customer_care_request_row->model_name))
                                        <p style="margin-bottom: 5px;"><strong>Model :</strong> {{ $customer_care_request_row->model_name }}</p>
                                        @endif
                                        @if(!empty($customer_care_request_row->in_warranty === 'Yes'))
                                            <p style="margin-bottom: 5px;"><strong>In Warranty :</strong> Yes</p>
                                        @else
                                            <p style="margin-bottom: 5px;"><strong>In Warranty :</strong> No</p>
                                        @endif
                                       
                                    <p style="margin-bottom: 5px;"><strong>Problem Type :</strong> 
                                        @if(!empty($customer_care_request_row->problem_type))
                                            {{ $customer_care_request_row->problem_type }}
                                        @else
                                            No problem type specified.
                                        @endif
                                    </p>
                                    </td>

                                    <td>
                                        @if(!empty($customer_care_request_row->name))
                                            {{ $customer_care_request_row->name }}<br>
                                        @endif
                                        @if(!empty($customer_care_request_row->email))
                                            {{ $customer_care_request_row->email }}<br>
                                        @endif
                                        @if(!empty($customer_care_request_row->phone_number))
                                            {{ $customer_care_request_row->phone_number }}
                                        @endif
                                    </td>

                                    <td style="width: 200px;">
                                        <div style="max-height: 100px; overflow-y: auto; overflow-x: hidden; white-space: normal;">
                                            {{ $customer_care_request_row->message }}
                                        </div>
                                    </td>

                                    <td>
                                        @if(!empty($customer_care_request_row->product_image))
                                        <a href="{{ asset('uploads/customer-care/' . $customer_care_request_row->product_image) }}">
                                            <img src="{{ asset('uploads/customer-care/' . $customer_care_request_row->product_image) }}"
                                            class="img-thumbnail"
                                            style="width: 100px; height: 100px;"
                                            alt="{{ $customer_care_request_row->category_name }}">
                                        </a>
                                        <br>
                                        @php
                                        $pdfFile = str_replace('.jpg', '.pdf', $customer_care_request_row->product_image);
                                        @endphp
                                        <a class="btn btn-primary btn-sm" href="{{ asset('uploads/customer-care/pdf/' . $pdfFile) }}" target="_blank">PDF File</a>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="d-flex gap-2">
                                            <form method="POST" action="{{ route('customer-care-request.destroy', $customer_care_request_row->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" data-name="{{ $customer_care_request_row->name }}" class="btn btn-soft-danger btn-sm show_confirm">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @php
                                $sr_no++;
                                @endphp
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                    @endif

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

<script>
    $(document).ready(function() {
        $('.show_confirm').click(function(event) {
            var form = $(this).closest("form");
            var name = $(this).data("name");
            event.preventDefault();

            Swal.fire({
                title: `Are you sure you want to delete this ${name}?`,
                text: "If you delete this, it will be gone forever.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "Cancel",
                dangerMode: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

    });
</script>
@endpush