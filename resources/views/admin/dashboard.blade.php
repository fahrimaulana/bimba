@extends('backend.layouts.app')

@section('head')
    <title>Dashboard | {{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('global/vendor/flag-icon-css/flag-icon.css') }}">
    <link rel="stylesheet" href="{{ asset('global/vendor/asscrollable/asScrollable.min.css') }}">
@endsection

@section('title')
    Dashboard
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endpush

@section('content')
    <div class="row">
        <div class="col-lg-6 col-xs-12 masonry-item">
            <div class="panel" id="messge">
                <div class="panel-heading">
                    <div class="panel-actions panel-actions-keep">
                        <a class="text-action" href="{{ route('admin.management.user.index') }}">
                            <i class="icon wb-eye" data-toggle="tooltip" data-original-title="View All User" aria-hidden="true" ></i>
                        </a>
                    </div>
                    <h3 class="panel-title">Recent User</h3>
                </div>
                <div class="panel-body">
                    <ul class="list-group list-group-full h-250" data-plugin="scrollable">
                        <div data-role="container">
                            <div data-role="content">
                                @foreach ($users as $user)
                                <li class="list-group-item">
                                    <div class="media">
                                        <div class="media-left">
                                            <span class="avatar avatar-online">
                                            <img src="{{ asset('global/portraits/5.jpg') }}" alt="">
                                            <i></i>
                                            </span>
                                        </div>
                                        <div class="media-body">
                                            <h5 class="list-group-item-heading">
                                                <small class="pull-xs-right">{{ $user->created_at->diffForHumans() }}</small>
                                                {{ $user->name }}
                                            </h5>
                                            <p class="list-group-item-text">
                                                <b>Role:</b> {{ $user->role->display_name }}. <b>Last Login:</b> {{ optional($user->last_login)->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </div>
                        </div>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <script src="{{ asset('global/js/Plugin/asscrollable.min.js') }}"></script>
@endsection