@extends('layouts.app')

@section('title', 'Update Stock Details')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Update Stock Details</h1>
            </div>

            <div class="section-body">
                <form action="{{ route('stock.update-details', ['id' => $stock->id, 'type' => $type]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card">
                        <div class="card-header">
                            <h4>{{ $type === 'motor' ? 'Motor' : 'Spare Part' }} Details</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="nama_item">{{ $type === 'motor' ? 'Nama Motor' : 'Nama Spare Part' }}</label>
                                <input type="text" id="nama_item" class="form-control" 
                                    value="{{ $type === 'motor' ? ($stock->motor->nama_motor ?? 'N/A') : ($stock->sparePart->nama_spare_part ?? 'N/A') }}"
                                    readonly>
                            </div>

                            @if($type === 'spare_part')
                                <div class="form-group">
                                    <label for="jumlah">Jumlah (dalam box)</label>
                                    <input type="number" class="form-control" id="jumlah" name="jumlah" 
                                        value="{{ old('jumlah', ceil($stock->jumlah / $stock->sparePart->unit_satuan)) }}" required>
                                    <small class="form-text text-muted">1 box = {{ $stock->sparePart->unit_satuan }} unit</small>
                                </div>
                            @endif

                            <div class="form-group">
                                <label for="harga_beli">Harga Beli (per unit)</label>
                                <input type="text" id="harga_beli" class="form-control" 
                                    value="{{ number_format($stock->harga_beli, 2, ',', '.') }}"
                                    readonly>
                            </div>

                            <div class="form-group">
                                <label for="harga_jual">Harga Jual (per unit)</label>
                                <input type="text" name="harga_jual" id="harga_jual" class="form-control"
                                    placeholder="Masukkan Harga Jual"
                                    value="{{ old('harga_jual', number_format($stock->harga_jual, 2, ',', '.')) }}"
                                    required>
                                @error('harga_jual')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('stock.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cleave.js/1.6.0/cleave.min.js"></script>
    <script>
        $(document).ready(function() {
            var hargaBeliCleave = new Cleave('#harga_beli', {
                numeral: true,
                numeralThousandsGroupStyle: 'thousand',
                numeralDecimalMark: ',',
                delimiter: '.'
            });

            var hargaJualCleave = new Cleave('#harga_jual', {
                numeral: true,
                numeralThousandsGroupStyle: 'thousand',
                numeralDecimalMark: ',',
                delimiter: '.',
                onValueChanged: function(e) {
                    $('#harga_jual').val(e.target.value);
                }
            });

            $('#harga_jual').on('focus', function() {
                $(this).val('');
            });

            $('#harga_jual').on('blur', function() {
                if ($(this).val() === '') {
                    $(this).val(hargaJualCleave.getFormattedValue());
                }
            });

            $('form').on('submit', function() {
                $('#harga_jual').val(hargaJualCleave.getRawValue());
            });
        });
    </script>
@endpush