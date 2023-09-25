@extends('admin.layouts.app')
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
                            <h5 class="mb-3">Person Vise Enquiry</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="display" id="reportTable">
                                    <thead>
                                        <tr>
                                            <th>Person Name</th>
                                            <th>Total Enquiry</th>
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
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#reportTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.report.person.enquiry') }}",
                    data: function(d) {},
                },
                columns: [
                    {
                        data: 'person_name',
                        name: 'person_name'
                    },
                    {
                        data: 'total_inquiries',
                        name: 'total_inquiries'
                    },
                ]
            });
        });
    </script>
@endpush
