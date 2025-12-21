@extends('layouts.public')

@section('content')
	<div class="bg-primary-50/60 py-10 px-4">
		<div class="max-w-4xl mx-auto space-y-6">
			<div class="rounded-2xl bg-white p-6 shadow-sm">
				<p class="text-xs uppercase tracking-[0.3em] text-primary-500">Form Booking</p>
				<h1 class="mt-2 text-3xl font-semibold text-gray-900">Booking banyak alat dalam 3 langkah ringan.</h1>
				<p class="mt-2 text-sm text-gray-600">1) Isi identitas & tanggal, 2) pilih alat sebanyak yang Anda mau, 3) pilih metode bayar lalu kirim.</p>
			</div>

			@if ($errors->any())
				<div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
					<p class="font-semibold">Periksa kembali input Anda:</p>
					<ul class="mt-2 list-disc list-inside space-y-1">
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif

			<div class="rounded-2xl bg-white p-6 shadow">
				<form action="{{ route('booking.store') }}" method="POST" class="space-y-8">
					@csrf

					<section class="space-y-4">
						<div class="flex items-center gap-3">
							<span class="flex h-8 w-8 items-center justify-center rounded-full bg-primary-100 text-sm font-semibold text-primary-700">1</span>
							<h2 class="text-lg font-semibold text-gray-900">Identitas pemesan</h2>
						</div>
						<div class="grid gap-4 md:grid-cols-2">
							<label class="text-sm font-medium text-gray-700">Nama lengkap
								<input type="text" name="nama_penyewa" value="{{ old('nama_penyewa') }}" class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-primary-500 focus:ring-1 focus:ring-primary-500" placeholder="cth. Andi Pratama" required>
							</label>
							<label class="text-sm font-medium text-gray-700">Nomor WhatsApp aktif
								<input type="text" name="no_hp" value="{{ old('no_hp') }}" class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-primary-500 focus:ring-1 focus:ring-primary-500" placeholder="08xxxxxxxxxx" required>
							</label>
						</div>
					</section>

					<section class="space-y-4">
						<div class="flex items-center gap-3">
							<span class="flex h-8 w-8 items-center justify-center rounded-full bg-primary-100 text-sm font-semibold text-primary-700">2</span>
							<h2 class="text-lg font-semibold text-gray-900">Tanggal sewa</h2>
						</div>
						<div class="grid gap-4 md:grid-cols-2">
							<label class="text-sm font-medium text-gray-700">Tanggal mulai
								<input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-primary-500 focus:ring-1 focus:ring-primary-500" required>
							</label>
							<label class="text-sm font-medium text-gray-700">Tanggal kembali
								<input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-primary-500 focus:ring-1 focus:ring-primary-500" required>
							</label>
						</div>
						<p class="rounded-xl bg-gray-50 px-4 py-3 text-sm text-gray-600">Durasi dihitung otomatis. Kalau tanggal sama, sistem tetap anggap 1 hari sewa.</p>
					</section>

					<section class="space-y-4">
						<div class="flex items-center gap-3">
							<span class="flex h-8 w-8 items-center justify-center rounded-full bg-primary-100 text-sm font-semibold text-primary-700">3</span>
							<div>
								<h2 class="text-lg font-semibold text-gray-900">Pilih barang</h2>
								<p class="text-sm text-gray-500">Tambah baris baru jika ingin kombinasi tenda, matras, kompor, dll.</p>
							</div>
						</div>

						<div id="alat-list" class="space-y-3">
							<div class="alat-item rounded-2xl border border-gray-200 bg-gray-50 px-4 py-4">
								<div class="flex items-center justify-between text-sm font-semibold text-gray-700">
									<span class="alat-item-number">Barang #1</span>
									<button type="button" class="remove-alat text-gray-400 hover:text-rose-600" title="Hapus baris">
										&times;
									</button>
								</div>
								<div class="mt-3 grid gap-4 md:grid-cols-2">
									<label class="text-sm font-medium text-gray-700">Nama alat
										<select name="alat_id[]" class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-primary-500 focus:ring-1 focus:ring-primary-500" required>
											<option value="">-- Pilih alat --</option>
											@foreach ($alats as $alat)
												<option value="{{ $alat->id }}">{{ $alat->nama_alat }} (stok {{ $alat->stok_total }})</option>
											@endforeach
										</select>
									</label>
									<label class="text-sm font-medium text-gray-700">Jumlah unit
										<input type="number" name="jumlah[]" min="1" class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-primary-500 focus:ring-1 focus:ring-primary-500" placeholder="cth. 2" required>
									</label>
								</div>
							</div>
						</div>

						<button type="button" id="add-alat" class="inline-flex items-center gap-2 rounded-xl border border-dashed border-primary-300 px-4 py-2 text-sm font-semibold text-primary-700">
							<span class="text-lg">+</span>
							Tambah barang lain
						</button>
						<p class="text-xs text-gray-500">Stok dicek ulang saat submit. Jika salah satu barang habis, sistem memberi info stok tersisa.</p>
					</section>

					<section class="space-y-4">
						<div class="flex items-center gap-3">
							<span class="flex h-8 w-8 items-center justify-center rounded-full bg-primary-100 text-sm font-semibold text-primary-700">4</span>
							<h2 class="text-lg font-semibold text-gray-900">Metode pembayaran</h2>
						</div>
						<div class="grid gap-4 sm:grid-cols-2">
							<label data-payment-card="cash" class="flex cursor-pointer items-start gap-3 rounded-2xl border {{ old('metode_pembayaran', 'cash') === 'cash' ? 'border-primary-400 bg-primary-50' : 'border-gray-200 bg-white' }} p-4">
								<input type="radio" name="metode_pembayaran" value="cash" class="mt-1 text-primary-600 focus:ring-primary-500" {{ old('metode_pembayaran', 'cash') === 'cash' ? 'checked' : '' }}>
								<div>
									<p class="font-semibold text-gray-900">Bayar cash saat pickup</p>
									<p class="text-xs text-gray-500">Bayar langsung di gudang Bontang Outdoor.</p>
								</div>
							</label>
							<label data-payment-card="transfer" class="flex cursor-pointer items-start gap-3 rounded-2xl border {{ old('metode_pembayaran', 'cash') === 'transfer' ? 'border-primary-400 bg-primary-50' : 'border-gray-200 bg-white' }} p-4">
								<input type="radio" name="metode_pembayaran" value="transfer" class="mt-1 text-primary-600 focus:ring-primary-500" {{ old('metode_pembayaran', 'cash') === 'transfer' ? 'checked' : '' }}>
								<div>
									<p class="font-semibold text-gray-900">Transfer bank</p>
									<p class="text-xs text-gray-500">Transfer DP/penuh lalu kirim bukti via WhatsApp.</p>
								</div>
							</label>
						</div>
						<div id="cashGuide" class="rounded-xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 {{ old('metode_pembayaran', 'cash') === 'transfer' ? 'hidden' : '' }}">
							<p class="font-semibold">Bayar cash saat serah terima</p>
							<p class="mt-1">{{ $cashNote }}</p>
						</div>
						<div id="transferGuide" class="rounded-xl border border-primary-100 bg-primary-50 px-4 py-3 text-sm text-primary-800 {{ old('metode_pembayaran', 'cash') === 'transfer' ? '' : 'hidden' }}">
							<p class="font-semibold">Data transfer</p>
							<p class="mt-1">{{ $transferConfig['bank'] ?? 'BCA' }} • {{ $transferConfig['account_number'] ?? '1234567890' }} a.n {{ $transferConfig['account_name'] ?? 'Bontang Outdoor' }}</p>
							<p class="text-xs text-primary-700">{{ $transferConfig['instructions'] ?? 'Setelah transfer, kirim bukti pembayaran agar booking segera diproses.' }}</p>
						</div>
					</section>

					<div class="border-t border-gray-200 pt-4">
						<button type="submit" class="w-full rounded-xl bg-primary-600 px-6 py-3 text-white font-semibold shadow-sm hover:bg-primary-700">Kirim booking sekarang</button>
						<p class="mt-2 text-center text-xs text-gray-500">Setelah submit, tim kami cek stok & menghubungi Anda via WhatsApp.</p>
					</div>
				</form>
			</div>
		</div>
	</div>

	<script>
		const alatList = document.getElementById('alat-list');
		const addButton = document.getElementById('add-alat');
		const today = new Date().toISOString().split('T')[0];
		document.querySelector('input[name="tanggal_mulai"]').setAttribute('min', today);
		document.querySelector('input[name="tanggal_selesai"]').setAttribute('min', today);
		document.querySelector('input[name="tanggal_mulai"]').addEventListener('change', function () {
			document.querySelector('input[name="tanggal_selesai"]').setAttribute('min', this.value || today);
		});

		const toggleRemoveButton = () => {
			document.querySelectorAll('.alat-item').forEach((item, index) => {
				item.querySelector('.alat-item-number').textContent = `Barang #${index + 1}`;
				const removeBtn = item.querySelector('.remove-alat');
				removeBtn.classList.toggle('hidden', alatList.children.length === 1);
			});
		};

		const clearItemFields = (item) => {
			item.querySelectorAll('input').forEach((input) => (input.value = ''));
			const select = item.querySelector('select');
			if (select) select.selectedIndex = 0;
		};

		addButton?.addEventListener('click', () => {
			const template = alatList.firstElementChild.cloneNode(true);
			clearItemFields(template);
			alatList.appendChild(template);
			toggleRemoveButton();
		});

		document.addEventListener('click', (event) => {
			if (event.target.closest('.remove-alat')) {
				const item = event.target.closest('.alat-item');
				if (alatList.children.length > 1) {
					item.remove();
					toggleRemoveButton();
				}
			}
		});

		toggleRemoveButton();

		const paymentCards = document.querySelectorAll('[data-payment-card]');
		const paymentInputs = document.querySelectorAll('input[name="metode_pembayaran"]');
		const cashGuide = document.getElementById('cashGuide');
		const transferGuide = document.getElementById('transferGuide');

		const updatePaymentGuide = (method) => {
			if (method === 'transfer') {
				transferGuide?.classList.remove('hidden');
				cashGuide?.classList.add('hidden');
			} else {
				cashGuide?.classList.remove('hidden');
				transferGuide?.classList.add('hidden');
			}

			paymentCards.forEach((card) => {
				const isActive = card.dataset.paymentCard === method;
				card.classList.toggle('border-primary-400', isActive);
				card.classList.toggle('bg-primary-50', isActive);
				card.classList.toggle('border-gray-200', !isActive);
				card.classList.toggle('bg-white', !isActive);
			});
		};

		paymentInputs.forEach((input) => {
			input.addEventListener('change', () => updatePaymentGuide(input.value));
		});

		updatePaymentGuide(document.querySelector('input[name="metode_pembayaran"]:checked')?.value || 'cash');
	</script>
@endsection
