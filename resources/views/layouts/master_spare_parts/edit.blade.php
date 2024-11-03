@extends('layouts.app')

@section('title', 'Edit Spare Part')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Spare Part</h1>
            </div>
            <div class="section-body">
                <form action="{{ route('master_spare_parts.update', $master_spare_part->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="nama_spare_part">Nama Spare Part</label>
                        <input type="text" name="nama_spare_part" class="form-control" value="{{ $master_spare_part->nama_spare_part }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </section>
    </div>
@endsection
