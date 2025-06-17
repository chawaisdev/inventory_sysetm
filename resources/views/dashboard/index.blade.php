@extends('layouts.app')
@section('title', 'Points Index')

@section('body')
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="">Home</a></li>
                    <li class="breadcrumb-item active">Points</li>
                </ol>
            </nav>
        </div>

        <h3>Welcome back, {{ $userName }}!</h3>

        <div class="row mb-4">
            <div class="col-xxl-4 col-lg-4 col-md-4">
                <div class="card custom-card overflow-hidden">
                    <div class="card-body">
                        <div class="d-flex align-items-top justify-content-between">
                            <div>
                                <span class="avatar avatar-md avatar-rounded bg-primary">
                                    <i class="ti ti-users fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div>
                                        <p class="text-muted mb-0">Total Users</p>
                                        <h4 class="fw-semibold mt-1">{{ number_format($totalUsers) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-4 col-lg-4 col-md-4">
                <div class="card custom-card overflow-hidden">
                    <div class="card-body">
                        <div class="d-flex align-items-top justify-content-between">
                            <div>
                                <span class="avatar avatar-md avatar-rounded bg-success">
                                    <i class="ti ti-book fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div>
                                        <p class="text-muted mb-0">Total Chapters</p>
                                        <h4 class="fw-semibold mt-1">{{ number_format($totalChapters) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-4 col-lg-4 col-md-4">
                <div class="card custom-card overflow-hidden">
                    <div class="card-body">
                        <div class="d-flex align-items-top justify-content-between">
                            <div>
                                <span class="avatar avatar-md avatar-rounded bg-warning">
                                    <i class="ti ti-coin fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div>
                                        <p class="text-muted mb-0">Total Points</p>
                                        <h4 class="fw-semibold mt-1">{{ number_format($totalRewards) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            @forelse($points as $point)
                <div class="col-md-6 mb-4">
                    <div class="d-flex shadow-sm bg-white rounded overflow-hidden" style="border-radius: 16px;">
                        <div class="p-3 flex-grow-1">
                            <h5 class="mb-2">{{ $point->chapter->name ?? 'Chapter' }}</h5>
                            <p class="mb-1"><strong>User:</strong> {{ $point->user->name ?? 'Unknown User' }}</p>
                            <p class="mb-1"><strong>XP:</strong> {{ $point->xp }}</p>
                            <p class="mb-1"><strong>Cash:</strong> ${{ $point->cash }}</p>
                            <p class="mb-1"><strong>Total Badges:</strong> {{ $point->total_badges }}</p>
                            <small class="text-muted">Date: {{ $point->created_at->format('d M Y') }}</small>
                        </div>
                        <div style="min-width: 180px; max-width: 180px;">
                            <img src="{{ asset('assets/images/others/reward.jpg') }}" alt="Chapter Image"
                                class="img-fluid h-100" style="object-fit: cover; width: 100%; height: 100%;">
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <div class="alert alert-info" role="alert">
                        No points found.
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection
