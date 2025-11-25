@extends('layouts.app')

@section('content')

<style>
    .profile-container {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        padding: 30px;
        max-width: 900px;
        margin: 40px auto;
    }
    .profile-left {
        text-align: center;
        border-right: 1px solid #eee;
        padding-right: 25px;
    }
    .profile-left img {
        width: 130px;
        height: 130px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 15px;
    }
    .profile-left h3 {
        font-weight: 700;
        margin: 10px 0 5px 0;
    }
    .profile-left p {
        color: #777;
        margin-bottom: 20px;
    }
    .profile-right {
        padding-left: 30px;
    }
    .profile-info label {
        font-weight: 600;
        color: #333;
    }
    .profile-info .value {
        color: #555;
    }
</style>

@php
    $user = auth()->user();
    $isAdmin = $user->role === 'admin';
    $isPtk = $user->role === 'ptk';
    $isSiswa = $user->role === 'siswa';
@endphp

<div class="profile-container">
    <div class="row align-items-center">
        <div class="col-md-4 profile-left">
            <img src="{{ asset('images/profile.png') }}" alt="Profile Picture">
        </div>

        <div class="col-md-8 profile-right">
            <div class="profile-info">
                <div class="row mb-3 border">
                    <div class="col-md-4">
                        <label>Nama</label>
                    </div>
                    <div class="col-md-8 value">
                        {{ $user->name }}
                    </div>
                </div>
                <div class="row mb-3 border">
                    <div class="col-md-4">
                        <label>Email</label>
                    </div>
                    <div class="col-md-8 value">
                        {{ $user->email }}
                    </div>
                </div>
                <div class="row mb-3 border">
                    <div class="col-md-4">
                        <label>Role</label>
                    </div>
                    <div class="col-md-8 value">
                        {{ $user->role }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
