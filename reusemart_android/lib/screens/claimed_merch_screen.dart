import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;

class ClaimedMerchScreen extends StatefulWidget {
  final String token;

  const ClaimedMerchScreen({Key? key, required this.token}) : super(key: key);

  @override
  State<ClaimedMerchScreen> createState() => _ClaimedMerchScreenState();
}

class _ClaimedMerchScreenState extends State<ClaimedMerchScreen> {
  bool _isLoading = true;
  List<dynamic> _claimedMerchList = [];
  String? _error;

  @override
  void initState() {
    super.initState();
    _fetchClaimedMerch();
  }

  Future<void> _fetchClaimedMerch() async {
    setState(() {
      _isLoading = true;
      _error = null;
    });

    try {
      final response = await http.get(
        Uri.parse('http://reusemartark.my.id/api/penukaran/pembeli'), // Ganti localhost ke 10.0.2.2 untuk emulator
        headers: {
          'Authorization': 'Bearer ${widget.token}',
          'Accept': 'application/json',
        },
      );

      if (response.statusCode == 200) {
        final List<dynamic> data = json.decode(response.body);
        setState(() {
          _claimedMerchList = data;
          _isLoading = false;
        });
      } else {
        setState(() {
          _error = 'Gagal mengambil data penukaran: ${response.statusCode}';
          _isLoading = false;
        });
      }
    } catch (e) {
      setState(() {
        _error = 'Terjadi kesalahan: $e';
        _isLoading = false;
      });
    }
  }

  void _showDetailDialog(dynamic item) {
    showDialog(
      context: context,
      builder: (_) => AlertDialog(
        title: Text('Detail Merchandise'),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text('Nama: ${item['nama_merchandise']}'),
            Text('Jumlah: ${item['jumlah_penukaran']}'),
            Text('Total Poin: ${item['jumlah_harga_poin']}'),
            Text('Tanggal Klaim: ${item['tanggal_claim_penukaran']}'),
            // Bisa ditambahkan field lain jika ada
          ],
        ),
        actions: [
          TextButton(onPressed: () => Navigator.pop(context), child: Text('Tutup')),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text("Merchandise yang Sudah Diklaim"),
      ),
      body: _isLoading
          ? Center(child: CircularProgressIndicator())
          : _error != null
              ? Center(child: Text(_error!))
              : _claimedMerchList.isEmpty
                  ? Center(child: Text("Belum ada merchandise yang diklaim."))
                  : ListView.builder(
                      padding: EdgeInsets.all(8),
                      itemCount: _claimedMerchList.length,
                      itemBuilder: (context, index) {
                        final item = _claimedMerchList[index];
                        return Card(
                          margin: EdgeInsets.symmetric(vertical: 6, horizontal: 12),
                          child: ListTile(
                            title: Text(item['nama_merchandise']),
                            subtitle: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text("Tanggal Klaim: ${item['tanggal_claim_penukaran']}"),
                              ],
                            ),
                            trailing: IconButton(
                              icon: Icon(Icons.info_outline),
                              onPressed: () => _showDetailDialog(item),
                              tooltip: 'Detail',
                            ),
                          ),
                        );
                      },
                    ),
    );
  }
}
