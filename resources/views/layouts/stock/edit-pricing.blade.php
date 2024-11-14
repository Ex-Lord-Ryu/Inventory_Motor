@extends('layouts.app')

@section('title', 'Update Stok Details')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Update Stok Details</h1>
            </div>

            <div class="section-body">
                <form action="{{ route('stock.update-details', $stock->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card">
                        <div class="card-header">
                            <h4>Harga dan Diskon</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="harga_jual">Harga Jual</label>
                                <input type="text" name="harga_jual" id="harga_jual" class="form-control"
                                    placeholder="Masukkan Harga Jual"
                                    value="{{ old('harga_jual', number_format($stock->harga_jual, 2, ',', '.')) }}"
                                    required>
                                @error('harga_jual')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="diskon_persen">Diskon Persen</label>
                                <input type="number" name="diskon_persen" id="diskon_persen" class="form-control"
                                    placeholder="Masukkan Diskon Persen"
                                    value="{{ old('diskon_persen', $stock->diskon_persen) }}" min="0" max="100"
                                    step="0.01">
                                @error('diskon_persen')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="diskon_nilai">Diskon Nilai</label>
                                <input type="text" name="diskon_nilai" id="diskon_nilai" class="form-control"
                                    placeholder="Masukkan Diskon Nilai"
                                    value="{{ old('diskon_nilai', number_format($stock->diskon_nilai, 2, ',', '.')) }}"
                                    readonly>
                                @error('diskon_nilai')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="harga_jual_diskon">Harga Jual Setelah Diskon</label>
                                <input type="text" name="harga_jual_diskon" id="harga_jual_diskon" class="form-control"
                                    placeholder="Harga Jual Setelah Diskon"
                                    value="{{ old('harga_jual_diskon', number_format($stock->harga_jual_diskon, 2, ',', '.')) }}"
                                    readonly>
                                @error('harga_jual_diskon')
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
            // Format currency inputs
            var hargaJualCleave = new Cleave('#harga_jual', {
                numeral: true,
                numeralThousandsGroupStyle: 'thousand',
                numeralDecimalMark: ',',
                delimiter: '.'
            });

            var diskonNilaiCleave = new Cleave('#diskon_nilai', {
                numeral: true,
                numeralThousandsGroupStyle: 'thousand',
                numeralDecimalMark: ',',
                delimiter: '.'
            });

            var hargaJualDiskonCleave = new Cleave('#harga_jual_diskon', {
                numeral: true,
                numeralThousandsGroupStyle: 'thousand',
                numeralDecimalMark: ',',
                delimiter: '.'
            });

            function calculateDiscount() {
                var hargaJual = parseFloat(hargaJualCleave.getRawValue()) || 0;
                var diskonPersen = parseFloat($('#diskon_persen').val()) || 0;

                var diskonNilai = hargaJual * (diskonPersen / 100);
                var hargaJualDiskon = hargaJual - diskonNilai;

                diskonNilaiCleave.setRawValue(diskonNilai);
                hargaJualDiskonCleave.setRawValue(hargaJualDiskon);
            }

            $('#harga_jual, #diskon_persen').on('input', calculateDiscount);

            // Initial calculation
            calculateDiscount();

            // Before form submission, convert formatted values back to raw numbers
            $('form').on('submit', function() {
                $('#harga_jual').val(hargaJualCleave.getRawValue());
                $('#diskon_nilai').val(diskonNilaiCleave.getRawValue());
                $('#harga_jual_diskon').val(hargaJualDiskonCleave.getRawValue());
            });
        });
    </script>
@endpush
