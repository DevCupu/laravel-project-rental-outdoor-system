@extends('layouts.public')

@section('title', $alat->nama_alat)

@section('content')
    @php
        $photo = $alat->foto_path
            ? (\Illuminate\Support\Str::startsWith($alat->foto_path, ['http://', 'https://'])
                ? $alat->foto_path
                : asset('storage/' . ltrim($alat->foto_path, '/')))
            : asset('images/default-alat.png');
        $selectedPayment = old('metode_pembayaran', 'cash');
        $transferConfig = config('services.payment.transfer', []);
        $cashNote = config('services.payment.cash_note', 'Bayar cash saat pengambilan di gudang Bontang Outdoor.');
    @endphp

    <section class="bg-primary-50 pt-24 pb-16">
        <div class="max-w-6xl mx-auto px-4 grid lg:grid-cols-2 gap-8">
            <div class="space-y-6">
                <div class="rounded-[32px] overflow-hidden border border-gray-100 bg-white shadow-sm">
                    <img src="{{ $photo }}" alt="{{ $alat->nama_alat }}" class="w-full h-80 object-cover">
                    <div class="p-8 space-y-4">
                        <div>
                            <p class="text-xs tracking-[0.3em] uppercase text-primary-600">Langkah 2</p>
                            <h1 class="text-3xl font-semibold text-gray-900 mt-2">{{ $alat->nama_alat }}</h1>
                            <p class="text-sm text-gray-500">{{ $alat->kategori ?? 'Peralatan Outdoor' }}</p>
                        </div>
                        <p class="text-gray-600 leading-relaxed">{{ $alat->deskripsi ?? 'Peralatan siap pakai untuk mendukung aktivitas camping dan outdoor Anda dengan aman.' }}</p>
                        <div class="grid sm:grid-cols-3 gap-4 text-center">
                            <div class="rounded-2xl border border-gray-100 p-4">
                                <p class="text-xs uppercase tracking-[0.3em] text-gray-400">Harga / hari</p>
                                <p class="text-2xl font-semibold text-gray-900">Rp {{ number_format($alat->harga_sewa_per_hari ?? 0, 0, ',', '.') }}</p>
                            </div>
                            <div class="rounded-2xl border border-gray-100 p-4">
                                <p class="text-xs uppercase tracking-[0.3em] text-gray-400">Stok Gudang</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $alat->stok_total ?? 0 }}</p>
                            </div>
                            <div class="rounded-2xl border border-gray-100 p-4">
                                <p class="text-xs uppercase tracking-[0.3em] text-gray-400">Status</p>
                                <p class="text-2xl font-semibold {{ ($alat->stok_total ?? 0) > 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ ($alat->stok_total ?? 0) > 0 ? 'Available' : 'Habis' }}
                                </p>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-2 text-xs font-semibold">
                            <span class="px-3 py-1 rounded-full bg-primary-100 text-primary-700">Kalender sewa real-time</span>
                            <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-700">Tanpa login</span>
                            <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700">Konfirmasi WhatsApp</span>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="rounded-[32px] bg-white shadow-lg border border-gray-100 p-6 sm:p-8" data-price="{{ $alat->harga_sewa_per_hari ?? 0 }}">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-gray-400">Flow Booking</p>
                            <h2 class="text-2xl font-semibold text-gray-900">3 langkah singkat</h2>
                        </div>
                        <p class="text-sm text-gray-500"><span id="currentStep">1</span> / 3</p>
                    </div>

                    @if ($errors->any())
                        <div class="mb-4 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
                            <p class="font-semibold">Periksa kembali data Anda:</p>
                            <ul class="mt-2 space-y-1 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
                            {{ session('error') }}
                        </div>
                    @endif

                    <p id="stepWarning" class="hidden mb-4 rounded-2xl border border-rose-200 bg-rose-50 p-3 text-sm text-rose-700"></p>

                    <form action="{{ route('booking.store') }}" method="POST" id="bookingWizard" class="space-y-8">
                        @csrf
                        <input type="hidden" name="alat_id" value="{{ $alat->id }}">

                        <div class="space-y-8">
                            <div data-step="1" class="space-y-6">
                                <div>
                                    <p class="text-xs uppercase tracking-[0.3em] text-gray-400">Langkah 2</p>
                                    <h3 class="mt-2 text-xl font-semibold text-gray-900">Atur tanggal & stok</h3>
                                    <p class="text-sm text-gray-500">Pilih tanggal ambil dan tanggal kembali. Sistem otomatis menghitung durasi serta cek stok.</p>
                                </div>

                                <div class="grid gap-4 sm:grid-cols-2">
                                    <label class="text-sm font-semibold text-gray-700">Tanggal ambil
                                        <input type="date" name="tanggal_mulai" class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-primary-500 focus:ring-1 focus:ring-primary-500" required>
                                    </label>
                                    <label class="text-sm font-semibold text-gray-700">Tanggal kembali
                                        <input type="date" name="tanggal_selesai" class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-primary-500 focus:ring-1 focus:ring-primary-500" required>
                                    </label>
                                </div>

                                <div class="grid gap-4 sm:grid-cols-2">
                                    <label class="text-sm font-semibold text-gray-700">Jumlah unit
                                        <input type="number" name="jumlah" min="1" value="1" class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-primary-500 focus:ring-1 focus:ring-primary-500" required>
                                    </label>
                                    <div class="rounded-2xl border border-gray-200 p-4 bg-gray-50">
                                        <p class="text-xs uppercase tracking-[0.3em] text-gray-400">Ketersediaan</p>
                                        <p id="availabilityStatus" class="mt-2 text-lg font-semibold text-gray-900">Pilih tanggal untuk cek stok</p>
                                    </div>
                                </div>

                                <div class="rounded-2xl border border-primary-100 bg-primary-50 p-4 text-sm text-primary-700">
                                    Sistem menghitung durasi secara otomatis. Jika tanggal mulai dan selesai sama, durasi dihitung 1 hari.
                                </div>

                                <div class="flex justify-end">
                                    <button type="button" data-action="next" class="inline-flex items-center gap-2 rounded-xl bg-primary-600 px-6 py-3 text-white font-semibold hover:bg-primary-700">
                                        Lanjutkan
                                        <span aria-hidden="true">→</span>
                                    </button>
                                </div>
                            </div>

                            <div data-step="2" class="hidden space-y-6">
                                <div>
                                    <p class="text-xs uppercase tracking-[0.3em] text-gray-400">Langkah 3</p>
                                    <h3 class="mt-2 text-xl font-semibold text-gray-900">Data penyewa</h3>
                                    <p class="text-sm text-gray-500">Tanpa akun, cukup isi nama lengkap dan nomor WhatsApp aktif.</p>
                                </div>
                                <div class="grid gap-4">
                                    <label class="text-sm font-semibold text-gray-700">Nama lengkap
                                        <input type="text" name="nama_penyewa" value="{{ old('nama_penyewa') }}" class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-primary-500 focus:ring-1 focus:ring-primary-500" placeholder="cth. Andi Pratama" required>
                                    </label>
                                    <label class="text-sm font-semibold text-gray-700">Nomor WhatsApp
                                        <input type="text" name="no_hp" value="{{ old('no_hp') }}" class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-primary-500 focus:ring-1 focus:ring-primary-500" placeholder="08xxxxxxxxxx" required>
                                    </label>
                                </div>
                                <div class="space-y-4">
                                    <p class="text-sm font-semibold text-gray-700">Pilih metode pembayaran</p>
                                    <div class="grid gap-3 sm:grid-cols-2">
                                        <label data-payment-card="cash" class="flex cursor-pointer items-start gap-3 rounded-2xl border {{ $selectedPayment === 'cash' ? 'border-primary-400 bg-primary-50' : 'border-gray-200 bg-white' }} p-4">
                                            <input type="radio" name="metode_pembayaran" value="cash" class="mt-1 text-primary-600 focus:ring-primary-500" {{ $selectedPayment === 'cash' ? 'checked' : '' }}>
                                            <div>
                                                <p class="font-semibold text-gray-900">Cash di gudang</p>
                                                <p class="text-sm text-gray-500">Bayar saat pengambilan alat / saat serah terima.</p>
                                            </div>
                                        </label>
                                        <label data-payment-card="transfer" class="flex cursor-pointer items-start gap-3 rounded-2xl border {{ $selectedPayment === 'transfer' ? 'border-primary-400 bg-primary-50' : 'border-gray-200 bg-white' }} p-4">
                                            <input type="radio" name="metode_pembayaran" value="transfer" class="mt-1 text-primary-600 focus:ring-primary-500" {{ $selectedPayment === 'transfer' ? 'checked' : '' }}>
                                            <div>
                                                <p class="font-semibold text-gray-900">Transfer bank</p>
                                                <p class="text-sm text-gray-500">Transfer penuh / DP dan kirim bukti via WhatsApp.</p>
                                            </div>
                                        </label>
                                    </div>
                                    <div id="cashGuide" class="rounded-2xl border border-emerald-100 bg-emerald-50 p-4 text-sm text-emerald-800 {{ $selectedPayment === 'transfer' ? 'hidden' : '' }}">
                                        <p class="font-semibold">Bayar cash saat pickup</p>
                                        <p class="mt-1">{{ $cashNote }}</p>
                                    </div>
                                    <div id="transferGuide" class="rounded-2xl border border-primary-100 bg-primary-50 p-4 text-sm text-primary-800 {{ $selectedPayment === 'transfer' ? '' : 'hidden' }}">
                                        <p class="font-semibold">Transfer ke rekening berikut:</p>
                                        <p class="mt-1 text-base text-primary-900">
                                            {{ $transferConfig['bank'] ?? 'BCA' }} • {{ $transferConfig['account_number'] ?? '1234567890' }}<br>
                                            a.n {{ $transferConfig['account_name'] ?? 'Bontang Outdoor' }}
                                        </p>
                                        <p class="mt-2 text-xs text-primary-700">{{ $transferConfig['instructions'] ?? 'Setelah transfer, kirim bukti pembayaran via WhatsApp agar tim kami segera memverifikasi.' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <button type="button" data-action="back" class="inline-flex items-center gap-2 rounded-xl border border-gray-300 px-6 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                        ← Kembali
                                    </button>
                                    <button type="button" data-action="next" class="inline-flex items-center gap-2 rounded-xl bg-primary-600 px-6 py-3 text-white font-semibold hover:bg-primary-700">
                                        Lanjut ke review
                                        <span aria-hidden="true">→</span>
                                    </button>
                                </div>
                            </div>

                            <div data-step="3" class="hidden space-y-6">
                                <div>
                                    <p class="text-xs uppercase tracking-[0.3em] text-gray-400">Langkah 4</p>
                                    <h3 class="mt-2 text-xl font-semibold text-gray-900">Review & konfirmasi</h3>
                                    <p class="text-sm text-gray-500">Pastikan data sesuai sebelum mengirim booking. Anda akan mendapatkan kode booking otomatis.</p>
                                </div>
                                <div class="rounded-3xl border border-gray-100 bg-gray-50 p-5 space-y-4">
                                    <div class="flex justify-between text-sm text-gray-600">
                                        <span>Alat</span>
                                        <span class="font-semibold text-gray-900" id="summaryEquipment">{{ $alat->nama_alat }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm text-gray-600">
                                        <span>Tanggal</span>
                                        <span class="font-semibold text-gray-900" id="summaryDates">-</span>
                                    </div>
                                    <div class="flex justify-between text-sm text-gray-600">
                                        <span>Durasi</span>
                                        <span class="font-semibold text-gray-900" id="summaryDays">-</span>
                                    </div>
                                    <div class="flex justify-between text-sm text-gray-600">
                                        <span>Metode pembayaran</span>
                                        <span class="font-semibold text-gray-900" id="summaryPayment">
                                            {{ $selectedPayment === 'transfer' ? 'Transfer bank' : 'Cash saat pickup' }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between text-sm text-gray-600">
                                        <span>Total harga</span>
                                        <span class="text-xl font-semibold text-gray-900" id="summaryTotal">Rp 0</span>
                                    </div>
                                </div>

                                <label class="flex items-start gap-3 text-sm text-gray-600">
                                    <input type="checkbox" id="agreement" class="mt-1 rounded border-gray-300 text-primary-600 focus:ring-primary-500" required>
                                    <span>Saya setuju dengan syarat sewa Bontang Outdoor dan bersedia mengikuti instruksi pembayaran / DP setelah booking dibuat.</span>
                                </label>

                                <div class="flex items-center justify-between">
                                    <button type="button" data-action="back" class="inline-flex items-center gap-2 rounded-xl border border-gray-300 px-6 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                        ← Kembali
                                    </button>
                                    <button type="submit" id="submitBooking" class="inline-flex items-center gap-2 rounded-xl bg-primary-600 px-6 py-3 text-white font-semibold hover:bg-primary-700 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                                        Konfirmasi Booking
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        (function() {
            const wizard = document.getElementById('bookingWizard');
            if (!wizard) return;

            const steps = Array.from(wizard.querySelectorAll('[data-step]'));
            let currentStep = 1;
            const currentStepLabel = document.getElementById('currentStep');
            const warningBox = document.getElementById('stepWarning');
            const availabilityStatus = document.getElementById('availabilityStatus');
            const startInput = wizard.querySelector('input[name="tanggal_mulai"]');
            const endInput = wizard.querySelector('input[name="tanggal_selesai"]');
            const qtyInput = wizard.querySelector('input[name="jumlah"]');
            const pricePerDay = Number(wizard.parentElement.dataset.price || 0);
            const summaryDates = document.getElementById('summaryDates');
            const summaryDays = document.getElementById('summaryDays');
            const summaryTotal = document.getElementById('summaryTotal');
            const summaryPayment = document.getElementById('summaryPayment');
            const submitBtn = document.getElementById('submitBooking');
            const agreement = document.getElementById('agreement');
            const paymentInputs = wizard.querySelectorAll('input[name="metode_pembayaran"]');
            const paymentCards = wizard.querySelectorAll('[data-payment-card]');
            const cashGuide = document.getElementById('cashGuide');
            const transferGuide = document.getElementById('transferGuide');
            let availableUnits = {{ max($alat->stok_total ?? 0, 0) }};

            const today = new Date().toISOString().split('T')[0];
            startInput.min = today;
            endInput.min = today;

            startInput.addEventListener('change', () => {
                endInput.min = startInput.value || today;
                fetchAvailability();
                updateSummary();
            });

            endInput.addEventListener('change', () => {
                fetchAvailability();
                updateSummary();
            });

            qtyInput.addEventListener('input', () => {
                if (qtyInput.value < 1) qtyInput.value = 1;
                updateSummary();
            });

            agreement.addEventListener('change', () => {
                submitBtn.disabled = !agreement.checked;
            });

            paymentInputs.forEach(input => {
                input.addEventListener('change', () => {
                    updatePaymentGuide();
                    updateSummary();
                });
            });

            document.querySelectorAll('[data-action="next"]').forEach(button => {
                button.addEventListener('click', () => {
                    if (validateStep(currentStep)) {
                        goToStep(currentStep + 1);
                    }
                });
            });

            document.querySelectorAll('[data-action="back"]').forEach(button => {
                button.addEventListener('click', () => {
                    goToStep(currentStep - 1);
                });
            });

            function goToStep(step) {
                if (step < 1 || step > steps.length) return;
                steps.forEach((section, index) => {
                    section.classList.toggle('hidden', index + 1 !== step);
                });
                currentStep = step;
                currentStepLabel.textContent = step;
                hideWarning();

                if (step === 3) {
                    updateSummary();
                }
            }

            function showWarning(message) {
                if (!warningBox) return;
                warningBox.textContent = message;
                warningBox.classList.remove('hidden');
            }

            function hideWarning() {
                if (!warningBox) return;
                warningBox.classList.add('hidden');
                warningBox.textContent = '';
            }

            function validateStep(step) {
                hideWarning();

                if (step === 1) {
                    if (!startInput.value || !endInput.value) {
                        showWarning('Pilih tanggal ambil dan tanggal kembali terlebih dahulu.');
                        return false;
                    }

                    if (new Date(endInput.value) < new Date(startInput.value)) {
                        showWarning('Tanggal kembali tidak boleh lebih awal dari tanggal ambil.');
                        return false;
                    }

                    if (!availableUnits || Number(qtyInput.value) > availableUnits) {
                        showWarning('Jumlah melebihi stok yang tersedia untuk tanggal tersebut.');
                        return false;
                    }

                    return true;
                }

                if (step === 2) {
                    const name = wizard.querySelector('input[name="nama_penyewa"]').value.trim();
                    const phone = wizard.querySelector('input[name="no_hp"]').value.trim();

                    if (!name || !phone) {
                        showWarning('Nama dan nomor WhatsApp wajib diisi.');
                        return false;
                    }

                    if (!getSelectedPaymentMethod()) {
                        showWarning('Pilih metode pembayaran terlebih dahulu.');
                        return false;
                    }

                    return true;
                }

                return true;
            }

            function updateSummary() {
                const qty = Number(qtyInput.value || 1);
                const startDate = startInput.value ? new Date(startInput.value) : null;
                const endDate = endInput.value ? new Date(endInput.value) : null;

                if (!startDate || !endDate) {
                    summaryDates.textContent = '-';
                    summaryDays.textContent = '-';
                    summaryTotal.textContent = 'Rp 0';
                    if (summaryPayment) {
                        summaryPayment.textContent = getSelectedPaymentMethod() === 'transfer' ? 'Transfer bank' : 'Cash saat pickup';
                    }
                    return;
                }

                const formatter = new Intl.NumberFormat('id-ID');
                const diffMs = Math.abs(endDate - startDate);
                let days = Math.floor(diffMs / (1000 * 60 * 60 * 24));
                days = Math.max(1, days);

                summaryDates.textContent = `${startInput.value} → ${endInput.value}`;
                summaryDays.textContent = `${days} hari`;
                const total = days * qty * pricePerDay;
                summaryTotal.textContent = `Rp ${formatter.format(total)}`;

                if (summaryPayment) {
                    summaryPayment.textContent = getSelectedPaymentMethod() === 'transfer' ? 'Transfer bank' : 'Cash saat pickup';
                }
            }

            function getSelectedPaymentMethod() {
                const selected = wizard.querySelector('input[name="metode_pembayaran"]:checked');
                return selected ? selected.value : null;
            }

            function updatePaymentGuide() {
                const method = getSelectedPaymentMethod();
                if (method === 'transfer') {
                    transferGuide?.classList.remove('hidden');
                    cashGuide?.classList.add('hidden');
                } else {
                    cashGuide?.classList.remove('hidden');
                    transferGuide?.classList.add('hidden');
                }

                updatePaymentCardStyles(method);
            }

            function updatePaymentCardStyles(method) {
                paymentCards.forEach(card => {
                    if (card.dataset.paymentCard === method) {
                        card.classList.add('border-primary-400', 'bg-primary-50');
                        card.classList.remove('border-gray-200', 'bg-white');
                    } else {
                        card.classList.add('border-gray-200', 'bg-white');
                        card.classList.remove('border-primary-400', 'bg-primary-50');
                    }
                });
            }

            async function fetchAvailability() {
                if (!startInput.value || !endInput.value) {
                    availabilityStatus.textContent = 'Pilih tanggal untuk cek stok';
                    availabilityStatus.classList.remove('text-emerald-600', 'text-rose-600');
                    return;
                }

                try {
                    availabilityStatus.textContent = 'Memeriksa stok...';
                    availabilityStatus.classList.remove('text-emerald-600', 'text-rose-600');

                    const params = new URLSearchParams({
                        start: startInput.value,
                        end: endInput.value,
                    });
                    const response = await fetch(`{{ route('alat.availability', $alat) }}?${params.toString()}`);
                    const data = await response.json();
                    availableUnits = Number(data.available ?? 0);

                    if (availableUnits > 0) {
                        availabilityStatus.textContent = `Stok tersedia ${availableUnits} unit.`;
                        availabilityStatus.classList.add('text-emerald-600');
                        availabilityStatus.classList.remove('text-rose-600');
                    } else {
                        availabilityStatus.textContent = 'Tidak tersedia pada tanggal yang dipilih.';
                        availabilityStatus.classList.add('text-rose-600');
                        availabilityStatus.classList.remove('text-emerald-600');
                    }

                    if (qtyInput.value > availableUnits) {
                        qtyInput.value = availableUnits > 0 ? availableUnits : 1;
                    }
                } catch (error) {
                    console.error(error);
                    availabilityStatus.textContent = 'Gagal memeriksa stok.';
                    availabilityStatus.classList.remove('text-emerald-600');
                    availabilityStatus.classList.add('text-rose-600');
                }
            }

            goToStep(1);
            updateSummary();
            updatePaymentGuide();
        })();
    </script>
@endpush
