@extends('layouts.app')

@section('title', 'Edit Data User Management')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Edit Data User Management</h1>
        </div>
        <form action="{{ route('user_management.update', $user_management->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">User</label>
                    <input type="text" name="role" id="role" class="form-control" value="{{ $user_management->role }}">
                </div>


                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </section>
</div>
@endsection