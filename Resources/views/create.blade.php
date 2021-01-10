@extends('dashboard::master')

@section('title', $module_name["plural"])

@section('content')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title">
                        <h5> Create New {{$module_name["singular"]}} </h5>
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
                        {{ Aire::open()->route("$base_route.store") }}
                        @csrf
                        <div class="form-group row"><label class="col-sm-2 col-form-label">Title</label>
                            <div class="col-sm-10">
                                {{ Aire::input('title')->addClass('form-control') }}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group row"><label class="col-sm-2 col-form-label">Subtitle</label>
                            <div class="col-sm-10">
                                {{ Aire::input('subtitle')->addClass('form-control') }}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Magazine</label>
                            <div class="col-sm-10">
                                <select class="select2 form-control" name="magazine_id" title="search in magazine">
                                    @if(old("magazine_id"))
                                        <option value="{{ old("magazine_id") }}">{{ \Modules\Magazine\Entities\Magazine::find(old("magazine_id"))->title }}</option>
                                    @endif
                                </select>
                            </div>
                            @error("magazine_id")
                            <div class="col-sm-2"></div>
                            <div class="col-sm-10">
                                <span class="text-danger">The magazine field is required.</span>
                            </div>
                            @enderror
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group row"><label class="col-sm-2 col-form-label">Summary</label>
                            <div class="col-sm-10">
                                {{ Aire::input('summary')->addClass('form-control') }}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group row"><label class="col-sm-2 col-form-label">Content</label>
                            <div class="col-sm-10">
                                <textarea id="summernote"
                                          name="content">@if(!empty(old("content"))) {!! old("content") !!} @endif</textarea>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Photo </label>
                            <div class="col-md-3">
                                <x-filepond name="photo_id" types="image/*"></x-filepond>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group row"><label class="col-sm-2 col-form-label">Tags</label>
                            <div class="col-sm-10">
                                {{ Aire::input('tags')->addClass('tagsinput form-control')->placeholder("Write tag and press enter") }}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">{{ $module_name["singular"] }} Status <br/>
                                <small class="text-navy">{{ $module_name["singular"] }} status to being active or
                                    inactive</small>
                            </label>
                            <x-checkbox :value="old('status')"></x-checkbox>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group row">
                            <div class="col-sm-4 col-sm-offset-2">
                                <a class="btn btn-white btn-sm" href="{{ route("$base_route.index") }}">Cancel</a>
                                <button class="btn btn-primary btn-sm" type="submit">Save</button>
                            </div>
                        </div>
                        {{ Aire::close() }}
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection


@push('styles')
    <link href="/panelui/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">
    <link href="/panelui/css/plugins/summernote/summernote-bs4.css" rel="stylesheet">
    <link href="/panelui/css/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet">
    <link href="/panelui/css/plugins/select2/select2.min.css" rel="stylesheet">
    <link href="/panelui/css/plugins/select2/select2-bootstrap4.min.css" rel="stylesheet">
@endpush

@push('scripts')
    <script src="/panelui/js/plugins/jasny/jasny-bootstrap.min.js"></script>
    <!-- Select2 -->
    <script src="/panelui/js/plugins/select2/select2.full.min.js"></script>
    <!-- SUMMERNOTE -->
    <script src="/panelui/js/plugins/summernote/summernote-bs4.js"></script>
    <script src="/panelui/js/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>
    <script>
        $(document).ready(function () {
            $('#summernote').summernote({
                callbacks: {
                    onImageUpload: function (files) {
                        for (let i = 0; i < files.length; i++) {
                            $.upload(files[i]);
                        }
                    },
                    onMediaDelete: function (target) {
                        $.delete(target[0]);
                    }
                },
                height: 500,
            });

            $.upload = function (file) {
                let out = new FormData();
                out.append('file', file, file.name);

                $.ajax({
                    method: 'POST',
                    url: '{{ route("filemanager.xhr.upload") }}',
                    contentType: false,
                    cache: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: out,
                    success: function (img) {
                        $('#summernote').summernote('insertImage', img.path, img.filename);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.error(textStatus + " " + errorThrown);
                    }
                });
            };

            $('.tagsinput').tagsinput({
                tagClass: 'label label-primary'
            });

            $(".select2").select2({
                minimumInputLength: 2,
                theme: 'bootstrap4',
                cache: true,
                ajax: {
                    url: "{{ route("admin.magazines.search") }}",
                    dataType: 'json',
                    type: "POST",
                    cache: true,
                    quietMillis: 50,
                    data: function (name) {
                        return {
                            title: name.term
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.title,
                                    id: item.id
                                }
                            })
                        }
                    }
                }
            });
        });
    </script>
@endpush