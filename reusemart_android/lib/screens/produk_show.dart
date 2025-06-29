import 'package:flutter/material.dart';
import 'dart:convert';
import 'package:http/http.dart' as http;

import 'detail_barang_screen.dart'; // nanti ini untuk detail produk

class ProdukShow extends StatefulWidget {
  final String? idPembeli;

  ProdukShow({this.idPembeli});

  @override
  _ProdukShowState createState() => _ProdukShowState();
}

class _ProdukShowState extends State<ProdukShow> {
  List produkList = [];
  bool loading = true;

  Future<void> fetchProduk() async {
    final url = Uri.parse('http://reusemartark.my.id/api/produk');
    // Sesuaikan url di atas dengan endpoint API backend-mu yang sebenarnya, bisa juga pakai query parameter lainnya

    try {
      final response = await http.get(url);
      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        // Asumsi data produk ada di data['data'] atau sesuai response API-mu
        setState(() {
          produkList = data['data'] ?? [];
          loading = false;
        });
      } else {
        setState(() {
          loading = false;
        });
        throw Exception('Gagal ambil data produk');
      }
    } catch (e) {
      setState(() {
        loading = false;
      });
      print(e);
    }
  }

  @override
  void initState() {
    super.initState();
    fetchProduk();
  }

  @override
  Widget build(BuildContext context) {
    if (loading) {
      return Center(child: CircularProgressIndicator());
    }
    if (produkList.isEmpty) {
      return Center(child: Text('Tidak ada produk'));
    }

    return Padding(
      padding: EdgeInsets.all(8),
      child: GridView.builder(
        itemCount: produkList.length,
        gridDelegate: SliverGridDelegateWithFixedCrossAxisCount(
          crossAxisCount: 2, // 2 produk per baris
          childAspectRatio: 0.7, // sesuaikan proporsi kartu
          crossAxisSpacing: 8,
          mainAxisSpacing: 8,
        ),
        itemBuilder: (context, index) {
          final produk = produkList[index];
          final kodeProduk = produk['KODE_PRODUK'] ?? '';
          final namaProduk = produk['NAMA_PRODUK'] ?? '';
          final harga = produk['HARGA'] ?? '';
          final berat = produk['BERAT'] ?? '';
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

          // Foto utama (1 dari 2 foto)
          final fotoUtamaUrl = 'http://reusemartark.my.id/foto/foto_produk/${kodeProduk}_1.jpg';

          return GestureDetector(
            onTap: () {
              Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (_) => ProdukDetailScreen(produk: produk),
                ),
              );
            },
            child: Card(
              elevation: 3,
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Expanded(
                    flex: 7,
                    child: ClipRRect(
                      borderRadius: BorderRadius.vertical(top: Radius.circular(12)),
                      child: Image.network(
                        fotoUtamaUrl,
                        width: double.infinity,
                        fit: BoxFit.cover,
                        errorBuilder: (context, error, stackTrace) => Center(
                          child: Icon(Icons.broken_image, size: 50, color: Colors.red),
                        ),
                        loadingBuilder: (context, child, progress) {
                          if (progress == null) return child;
                          return Center(child: CircularProgressIndicator());
                        },
                      ),
                    ),
                  ),
                  SizedBox(
                    height: 100, // coba sesuaikan tinggi agar muat, misal 100–120
                    child: Padding(
                      padding: EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(namaProduk, style: TextStyle(fontWeight: FontWeight.bold), maxLines: 1, overflow: TextOverflow.ellipsis),
                          SizedBox(height: 4),
                          Text('Garansi: $garansi', style: TextStyle(fontSize: 12, color: Colors.grey[700])),
                          SizedBox(height: 4),
                          Text('Berat: $berat Kg', style: TextStyle(fontSize: 12, color: Colors.grey[700])),
                          SizedBox(height: 4),
                          Text('Harga: Rp $harga', style: TextStyle(color: Colors.green[700], fontWeight: FontWeight.w600)),
                        ],
                      ),
                    ),
                  ),
                ],
              ),
            ),
          );
        },
      ),
    );
  }
}
