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
                        {{ Aire::open()->route("$base_route.update",$blog)->bind($blog) }}
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
                                <select class="select2 form-control" name="magazine_id" title="search in magazines">
                                    @if(empty(old("magazine_id")))
                                        @php
                                            $magazine = $blog->magazine;
                                        @endphp
                                        <option value="{{ $magazine->id }}">{{ $magazine->title }}</option>
                                    @else
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
                        <div class="form-group row"><label class="col-sm-2 col-form-label"
                                                           for="summernote">Content</label>
                            <div class="col-sm-10">
                                    <textarea id="summernote" name="content">
                                        @if(!empty($blog->content))
                                            {!! $blog->content !!}
                                        @elseif(!empty(old("content")))
                                            {!! old("content") !!}
                                        @endif
                                    </textarea>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Photo </label>
                            <div class="col-md-3">
                                <x-filepond name="photo_id" :value="$blog->photo?->path" types="image/*"></x-filepond>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group row"><label class="col-sm-2 col-form-label">Tags</label>
                            <div class="col-sm-10">
                                {{ Aire::input('tags')->addClass('tagsinput form-control')->placeholder("Write tag and press enter")->value(implode(",",$blog->tags)) }}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">{{ $module_name["singular"] }} Status <br/>
                                <small class="text-navy">{{ $module_name["singular"] }} status to being active or
                                    inactive</small>
                            </label>
                            <x-checkbox :value="$blog->status"></x-checkbox>
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
                    onInit: function (e) {
                        if (localStorage.hasOwnProperty("summernotedata{{ $blog->updated_at }}")) {
                            $("#summernote").summernote("code", localStorage.getItem("summernotedata{{ $blog->updated_at }}"));
                        }
                    },
                    onChange: function (contents) {
                        localStorage.setItem("summernotedata{{ $blog->updated_at }}", contents);
                    },
                    onImageUpload: function (files) {
                        for (let i = 0; i < files.length; i++) {
                            $.upload(files[i]);
                        }
                    },
                },
                height: 500,
            });
            //upload image on server from summernote
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
@push('styles')
    <link href="/panelui/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">
    <link href="/panelui/css/plugins/summernote/summernote-bs4.css" rel="stylesheet">
    <link href="/panelui/css/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet">
    <link href="/panelui/css/plugins/select2/select2.min.css" rel="stylesheet">
    <link href="/panelui/css/plugins/select2/select2-bootstrap4.min.css" rel="stylesheet">
@endpush