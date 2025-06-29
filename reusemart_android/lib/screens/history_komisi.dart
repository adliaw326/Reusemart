import 'package:flutter/material.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';

import 'profile_hunter.dart';

class HistoryKomisiScreen extends StatefulWidget {
  @override
  _HistoryKomisiScreenState createState() => _HistoryKomisiScreenState();
}

class _HistoryKomisiScreenState extends State<HistoryKomisiScreen> {
  final FlutterSecureStorage _storage = FlutterSecureStorage();
  bool _isLoading = true;
  List<dynamic> _komisiList = [];

  @override
  void initState() {
    super.initState();
    _fetchHistoryKomisi();
  }

  Future<void> _fetchHistoryKomisi() async {
    String? token = await _storage.read(key: 'token');
    if (token == null) {
      setState(() => _isLoading = false);
      return;
    }

    final response = await http.get(
      Uri.parse('http://reusemartark.my.id/api/history-komisi-mobile'),
      headers: {'Authorization': 'Bearer $token'},
    );

    if (response.statusCode == 200) {
      final List<dynamic> data = json.decode(response.body);
      setState(() {
        _komisiList = data;
        _isLoading = false;
      });
    } else {
      print('Failed to load: ${response.statusCode}');
      setState(() => _isLoading = false);
    }
  }

  void _showDetailDialog(dynamic komisiItem) {
    final produk = komisiItem['produk'];
    final kodeProduk = komisiItem['kode_produk'];
    final foto1 = 'http://reusemartark.my.id/foto/foto_produk/${kodeProduk}_1.jpg';
    final foto2 = 'http://reusemartark.my.id/foto/foto_produk/${kodeProduk}_2.jpg';

    showDialog(
      context: context,
      builder: (_) => AlertDialog(
        title: Text('Detail Komisi & Produk'),
        content: SingleChildScrollView(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Row(
                children: [
                  Image.network(foto1, width: 100, height: 100, fit: BoxFit.cover, errorBuilder: (_, __, ___) => Icon(Icons.image_not_supported)),
                  SizedBox(width: 8),
                  Image.network(foto2, width: 100, height: 100, fit: BoxFit.cover, errorBuilder: (_, __, ___) => Icon(Icons.image_not_supported)),
                ],
              ),
              SizedBox(height: 10),
              produk != null
                  ? Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text('Nama Produk: ${produk['nama_produk']}'),
                        Text('Kategori: ${produk['kategori']}'),
                        Text('Harga: Rp ${produk['harga']}'),
                        Text('Berat: ${produk['berat']} Kg'),
                      ],
                    )
                  : Text('Data produk tidak tersedia'),
              SizedBox(height: 10),
              Text('Komisi Hunter: Rp ${komisiItem['komisi_hunter']}'),
            ],
          ),
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: Text('Tutup'),
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('History Komisi Hunter'),
        leading: IconButton(
          icon: Icon(Icons.arrow_back),
          onPressed: () {
            Navigator.pushReplacement(context, MaterialPageRoute(builder: (_) => ProfileHunterScreen()));
          },
        ),
      ),
      body: _isLoading
          ? Center(child: CircularProgressIndicator())
          : _komisiList.isEmpty
              ? Center(child: Text('Belum ada data komisi'))
              : SingleChildScrollView(
                  scrollDirection: Axis.horizontal,
                  child: DataTable(
                    columnSpacing: 20,
                    columns: const [
                      DataColumn(label: Text('ID Komisi')),
                      DataColumn(label: Text('Kode Produk')),
                      DataColumn(label: Text('Komisi Hunter')),
                      DataColumn(label: Text('Detail')),
                    ],
                    rows: _komisiList.map((komisiItem) {
                      return DataRow(cells: [
                        DataCell(Text(komisiItem['id_komisi'].toString())),
                        DataCell(Text(komisiItem['kode_produk'].toString())),
                        DataCell(Text('Rp ${komisiItem['komisi_hunter'].toString()}')),
                        DataCell(
                          ElevatedButton(
                            style: ElevatedButton.styleFrom(
                              backgroundColor: Colors.blue,
                              foregroundColor: Colors.white,
                              minimumSize: Size(80, 40),
                            ),
                            child: Text('Detail'),
                            onPressed: () {
                              _showDetailDialog(komisiItem);
                            },
                          ),
                        ),
                      ]);
                    }).toList(),
                  ),
                ),
    );
  }
}
