@extends('layouts.master')
@section('title')
    @lang('translation.dashboards')
@endsection
@section('css')
    <link href="{{ URL::asset('build/libs/jsvectormap/css/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
@php
use \Carbon\Carbon;
@endphp
    {{-- @component('components.breadcrumb')
        @slot('li_1')
            Dashboard
        @endslot
        @slot('title')
        Dashboard
        @endslot
    @endcomponent --}}
    <div class="row">
        @include('layouts.notification')

        <div class="col">
            <div class="card p-3 bg-white">
                <h3 class="text-dark">List of samples Being Processed</h3>
                <div class="col my-2">
                    <nav class="navbar">
                        <div class="container-fluid p-0">
                            <form class="d-flex" method="GET" action="{{ route('root') }}">
                                <input class="form-control me-2 main-search" type="search" placeholder="Search" aria-label="Search" name="search" value="{{ request('search') }}">
                                <button class="btn search-btn" type="submit">Search</button>
                            </form>
                            <form class="d-flex" method="GET" action="{{ route('root') }}">
                                <input type="hidden" name="search" value="{{ request('search') }}">
                                <select class="form-select sort-dropdown" aria-label="Default select example" name="sort_by" onchange="this.form.submit()">
                                    <option selected disabled>Sort By</option>
                                    <option value="test_number" {{ request('sort_by') == 'test_number' ? 'selected' : '' }}>Test Number</option>
                                    <option value="access_number" {{ request('sort_by') == 'access_number' ? 'selected' : '' }}>Access Number</option>
                                    <option value="received_date" {{ request('sort_by') == 'received_date' ? 'selected' : '' }}>Received date</option>
                                </select>

                                {{-- <select class="form-select sort-order-dropdown" aria-label="Default select example" name="sort_order" onchange="this.form.submit()">
                                    <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                                    <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Descending</option>
                                </select> --}}
                            </form>
                        </div>
                    </nav>

                </div>
                <table id="" class="table table-striped display table-responsive rounded">
                    <thead>
                        <tr>
                            <th class="rounded-start-3 ">Test #</th>
                            <th>Access #</th>
                            <th>Patient Name</th>
                            <th>Date Received</th>
                            <th class="rounded-end-3 ">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($samples as $sample)
                            <tr>
                                <td>{{ $sample->test_number }}</td>
                                <td>{{ $sample->access_number }}</td>
                                <td>{{ "{$sample->patient->first_name} {$sample->patient->surname}" }}</td>
                                <td>{{ Carbon::parse($sample->received_date)->format('d-m-Y') }}</td>



                                <td>
                                    <a href="#showModal" data-bs-toggle="modal">
                                        <span class="logo-sm">
                                            <img src="{{ URL::asset('build/images/report.png') }}" alt=""
                                                height="20">
                                        </span>
                                    </a>
                                    <li class="list-inline-item" data-bs-toggle="tooltip"
                                        data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                                        <a  id="edit-btn" class="edit-item-btn fs-5" data-id="{{ $sample->id }}"  href="#showModal" data-bs-toggle="modal"><i
                                                class="ri-pencil-fill align-bottom"></i></a>
                                    </li>
                                    <a href="">
                                        <span class="logo-sm">
                                            <img src="{{ URL::asset('build/images/Vector.png') }}" alt=""
                                                height="20">
                                        </span>
                                    </a>
                                    <a href="">
                                        <span class="logo-sm">
                                            <img src="{{ URL::asset('build/images/delete.png') }}" alt=""
                                                height="20">
                                        </span>
                                    </a>
                                </td>
                                {{-- <td>
                                        <ul class="list-inline hstack gap-2 mb-0">
                                            <li class="list-inline-item" data-bs-toggle="tooltip"
                                                data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                                                <a class="edit-item-btn" data-id="{{ $sample->id }}"  href="#showModal" data-bs-toggle="modal"><i
                                                        class="ri-pencil-fill align-bottom text-muted"></i></a>
                                            </li>
                                            <li class="list-inline-item" data-bs-toggle="tooltip"
                                                data-bs-trigger="hover" data-bs-placement="top" title="Delete">
                                                <a class="remove-item-btn" data-id="{{ $sample->id }}"  data-bs-toggle="modal"
                                                    href="#deleteRecordModal">
                                                    <i class="ri-delete-bin-fill align-bottom text-muted"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <ul class="pagination justify-content-center">
                    @if ($samples->previousPageUrl())
                        <li class="page-item previousPageUrl">
                            <a class="page-link" href="{{ $samples->previousPageUrl() }}" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                                <span class="sr-only">Previous</span>
                            </a>
                        </li>
                    @else
                        <li class="page-item previousPageUrl disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">&laquo;</a>
                        </li>
                    @endif

                    @for ($page = 1; $page <= $samples->lastPage(); $page++)
                        <li class="page-item {{ $samples->currentPage() == $page ? 'active' : '' }}">
                            <a class="page-link" href="{{ $samples->url($page) }}">{{ str_pad($page, 2, '0', STR_PAD_LEFT) }}</a>
                        </li>
                    @endfor

                    @if ($samples->nextPageUrl())
                        <li class="page-item nextPageUrl">
                            <a class="page-link" href="{{ $samples->nextPageUrl() }}" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                                <span class="sr-only">Next</span>
                            </a>
                        </li>
                    @else
                        <li class="page-item nextPageUrl disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">&raquo;</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <!-- apexcharts -->
    <script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/jsvectormap/js/jsvectormap.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/jsvectormap/maps/world-merc.js') }}"></script>
    <script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>
    <!-- dashboard init -->
    <script src="{{ URL::asset('build/js/pages/dashboard-ecommerce.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>

    <script>
        $(document).ready(function(){
        $('.edit-item-btn').click(function(){
            // Redirect to another page
            var itemId = $(this).data('id');
            // var itemId = $('#edit-btn').attr('data-id');
            console.log(itemId);
            var urlValue = '/sample/' + itemId + '/edit';
            // alert(urlValue);
            window.location.href = urlValue;
        });
    });
    </script>
@endsection
