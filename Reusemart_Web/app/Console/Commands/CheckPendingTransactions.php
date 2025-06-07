<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TransaksiPembelian;
use App\Models\Pembeli;
use App\Models\Produk;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Scheduling\Schedule;

class CheckPendingTransactions extends Command
{
    protected $signature = 'transactions:check-pending';
    protected $description = 'Check pending transactions older than 1 minute and cancel them';

    public function handle()
    {
        // Ambil transaksi yang menunggu bayar > 1 menit
        $this->info('Mengecek transaksi pending...'); // << tampil di CMD
        $pendingTransactions = TransaksiPembelian::where('STATUS_TRANSAKSI', 'BELUM DIBAYAR')
            ->where('TANGGAL_PESAN', '<', now()->subMinute())
            ->get();

        foreach ($pendingTransactions as $transaksi) {
            DB::transaction(function () use ($transaksi) {
                // Update status transaksi jadi batal
                $transaksi->STATUS_TRANSAKSI = 'BATAL KARENA LAMA';
                $transaksi->save();

                // Kembalikan poin ke pembeli
                // $pembeli = Pembeli::find($transaksi->ID_PEMBELI);
                // if ($pembeli && $transaksi->POIN_DISKON > 0) {
                //     $pembeli->POIN_PEMBELI += $transaksi->POIN_DISKON;
                //     $pembeli->save();
                // }

                // Update stok produk agar tersedia kembali
                $produkList = Produk::where('ID_PEMBELIAN', $transaksi->ID_PEMBELIAN)->get();
                foreach ($produkList as $produk) {
                    $produk->ID_PEMBELIAN = null; // asumsi JUMLAH adalah qty produk yg dipesan
                    $produk->save();
                }
            });

            $this->info("Transaksi {$transaksi->ID_PEMBELIAN} dibatalkan karena timeout.");
        }
        $this->info('Pengecekan transaksi pending selesai.');
    }
}
