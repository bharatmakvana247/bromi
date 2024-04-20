@extends('superadmin.layouts.superapp')
@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-title">
                <div class="row">

                </div>
            </div>
        </div>
        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h5 class="mb-3">Requests </h5>
                            <button
                                    class="btn custom-icon-theme-button"
                                    type="button"
                                    data-bs-toggle="modal"
                                    data-bs-target="#leadModal"
                                    title="Add Lead"
                                    onclick="resetData()"
                                ><i class="fa fa-plus"></i></button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="display" id="enquiryTable">
                                    <thead>
                                        <tr>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Mobile</th>
                                            <th>Email</th>
                                            <th>Date</th>
                                            <th>Request</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="leadModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Request View </h5>
                    <button class="btn-close btn-light" type="button" data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>
                <div class="modal-body">
                    <form class="form-bookmark needs-validation modal_form" method="post" id="modal_form" novalidate="">
                        <input type="hidden" name="this_data_id" id="this_data_id">
                        <div class="row">
                            <div class="form-group col-md-4 m-b-20">
                                <div class="fname">
                                    <label for="first_name">First Name</label>
                                    <input class="form-control" name="first_name" id="first_name" type="text"
                                        autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group col-md-4 m-b-20">
                                <div class="fname">
                                    <label for="last_name">Last Name</label>
                                    <input class="form-control" name="last_name" id="last_name" type="text"
                                        autocomplete="off" >
                                </div>
                            </div>
                            <div class="form-group col-md-4 m-b-20">
                                <div class="fname">
                                    <label for="mobile">Phone Number</label>
                                    <input class="form-control" name="mobile" id="mobile" type="text"
                                        autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group col-md-4 m-b-20">
                                <div class="fname">
                                    <label for="email">Email</label>
                                    <input class="form-control" name="email" id="email"
                                        style="text-transform: none !important;" type="text" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group col-md-4 m-b-20">
                                <div class="fname">
                                    <label for="company">Company</label>
                                    <input class="form-control" name="company" id="company" type="text"
                                        autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group col-md-4 m-b-20">
                                <div class="fname">
                                    <select name="lead_type" id="lead_type" class="form-control">
                                        <option value="">Lead Type</option>
                                        <option value="Hot">Hot</option>
                                        <option value="Warm">Warm</option>
                                        <option value="Cold">Cold</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-4 m-b-20">
                                <div class="fname">
                                    <label for="followup_date">Follow Up Date</label>
                                    <input class="form-control" name="followup_date" id="followup_date" type="date" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group col-md-4 m-b-20">
                                <div class="fname">
                                    <label for="time">Time</label>
                                    <input class="form-control" name="time" id="time" type="time" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group col-md-4 m-b-20">
                                <div class="fname">
                                    <label for="plan_interested_in">Plan Interested In</label>
                                    <input name="plan_interested_in" id="plan_interested_in" class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group m-b-20">
                                <div class="fname">
                                    <textarea name="enquiry" class="form-control" id="enquiry" cols="10" rows="5" placeholder="Enquiry"></textarea>
                                </div>
                            </div>
                            <div class="form-group col-md-4 m-b-20"></div>
                            <div class="form-group col-md-4 m-b-20"></div>
                            
                        </div>

                        <div class="row" id="total-card">
                        </div>

                        <div class="text-center mt-3">  
                            <button class="btn custom-theme-button" id="saveLead">Save</button>
                            <button class="btn btn-secondary ms-3" style="border-radius: 5px;" type="button" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function userActivate(enq) {
            $.ajax({
                type: "POST",
                url: "{{ route('superadmin.changeEnquiryStatus') }}",
                data: {
                    id: $(enq).attr('data-id'),
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    $('#enquiryTable').DataTable().draw();
                }
            });
        }

        $(document).ready(function() {
            $('#enquiryTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('superadmin.adminEnquiries') }}",
                columns: [
                    { data: 'user_name', name: 'user_name' },
                    { data: 'last_name', name: 'last_name' },
                    { data: 'mobile', name: 'mobile' },
                    { data: 'email', name: 'email' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'enquiry', name: 'enquiry' },
                    { data: 'status', name: 'status' },
                    { data: 'Actions', name: 'Actions' },
                ],
                columnDefs: [
                    { targets: 0, width: '90px' },
                    { targets: 0, className: 'small' },

                    { targets: 1, width: '90px' },
                    { targets: 1, className: 'small' },

                    { targets: 2, width: '70px' },
                    { targets: 2, className: 'text-center small' },
                    
                    { targets: 3, width: '70px' }, // Set width of the third column (index 2) to 200 pixels
                    { targets: 3, className: 'small' },

                    { targets: 4, width: '100px' }, // Set width of the 4th column (index 3) to 60 pixels
                    { targets: 4, className: 'text-center' },
                    
                    { targets: 5, width: '150px' }, // Set width of the 4th column (index 3) to 60 pixels
                    { targets: 5, className: 'small' },
                    
                    { targets: 6, width: '60px' } // Set width of the 4th column (index 3) to 60 pixels
                ]
            });
        });

        $('#modal_form').validate({ // initialize the plugin
            rules: {
                first_name: {
                    required: true,
                },
                last_name: {
                    required: true,
                },
                mobile: {
                    required: true,
                },
                company: {
                    required: true,
                },
                lead_type: {
                    required: true,
                },
                followup_date: {
                    required: true,
                },
                time: {
                    required: true,
                },
                plan_interested_in: {
                    required: true,
                },
                enquiry: {
                    required: true,
                },
                email: {
                    required: true,
                    email: true,
                }
            },
            submitHandler: function(form) { // for demo
                alert('valid form submitted'); // for demo
                return false; // for demo
            }
        });

        function resetData() {
            $('#saveEnq').removeClass('d-none');
            $('#modal_form').trigger("reset");
            $('#exampleModalLabel').text('ADD NEW Enquiry');
        }

        function getBromiEnq(data) {
            $('#modal_form').trigger("reset");
            var id = $(data).attr('data-id');
            $.ajax({
                type: "POST",
                url: "{{ route('superadmin.showEnquiry') }}",
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    console.log(data);
                    $('#first_name').val(data.brom_enq.user_name)
                    $('#last_name').val(data.brom_enq.last_name)
                    $('#mobile').val(data.brom_enq.mobile)
                    $('#email').val(data.brom_enq.email)
                    $('#company').val(data.brom_enq.company)
                    $('#lead_type').val(data.brom_enq.lead_type).trigger('change')
                    $('#followup_date').val(data.brom_enq.next_follow_up_date)
                    $('#time').val(data.brom_enq.next_follow_up_time)
                    $('#plan_interested_in').val(data.brom_enq.plan_interested_in)
                    $('#enquiry').val(data.brom_enq.enquiry);
                    $('#this_data_id').val(data.brom_enq.id)
                    $('#leadModal').modal('show');
                    $('#saveEnq').addClass('d-none');
                }
            });
        }

        $(document).on('click', '#saveLead', function(e) {
            e.preventDefault();
            $("#modal_form").validate();
            if (!$("#modal_form").valid()) {
                return
            }
            var id = $('#this_data_id').val()
            $.ajax({
                type: "POST",
                url: "{{ route('superadmin.saveEnquiry') }}",
                data: {
                    id: id,
                    user_name: $('#first_name').val(),
                    last_name: $('#last_name').val(),
                    mobile: $('#mobile').val(),
                    email: $('#email').val(),
                    company: $('#company').val(),
                    lead_type: $('#lead_type').val(),
                    followup_date: $('#followup_date').val(),
                    time: $('#time').val(),
                    plan_interested_in: $('#plan_interested_in').val(),
                    enquiry: $('#enquiry').val(),
                    _token: '{{ csrf_token() }}',
                },
                success: function(data) {
                    $('#enquiryTable').DataTable().draw();
                    $('#leadModal').modal('hide');
                    if (id.length > 0) {
                        window.location.reload();
                    }
                },
                error:function(error) {
                    console.log(error);
                    // Check if the error object contains responseJSON
                    if (error.responseJSON) {
                        // Retrieve the error message from responseJSON
                        var errorMessage = 'Something went wrong! May be duplicate email.';
                        // Display or handle the error message as needed
                        $('#em_err').remove();
                        $('#email').after('<span class="text-danger" id="em_err">' + errorMessage + '</span>');
                        console.log(errorMessage);
                    }
                }
            });
        })

    </script>
@endpush
