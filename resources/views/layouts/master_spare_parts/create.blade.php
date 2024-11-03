@extends('layouts.app')

@section('title', 'Add Spare Part')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Add Spare Part</h1>
            </div>
            <div class="section-body">
                <form action="{{ route('master_spare_parts.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="nama_spare_part">Nama Spare Part</label>
                        <input type="text" name="nama_spare_part" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </section>
    </div>
@endsection
