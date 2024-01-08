@extends('layouts.base')
@section('title', 'User Posts')
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
                        <h5 class="card-title mb-3">User Posts</h5>
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



                        {{-- <table class="table border-top dataTable" id="postsTable">
                            <thead>
                                <tr>

                                    <th>#</th>
                                    <th>Title</th>
                                    <th>image</th>
                                    <th>Description</th>
                                    <th>type</th>
                                    <th>Location</th>
                                    <th>timestap</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($posts as $post)
                                    <tr class="odd">
                                        <th scope="row">{{ $loop->iteration }}</th>


                                        <td class="user_name">{{ $post->title ?: 'No Title' }}</td>


                                        <td class="user-category">{{ $post->description ?: 'No Description' }} </td>
                                        <td class="user_name"><img class="rounded" src="{{ asset($post->image) }}"
                                                alt="" width="100" height="100"></td>
                                        <td class="user-category">{{ $post->type }}</td>


                                        <td class="user-category">{{ $post->location }}</td>
                                        <td class="user-category">{{ $post->created_at }}</td>







                                        <td class="" style="">
                                            <div class="d-flex align-items-center">
                                               

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
                        </table> --}}


                        @foreach ($posts as $post)


                                <div class="d-flex justify-content-center bg-white mb-2 mt-3" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal" onclick="card()">
                                    <div>
                                        <div class="card border-0" style="width: 19.5rem;">
                                            <div class="loadercenter">
                                                {{-- <div id="loader" class="spinner-border" role="status"></div> --}}

                                                <img src="{{ asset($post->image) }}"
                                                    class="card-img-top object-fit-cover" alt="Your Image" id="image"
                                                    height="200px" width="100%" style="object-fit: cover">
                                            </div>
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between">
                                                    <h6 class="card-title">{{ $post->title ?: 'No Title' }}</h6>
                                                    <a href="" data-bs-toggle="modal"
                                                        data-bs-target="#deleteModal{{ $post->id }}"
                                                        class="text-body delete-record">
                                                        <i class="ti ti-trash x`ti-sm mx-2"></i>
                                                    </a>
                                                </div>
                                                <h5 class="card-title mb-0">Description:</h5>
                                                <div class="card-text ellipsis text-body mb-2" id="cardPara">
                                                    {{ $post->description ?: 'No Description' }}</div>

                                                <h5 class="card-title mb-0">Type:</h5>

                                                <div class="card-text ellipsis text-body mb-2" id="cardPara">
                                                    {{ $post->type }}</div>


                                                <h5 class="card-title mb-0">Location:</h5>

                                                <div class="d-flex gap-2 mb-2 align-items-center">
                                                    <i class="fa-solid fa-location-dot" style="color: #408a0f;"></i>
                                                    <div id="locationPara ">{{ $post->location }}</div>
                                                </div>

                                                <h5 class="card-title mb-0">Time:</h5>

                                                <div class="card-text ellipsis text-body mb-2" id="cardPara">
                                                    {{ $post->created_at }}</div>

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
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                @endforeach


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
