@extends('layouts.app')

@section('title', 'Unit Motor')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Unit Motor untuk {{ $stock->motor->nama_motor }}</h1>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-header">
                        <h4>Tambah Unit Motor Baru</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('stock.add-motor-unit', $stock->id) }}" method="POST">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-md-5">
                                    <input type="text" name="nomor_rangka" class="form-control"
                                        placeholder="Nomor Rangka" required>
                                </div>
                                <div class="form-group col-md-5">
                                    <input type="text" name="nomor_mesin" class="form-control" placeholder="Nomor Mesin"
                                        required>
                                </div>
                                <div class="form-group col-md-2">
                                    <button type="submit" class="btn btn-primary btn-block">Tambah Unit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        <h4>Daftar Unit Motor</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nomor Rangka</th>
                                        <th>Nomor Mesin</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($stock->motorUnits as $unit)
                                        <tr>
                                            <td>{{ $unit->nomor_rangka }}</td>
                                            <td>{{ $unit->nomor_mesin }}</td>
                                            <td>
                                                <span
                                                    class="badge badge-{{ $unit->status == MotorUnit::STATUS_AVAILABLE ? 'success' : 'danger' }}">
                                                    {{ $unit->formatted_status }}
                                                </span>
                                            </td>
                                            <td>
                                                <form action="{{ route('stock.update-motor-unit-status', $unit->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select name="status"
                                                        class="form-control form-control-sm d-inline-block w-auto"
                                                        onchange="this.form.submit()">
                                                        <option value="{{ MotorUnit::STATUS_AVAILABLE }}"
                                                            {{ $unit->status == MotorUnit::STATUS_AVAILABLE ? 'selected' : '' }}>
                                                            Available</option>
                                                        <option value="{{ MotorUnit::STATUS_SOLD }}"
                                                            {{ $unit->status == MotorUnit::STATUS_SOLD ? 'selected' : '' }}>
                                                            Sold</option>
                                                    </select>
                                                </form>
                                                <form action="{{ route('stock.delete-motor-unit', $unit->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Anda yakin ingin menghapus unit ini?')">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
