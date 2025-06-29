import 'package:flutter/material.dart';

class ProdukDetailScreen extends StatelessWidget {
  final Map produk;

  ProdukDetailScreen({required this.produk});

  @override
  Widget build(BuildContext context) {
    final kodeProduk = produk['KODE_PRODUK'] ?? '';
    final namaProduk = produk['NAMA_PRODUK'] ?? '';
    final garansiStr = produk['GARANSI'];
      String garansi;

      if (garansiStr != null) {
        final garansiDate = DateTime.tryParse(garansiStr);
        if (garansiDate != null && garansiDate.isAfter(DateTime.now())) {
          garansi = 'Masih Aktif';
        } else {
          garansi = 'Sudah Habis';
        }
      } else {
        garansi = 'Tidak Ada';
      }
    final harga = produk['HARGA'] ?? '';
    final berat = produk['BERAT'] ?? '';

    final foto1 = 'https://reusemartark.my.id/foto/foto_produk/${kodeProduk}_1.jpg';
    final foto2 = 'https://reusemartark.my.id/foto/foto_produk/${kodeProduk}_2.jpg';

    return Scaffold(
      appBar: AppBar(title: Text('Detail Produk')),
      body: SingleChildScrollView(
        child: Column(
          children: [
            SizedBox(height: 8),
            Text(namaProduk, style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold)),
            SizedBox(height: 8),
            Text('Garansi: $garansi', style: TextStyle(fontSize: 16)),
            SizedBox(height: 8),
            Text('Berat: $berat Kg', style: TextStyle(fontSize: 16)),
            SizedBox(height: 8),
            Text('Harga: Rp $harga', style: TextStyle(fontSize: 18, color: Colors.green[700], fontWeight: FontWeight.bold)),
            SizedBox(height: 16),

            Row(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                _buildFoto(foto1),
                SizedBox(width: 16),
                _buildFoto(foto2),
              ],
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildFoto(String url) {
    return ClipRRect(
      borderRadius: BorderRadius.circular(12),
      child: Image.network(
        url,
        width: 150,
        height: 150,
        fit: BoxFit.cover,
        errorBuilder: (context, error, stackTrace) => Icon(Icons.broken_image, size: 80, color: Colors.red),
        loadingBuilder: (context, child, loadingProgress) {
          if (loadingProgress == null) return child;
          return SizedBox(
            width: 150,
            height: 150,
            child: Center(child: CircularProgressIndicator()),
          );
        },
      ),
    );
  }
}
