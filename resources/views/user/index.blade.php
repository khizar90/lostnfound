@extends('layouts.base')
@section('title', 'Users')
@section('main', 'Accounts Management')
@section('link')
    <link rel="stylesheet" href="/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet" href="/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
    <link rel="stylesheet" href="/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css" />
    <link rel="stylesheet" href="/assets/vendor/libs/select2/select2.css" />
    <link rel="stylesheet" href="/assets/vendor/libs/formvalidation/dist/css/formValidation.min.css" />
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row g-4 mb-4">
                <div class="col-sm-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div class="content-left">
                                    <span>Session</span>
                                    <div class="d-flex align-items-center my-1">
                                        <h4 class="mb-0 me-2">21,459</h4>
                                        <span class="text-success">(+29%)</span>
                                    </div>
                                    <span>Total Users</span>
                                </div>
                                <span class="badge bg-label-primary rounded p-2">
                                    <i class="ti ti-user ti-sm"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div class="content-left">
                                    <span>Paid Users</span>
                                    <div class="d-flex align-items-center my-1">
                                        <h4 class="mb-0 me-2">4,567</h4>
                                        <span class="text-success">(+18%)</span>
                                    </div>
                                    <span>Last week analytics </span>
                                </div>
                                <span class="badge bg-label-danger rounded p-2">
                                    <i class="ti ti-user-plus ti-sm"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div class="content-left">
                                    <span>Active Users</span>
                                    <div class="d-flex align-items-center my-1">
                                        <h4 class="mb-0 me-2">19,860</h4>
                                        <span class="text-danger">(-14%)</span>
                                    </div>
                                    <span>Last week analytics</span>
                                </div>
                                <span class="badge bg-label-success rounded p-2">
                                    <i class="ti ti-user-check ti-sm"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div class="content-left">
                                    <span>Pending Users</span>
                                    <div class="d-flex align-items-center my-1">
                                        <h4 class="mb-0 me-2">237</h4>
                                        <span class="text-success">(+42%)</span>
                                    </div>
                                    <span>Last week analytics</span>
                                </div>
                                <span class="badge bg-label-warning rounded p-2">
                                    <i class="ti ti-user-exclamation ti-sm"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Users List Table -->
            <div class="card">
                <div class="card-header border-bottom">
                    <div class="d-flex justify-content-between">
                        <h5 class="card-title mb-3">Users List</h5>
                        <div class="">
                            {{-- <button class="btn btn-primary btn-sm" id="clearFiltersBtn">Clear Filter</button> --}}
                        </div>
                    </div>



                    @if (session()->has('success'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            {{ session()->get('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif



                    {{-- <div class="d-flex justify-content-between align-items-center row pb-2 gap-3 gap-md-0">
                        <div class="col-md-4 user_status">
                            <h6>Account Status</h6>

                            <select id="FilterTransaction" class="form-select text-capitalize">
                                <option value="">All</option>
                                <option value="Pending" class="text-capitalize"
                                    @if ($accountStatus === 'pending') selected @endif>Pending</option>
                                <option value="Approved" class="text-capitalize"
                                    @if ($accountStatus === 'approved') selected @endif>Approved</option>
                            </select>
                        </div>

                        <div class="col-md-4 user_role">
                            <h6>Devices</h6>
                            <select id="UserRole" class="form-select text-capitalize">
                                <option value="">All</option>
                                <option value="iOS" @if ($userDevice === 'ios') selected @endif>iOS</option>
                                <option value="android" @if ($userDevice === 'android') selected @endif>Android</option>
                            </select>
                        </div>

                        <div class="col-md-4 user_plan">
                            <h6>Category</h6>

                            <select id="FilterCategory" class="form-select text-capitalize">
                                <option value="">All</option>
                                <option value="Ophthalmology" @if ($category === 'ophthalmology') selected @endif>
                                    Ophthalmology</option>
                            </select>
                        </div>
                    </div> --}}
                </div>
                <div class="card-datatable table-responsive">
                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">



                        <table class="table border-top dataTable" id="usersTable">
                            <thead>
                                <tr>

                                    <th>#</th>
                                    <th>Device ID</th>
                                    <th>Device Name</th>
                                    <th>Time Zone</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr class="odd">
                                        <th scope="row">{{ $loop->iteration }}</th>

                                       
                                        <td class="user_name">{{ $user->device_id }}</td>


                                        <td class="user-category">{{ $user->device_name }}</td>

                                        <td class="user-category">{{ $user->timezone}}</td>







                                        <td class="" style="">
                                            <div class="d-flex align-items-center">
                                                <a href="{{ route('dashboard-user-posts' , $user->id) }}" class="text-body delete-record"><i
                                                        class="ti ti-eye"></i></a>

                                                {{-- <a href="" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal{{ $user->id }}"
                                                    class="text-body delete-record">
                                                    <i class="ti ti-trash x`ti-sm mx-2"></i>
                                                </a> --}}




                                            </div>


                                            <div class="modal fade" data-bs-backdrop='static'
                                                id="deleteModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                                                    <div class="modal-content deleteModal verifymodal">
                                                        <div class="modal-header">
                                                            <div class="modal-title" id="modalCenterTitle">Are you
                                                                sure you want to delete
                                                                this account?
                                                            </div>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="body">After delete this account user cannot
                                                                access anything in application</div>
                                                        </div>
                                                        <hr class="hr">

                                                        <div class="container">
                                                            <div class="row">
                                                                <div class="first">
                                                                    <a href="" class="btn"
                                                                        data-bs-dismiss="modal"
                                                                        style="color: #a8aaae ">Cancel</a>
                                                                </div>
                                                                <div class="second">
                                                                    <a class="btn text-center"
                                                                        href="{{ url('admin/user/delete', $user->id) }}">Delete</a>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach



                            </tbody>
                        </table>


                        <div id="paginationContainer">
                            <div class="row mx-2">
                                <div class="col-sm-12 col-md-6">
                                    <div class="dataTables_info" id="DataTables_Table_0_info" role="status"
                                        aria-live="polite">Showing {{ $users->firstItem() }} to
                                        {{ $users->lastItem() }}
                                        of
                                        {{ $users->total() }} entries</div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div class="dataTables_paginate paging_simple_numbers" id="paginationLinks">
                                        {{-- <h1>{{ @json($data) }}</h1> --}}
                                        @if ($users->hasPages())
                                            {{ $users->links('pagination::bootstrap-4') }}
                                        @endif


                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
             


              




            </div>
        </div>
    @endsection

    @section('script')

    @endsection
