@extends('dashboard::master')

@section('title', $module_name["plural"])

@section('content')
    <div class="wrapper wrapper-content animated fadeInRight article">
        <div class="row justify-content-md-center">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-content">
                        <a href="{{ route("$base_route.edit",$blog) }}" class="float-right btn btn-success mr-3 ml-3">
                            <i class="fa fa-edit"></i> Edit this {{ $module_name["singular"] }}</a>
                        <div class="text-center article-title">
                            <h1>
                                {{ $blog->title }}
                            </h1>
                            <p>
                                {{ $blog->subtitle }}
                            </p>
                        </div>
                        <div class="text-left article-title">
                            <span class="text-muted"> Summary :</span>
                            <p>
                                {{ $blog->summary }}
                            </p>
                        </div>
                        {!! $blog->content !!}
                        <div class="row my-4">
                            <div class="col-md-6">
                                <h5><i class="fa fa-tags"></i> Tags:</h5>
                                @foreach($blog->tags ?? [] as $tag)
                                    <span class="label label-info">{{ $tag }}</span>
                                @endforeach
                            </div>
                            <div class="col-md-2">
                                <h5><i class="fa fa-user"></i> Author:</h5>
                                <span class="label label-success">{{ $blog->user->name }}</span>
                            </div>
                            <div class="col-md-2">
                                <h5><i class="fa fa-clock-o"></i> Create at:</h5>
                                <span class="text-muted"> {{ $blog->created_at?->toDayDateTimeString() }}</span>
                            </div>
                            <div class="col-md-2">
                                <div class="small text-left">
                                    <h5><i class="fa fa-line-chart"></i> Stats:</h5>
                                    <div> <i class="fa fa-comments-o"> </i> {{ count($blog?->comments ?? []) }} comments </div>
                                </div>
                            </div>
                        </div>

                        @if(collect(app("enableModules"))->contains("name","Comment"))
                            <div class="row">
                                <div class="col-lg-12">
                                    <h2>Comments:</h2>
                                    @forelse($blog?->comments ?? [] as $comment)
                                        <div class="social-feed-box">
                                        <div class="social-avatar">
                                            <a href="#" class="float-left">
                                                <x-img :src="$comment->user?->avatar?->path"></x-img>
                                            </a>
                                            <div class="media-body">
                                                <a href="{{ route("admin.users.show",$comment->user) }}">
                                                    {{ $comment->user->name }}
                                                </a>
                                                <small class="text-muted">{{ $comment->created_at->toDayDateTimeString() }}</small>
                                            </div>
                                        </div>
                                        <div class="social-body">
                                            <p> {{ $comment->message }} </p>
                                            @foreach($comment->comments ?? [] as $item)
                                                <div class="social-body">
                                                    <p><a class="text-navy" href="{{ route('admin.users.show', $item->user->id) }}">{{ $item->user->name }}: </a> {{ $item->message }} </p>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                        @empty
                                        Comment is Empty
                                    @endforelse
                                </div>
                            </div>
                        @endif
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

@endpush