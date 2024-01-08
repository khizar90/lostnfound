@extends('layouts.base')
@section('title', 'posts')
@section('main', 'Posts Management')
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

            <!-- posts List Table -->
            <div class="card">
                <div class="card-header border-bottom">
                    <div class="d-flex justify-content-between">
                        <h5 class="card-title mb-3">Posts</h5>
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




                </div>
                <div class="card-datatable table-responsive">
                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">



                        <table class="table border-top dataTable" id="postsTable">
                            <thead>
                                <tr>

                                    <th>#</th>
                                    <th>Repoted By</th>
                                    <th>Title</th>
                                    <th>image</th>
                                    <th>type</th>
                                    <th>reason</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($posts as $post)
                                    <tr class="odd">
                                        <th scope="row">{{ $loop->iteration }}</th>

                                        <td class="user-category">{{ $post->user->device_id ?: 'No ID' }} </td>

                                        <td class="user_name">{{ $post->post->title ?: 'No Title' }}</td>


                                        <td class="user_name"><img class="rounded" src="{{ asset($post->post->image) }}"
                                                alt="" width="100" height="100"></td>
                                        <td class="user-category">{{ $post->post->type }}</td>
                                        <td class="user-category">{{ $post->reason }}</td>









                                        <td class="" style="">
                                            <div class="d-flex align-items-center">
                                                {{-- <a href="" class="text-body delete-record"><i
                                                        class="ti ti-eye"></i></a> --}}

                                                <a href="" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal{{ $post->id }}"
                                                    class="text-body delete-record">
                                                    <i class="ti ti-trash x`ti-sm mx-2"></i>
                                                </a>




                                            </div>


                                            <div class="modal fade" data-bs-backdrop='static'
                                                id="deleteModal{{ $post->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                                                    <div class="modal-content deleteModal verifymodal">
                                                        <div class="modal-header">
                                                            <div class="modal-title" id="modalCenterTitle">Are you
                                                                sure you want to delete
                                                                this post?
                                                            </div>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="body">After delete this post user cannot
                                                                see this post</div>
                                                        </div>
                                                        <hr class="hr">

                                                        <div class="container">
                                                            <div class="row">
                                                                <div class="first">
                                                                    <a href="" class="btn" data-bs-dismiss="modal"
                                                                        style="color: #a8aaae ">Cancel</a>
                                                                </div>
                                                                <div class="second">
                                                                    <a class="btn text-center"
                                                                        href="{{ route('dashboard-delete-post', $post->id) }}">Delete</a>
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
                                        aria-live="polite">Showing {{ $posts->firstItem() }} to
                                        {{ $posts->lastItem() }}
                                        of
                                        {{ $posts->total() }} entries</div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div class="dataTables_paginate paging_simple_numbers" id="paginationLinks">
                                        {{-- <h1>{{ @json($data) }}</h1> --}}
                                        @if ($posts->hasPages())
                                            {{ $posts->links('pagination::bootstrap-4') }}
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
