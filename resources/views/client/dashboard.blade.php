@extends('backend.layouts.app')

@section('head')
    <title>Dashboard | {{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('global/vendor/flag-icon-css/flag-icon.css') }}">
    <link rel="stylesheet" href="{{ asset('global/vendor/asscrollable/asScrollable.min.css') }}">
    <link rel='stylesheet' href='{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css') }}'>
@endsection

@section('title')
    Dashboard
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endpush

@section('content')
    <div class="row">
        <div class="row">
            <div class="col-md-12">
                <div class="m-b-15 clearfix">
                    <form id="form" class="pull-right" method="GET" action="{{ URL::current() }}">
                        <select name="department_id" class="department-switch show-tick" data-plugin="selectpicker">
                            <option value="All">All</option>
                            @foreach ($departments as $department)
                            <option value="{{ $department->id }}" {{ $department->id == $departmentId ? 'selected' : '' }}>{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xxl-12 col-lg-12 col-xs-12">
                <div class="card card-shadow card-responsive">
                    {!! $lineStudentByYear->container() !!}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xxl-12 col-lg-12 col-xs-12">
                <div class="card card-shadow card-responsive">
                    {!! $lineStudentStatic->container() !!}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xxl-12 col-lg-12 col-xs-12">
                <div class="card card-shadow card-responsive">
                    {!! $dpuChart->container() !!}
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-xs-12 masonry-item">
            <div class="panel" id="messge">
                <div class="panel-heading">
                    <div class="panel-actions panel-actions-keep">
                        <a class="text-action" href="{{ route('client.management.user.index') }}">
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
                                                <b>Role:</b> {{ optional($user->role)->display_name }}. <b>Last Login:</b> {{ optional($user->last_login)->diffForHumans() }}
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>

    {!! $lineStudentByYear->script() !!}
    {!! $lineStudentStatic->script() !!}
    {!! $dpuChart->script() !!}
    <script type="text/javascript">
        $(document).ready(function() {
            $('.department-switch').change(function() {
                $('#form').submit();
            })
        });
    </script>
@endsection
