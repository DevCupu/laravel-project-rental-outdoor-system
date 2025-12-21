<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Bukti Booking {{ $booking->booking_code }}</title>
    <style>
        body {
            font-family: "Helvetica", "Arial", sans-serif;
            margin: 0;
            padding: 32px;
            color: #0f172a;
            font-size: 12px;
        }

        .header,
        .footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header {
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid #cbd5f5;
        }

        .brand {
            font-weight: 700;
            font-size: 18px;
            letter-spacing: .05em;
            text-transform: uppercase;
        }

        .badge {
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 11px;
            background-color: #ede9fe;
            color: #5b21b6;
            text-transform: uppercase;
            font-weight: 600;
        }

        h2 {
            font-size: 14px;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: .2em;
            color: #475569;
        }

        .section {
            margin-bottom: 18px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        th {
            text-align: left;
            font-size: 11px;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: #475569;
            padding-bottom: 6px;
        }

        td {
            padding: 10px 0;
            border-top: 1px solid #e2e8f0;
            font-size: 12px;
        }

        .total-row td {
            font-weight: 600;
            font-size: 13px;
            border-top-width: 2px;
        }

        .summary {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }

        .card {
            flex: 1 1 45%;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 14px;
            background: #f8fafc;
        }

        .muted {
            color: #475569;
        }

        .note {
            margin-top: 12px;
            padding: 12px;
            background: #ecfdf5;
            border: 1px solid #86efac;
            border-radius: 12px;
            font-size: 11px;
        }

        .note.transfer {
            background: #eef2ff;
            border-color: #c7d2fe;
        }

        .footer {
            border-top: 1px solid #cbd5f5;
            padding-top: 16px;
            margin-top: 24px;
            font-size: 11px;
            color: #475569;
        }
    </style>
</head>

<body>
    @php
        $totalDays = max(1, (int) ($booking->total_hari ?? 1));
    @endphp

    <div class="header">
        <div>
            <div class="brand">Bontang Outdoor</div>
            <div class="muted">Bukti Booking Penyewaan Alat Camping</div>
        </div>
        <div class="badge">{{ $booking->booking_code }}</div>
    </div>

    <div class="section summary">
        <div class="card">
            <h2>Penyewa</h2>
            <p><strong>{{ $booking->nama_penyewa }}</strong></p>
            <p class="muted">WhatsApp: {{ $booking->no_hp }}</p>
            <p class="muted">Email: {{ $booking->email ?? '-' }}</p>
        </div>
        <div class="card">
            <h2>Periode</h2>
            <p><strong>{{ optional($booking->tanggal_mulai)->format('d M Y') }} →
                    {{ optional($booking->tanggal_selesai)->format('d M Y') }}</strong></p>
            <p class="muted">Durasi {{ $totalDays }} hari</p>
            <p class="muted">Status: {{ strtoupper($booking->status_booking ?? 'PENDING') }}</p>
        </div>
        <div class="card">
            <h2>Ringkasan Biaya</h2>
            <p class="muted">{{ $booking->bookingDetails->count() }} jenis alat • {{ $totalDays }} hari</p>
            <p><strong>Total: Rp {{ number_format($booking->total_harga ?? 0, 0, ',', '.') }}</strong></p>
            <p class="muted">Termasuk semua subtotal alat dan durasi sewa.</p>
        </div>
    </div>

    <div class="section">
        <h2>Detail Alat</h2>
        <table>
            <thead>
                <tr>
                    <th>Alat</th>
                    <th style="text-align:center;">Jumlah</th>
                    <th style="text-align:right;">Harga / Hari</th>
                    <th style="text-align:center;">Durasi (hari)</th>
                    <th style="text-align:right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($booking->bookingDetails as $detail)
                    <tr>
                        <td>{{ $detail->alat->nama_alat ?? 'Peralatan' }}</td>
                        <td style="text-align:center;">{{ $detail->jumlah }}</td>
                        <td style="text-align:right;">Rp {{ number_format($detail->alat->harga_sewa_per_hari ?? 0, 0, ',', '.') }}</td>
                        <td style="text-align:center;">{{ $totalDays }}</td>
                        <td style="text-align:right;">Rp {{ number_format($detail->subtotal ?? 0, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="4">Total</td>
                    <td style="text-align:right;">Rp {{ number_format($booking->total_harga ?? 0, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>Perhitungan Singkat</h2>
        <p class="muted">Subtotal dihitung dari (Harga sewa per hari x Jumlah alat x Durasi sewa). Total adalah penjumlahan seluruh subtotal.</p>
    </div>

    <div class="section">
        <h2>Pembayaran</h2>
        <p class="muted">Metode: {{ $paymentMethod === 'transfer' ? 'Transfer bank' : 'Cash saat pickup' }}</p>
        @if ($paymentMethod === 'transfer')
            <div class="note transfer">
                <p><strong>{{ $transferConfig['bank'] ?? 'BCA' }}</strong> • {{ $transferConfig['account_number'] ?? '1234567890' }}</p>
                <p>a.n {{ $transferConfig['account_name'] ?? 'Bontang Outdoor' }}</p>
                <p>{{ $transferConfig['instructions'] ?? 'Setelah transfer, kirim bukti pembayaran melalui WhatsApp.' }}</p>
            </div>
        @else
            <div class="note">
                <p>{{ $cashNote }}</p>
            </div>
        @endif
    </div>

    <div class="footer">
        <p>Dokumen ini digenerate otomatis oleh sistem Bontang Outdoor.</p>
        <p>Harap tunjukkan bukti ini saat pengambilan alat.</p>
    </div>
</body>

</html>
