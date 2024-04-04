@extends('superadmin.layouts.superapp')
@section('content')
<div class="page-body">
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">

            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5 class="mb-3">Users </h5>
                        <div class="row mt-3 mb-3 gy-3">
                            <div style="width: 70px;">
                                <button
                                    class="btn custom-icon-theme-button"
                                    type="button"
                                    data-bs-toggle="modal"
                                    data-bs-target="#userModal"
                                    title="Add User"
                                    onclick="resetData()"
                                ><i class="fa fa-plus"></i>
                                </button>
                            </div>
                            <div class="col-12 col-lg-2 col-md-2">
                                <select
                                    id="filter_type"
                                    class="form-control"
                                    style="border: 1px solid black;"
                                    onchange="updateFilter()"
                                >
                                    <option value="">-- Select filter --</option>
                                    <option value="state">State</option>
                                    <option value="city">City</option>
                                </select>
                            </div>
                            <div class="col-12 col-lg-2 col-md-2">
                                <div class="fname">
                                    <input
                                        type="text"
                                        id="filter_value"
                                        class="form-control"
                                        placeholder="Search ...."
                                        onkeyup="filter()"
                                        readonly
                                    >
                                </div>
                            </div>
                            <div style="width: 70px;">
                                <button
                                    class="btn custom-icon-theme-button"
                                    type="button"
                                    title="reset"
                                    onclick="reset()"
                                ><i class="fa fa-recycle"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display" id="userTable">
                                <thead>
                                    <tr>
                                        <th>Sr No.</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>State</th>
                                        <th>City</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Plan</th>
                                        <th>Subscribed On</th>
                                        <th>Expired On</th>
                                        <th>Company Name</th>
                                        <th>Users</th>
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
<div class="modal fade" id="userModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button class="btn-close bg-light" type="button" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>
            <div class="modal-body">
                <form class="form-bookmark needs-validation modal_form" method="post" id="modal_form" novalidate="">
                    <input type="hidden" name="this_data_id" id="this_data_id">
                    <div class="row">
                        <div class="form-group col-md-4 m-b-20">
                            <div class="fname">
                                <input class="form-control" name="first_name" id="first_name" type="text" autocomplete="off" placeholder="Name">
                            </div>
                        </div>
                        <div class="form-group col-md-4 m-b-20">
                            <div class="fname">
                                <input class="form-control" name="last_name" id="last_name" type="text" autocomplete="off" placeholder="Last Name">
                            </div>
                        </div>
                        <div class="form-group col-md-4 m-b-20">
                            <div class="fname">
                                <input class="form-control" name="email" id="email" style="text-transform: none !important;" type="text" autocomplete="off" placeholder="Email">
                            </div>
                        </div>
                        <div class="form-group col-md-4 m-b-20">
                            <div class="fname">
                                <input class="form-control" name="password" id="password" type="text" autocomplete="off" placeholder="Password">
                            </div>
                        </div>
                    </div>

                    <div class="row" id="total-card">
                        <div class="col-sm-4">
                            <div class="card bg-white" style="border-style: groove;">
                                <div class="card-body shadow-none">
                                    <h6 class="text-center">Total Property</h6>
                                    <h5 class="text-center mt-3" id="total_property"></h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="card bg-white" style="border-style: groove;">
                                <div class="card-body shadow-none">
                                    <h6 class="text-center">Total Project</h6>
                                    <h5 class="text-center mt-3" id="total_project">25</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="card bg-white" style="border-style: groove;">
                                <div class="card-body shadow-none">
                                    <h6 class="text-center">Total Inquiry</h6>
                                    <h5 class="text-center mt-3" id="total_enquiry">45</h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3" id="user_table">
                        <h4 class="text-center">Sub Users</h4>
                        <div class="col p-2">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Sr No.</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>  
                                        <th>Company Name</th>
                                    </tr>
                                </thead>
                                <tbody id="user_data">
                                </tbody>
                            </table>
                        </div>

                        <div class="row mt-3 mb-3">
                            <div class="col" id="login_as_user">
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <button class="btn custom-theme-button" id="saveUser">Save</button>
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

    function updateFilter() {
        let filterOn = document.getElementById('filter_type');
        let search_input = document.getElementById('filter_value');
        search_input.value = '';

        if(filterOn.value != '')
        {
            search_input.readOnly = false;
            search_input.placeholder = `Search ${filterOn.value}`;
        } else {
            search_input.readOnly = true;
            search_input.placeholder = 'Search ....';
        }
    }

    function delayedFunction() {
        $('#userTable').DataTable().draw();
    }

    function filter() {
        setTimeout(delayedFunction, 1000);
    }

    function reset() {
        let filterOn = document.getElementById('filter_type');
        let search_input = document.getElementById('filter_value');

        search_input.value = '';
        filterOn.value = '';
        $('#userTable').DataTable().draw();
    }

    function userActivate(user, status) {
        $.ajax({
            type: "POST",
            url: "{{ route('superadmin.changeUserStatus') }}",
            data: {
                id: $(user).attr('data-id'),
                status: status,
                _token: '{{ csrf_token() }}'
            },
            success: function(data) {
                $('#userTable').DataTable().draw();
            }
        });
    }

    $(document).ready(function() {
        let filterOn = document.getElementById('filter_type');
        let search_input = document.getElementById('filter_value');
        $('#userTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('superadmin.users') }}",
                data: function(d) {
                    d.filter_type = filterOn.value;
                    d.filter_value = search_input.value;
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                {
                    data: 'first_name',
                    name: 'first_name'
                },
                {
                    data: 'last_name',
                    name: 'last_name'
                },
                {
                    data: 'state_name',
                    name: 'state_name'
                },
                {
                    data: 'city_name',
                    name: 'city_name'
                },
                {
                    data: "email" , render : function ( data, type, row, meta ) {
                        return `<span style="text-transform:lowercase !important">${row.email}</span>`; 
                    }
                },
                {
                    data: 'mobile_number',
                    name: 'mobile_number'
                },
                {
                    data: 'plan',
                    name: 'plan'
                },
                {
                    data: 'subscribed_on',
                    name: 'subscribed_on'
                },
                {
                    data: 'plan_expire_on',
                    name: 'plan_expire_on'
                },
                {
                    data: 'company_name',
                    name: 'company_name',
                },
                {
                    data: 'users',
                    name: 'users'
                },
                {
                    data: 'Actions',
                    name: 'Actions'
                },
            ]
        });

    });


    $('#modal_form').validate({ // initialize the plugin
        rules: {
            first_name: {
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
        document.getElementById('total-card').classList.add('d-none');
        document.getElementById('user_table').classList.add('d-none');

        let modal_title = document.getElementById('exampleModalLabel');
        modal_title.innerHTML = 'Add New User';
    }

    function getUser(data) {
        $('#modal_form').trigger("reset");
        var id = $(data).attr('data-id');
        $.ajax({
            type: "POST",
            url: "{{ route('superadmin.getUser') }}",
            data: {
                id: id,
                _token: '{{ csrf_token() }}'
            },
            success: function(data) {

                document.getElementById('total-card').classList.remove('d-none');

                $('#this_data_id').val(data.main_user.id)
                $('#first_name').val(data.main_user.first_name)
                $('#last_name').val(data.main_user.last_name)
                $('#email').val(data.main_user.email)
                $('#userModal').modal('show');

                let table = document.getElementById('user_table');
                let table_data = document.getElementById('user_data');
                let total_property = document.getElementById('total_property');
                let total_project = document.getElementById('total_project');
                let total_inquiry = document.getElementById('total_enquiry');

                let routeUrl = `{{ route('login_as_user', ['id' => ':id']) }}`.replace(':id', data.main_user.id);

                var anchorTag = document.createElement('a');
                anchorTag.href = routeUrl;
                anchorTag.className = "btn btn-primary";
                anchorTag.innerText = 'Login As User';

                let login = document.getElementById('login_as_user');
                login.innerHTML = '';
                login.appendChild(anchorTag);

                total_property.innerHTML = data.total_property;
                total_project.innerHTML = data.total_project;
                total_inquiry.innerHTML = data.total_enquiry;

                let modal_title = document.getElementById('exampleModalLabel');
                modal_title.innerHTML = 'Update User';

                if (data.sub_user.length > 0) {
                    table.classList.remove('d-none');
                    table_data.innerHTML = '';

                    data.sub_user.forEach((user, index) => {

                        let subrouteUrl = `{{ route('login_as_user', ['id' => ':id']) }}`.replace(':id', user.id);
                        
                        table_data.innerHTML += `<tr>
                            <td>${index + 1}</td>
                            <td>${user.first_name}</td>
                            <td>${user.last_name}</td>
                            <td>${user.email}</td>
                            <td>${user.mobile_number}</td>
                            <td>${user.company_name}</td>
                            <td><a class="btn btn-primary" href="${subrouteUrl}">Login As User</a></td>
                        </tr>`;
                    });

                } else {
                    table.classList.add('d-none');
                    table_data.innerHTML = '';
                }
            }
        });
    }


    $(document).on('click', '#saveUser', function(e) {
        e.preventDefault();
        $("#modal_form").validate();
        if (!$("#modal_form").valid()) {
            return
        }
        var id = $('#this_data_id').val()
        $.ajax({
            type: "POST",
            url: "{{ route('superadmin.saveUser') }}",
            data: {
                id: id,
                first_name: $('#first_name').val(),
                last_name: $('#last_name').val(),
                email: $('#email').val(),
                password: $('#password').val(),
                _token: '{{ csrf_token() }}',
            },
            success: function(data) {
                $('#userTable').DataTable().draw();
                $('#userModal').modal('hide');
            }
        });
    })
</script>
@endpush
