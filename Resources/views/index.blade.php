@extends('dashboard::master')

@section('title', $module_name["plural"])

@section('content')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title">
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="table-responsive">
                            <a href="{{ route("$base_route.create") }}" class="float-right btn btn-success mr-3 ml-3">
                                <i class="fa fa-plus"></i> Create New {{ $module_name["singular"] }}</a>
                            <table class="table table-striped table-bordered table-hover dataTables">
                                <thead>
                                <tr>
                                    <th data-toggle="true">#</th>
                                    <th>Title</th>
                                    <th>Magazine</th>
                                    <th>Author</th>
                                    <th>Status</th>
                                    <th>Create At</th>
                                    <th>Updated At</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($blogs as $key=>$item)
                                    <tr class="gradeU">
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $item->title }}</td>
                                        <td>{{ $item->magazine->title }}</td>
                                        <td><a href="{{ route("admin.users.show",$item->user->id) }}">{{ $item->user->name }}</a></td>
                                        <td>{!! status($item->status) !!}</td>
                                        <td class="center">{{ $item->created_at}}</td>
                                        <td class="center">{{ $item->updated_at}}</td>
                                        <td class="text-right">
                                            <div class="btn-group">
                                                <a type="button" href="{{ route($base_route.".show",[$item->id]) }}"
                                                   class="btn btn-white">
                                                    View <i class="fa fa-eye"></i>
                                                </a>
                                                <a type="button" href="{{ route($base_route.".edit",[$item->id]) }}"
                                                   class="btn btn-white">
                                                    Edit <i class="fa fa-edit"></i>
                                                </a>
                                                <x-remove-button :url='route($base_route.".destroy",$item)'></x-remove-button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@push('styles')
    <!-- Sweet Alert -->
    <link href="{{ url("panelui/css/plugins/sweetalert/sweetalert.css") }}" rel="stylesheet">
@endpush
@push('scripts')
    <script src="{{ url("panelui/js/plugins/dataTables/datatables.min.js") }}"></script>
    <script src="{{ url("panelui/js/plugins/dataTables/dataTables.bootstrap4.min.js") }}"></script>
    <!-- Sweet alert -->
    <script src="{{ url("panelui/js/plugins/sweetalert/sweetalert.min.js") }}"></script>
    <!-- Page-Level Scripts -->
    <script>
        $(document).ready(function () {
            $('.dataTables').DataTable({
                pageLength: 10,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    {extend: 'copy'},
                    {extend: 'csv'},
                    {extend: 'excel', title: '{{ $module_name["plural"] ." Lists" }}'},
                    {extend: 'pdf', title: '{{ $module_name["plural"] ." Lists" }}'},
                    {
                        extend: 'print',
                        customize: function (win) {
                            $(win.document.body).addClass('white-bg');
                            $(win.document.body).css('font-size', '10px');

                            $(win.document.body).find('table')
                                .addClass('compact')
                                .css('font-size', 'inherit');
                        }
                    }
                ]
            });
        });
    </script>
@endpush