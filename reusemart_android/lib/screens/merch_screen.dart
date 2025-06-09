import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'profile_pembeli.dart';

class MerchScreen extends StatefulWidget {
  final String idPembeli; // Pastikan id pembeli di-passing dari login

  MerchScreen(this.idPembeli);

  @override
  _MerchScreenState createState() => _MerchScreenState();
}

class _MerchScreenState extends State<MerchScreen> {
  List merchList = [];
  bool loading = true;

  @override
  void initState() {
    super.initState();
    fetchMerch();
  }

  Future<void> fetchMerch() async {
    final url = Uri.parse('http://10.0.2.2:8000/api/merch');
    try {
      final response = await http.get(url);
      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        setState(() {
          merchList = data['data'] ?? [];
          loading = false;
        });
      } else {
        throw Exception('Gagal memuat merch');
      }
    } catch (e) {
      print(e);
      setState(() {
        loading = false;
      });
    }
  }

  Future<bool> postTukarMerch(String idPembeli, String idMerch, int jumlah, int totalPoin) async {
    print("Mengirim idPembeli: $idPembeli"); // Tambahkan ini
    final url = Uri.parse('http://10.0.2.2:8000/api/penukaran');
    try {
      final response = await http.post(url,
          headers: {"Content-Type": "application/json"},
          body: jsonEncode({
            "id_pembeli": idPembeli,
            "id_merch": idMerch,
            "jumlah_penukaran": jumlah,
            "jumlah_harga_poin": totalPoin,
          }));
      print('Response status: ${response.statusCode}');
      print('Response body: ${response.body}');

      if (response.statusCode == 200) {
        final res = json.decode(response.body);
        return res['success'] ?? false;
      } else {
        return false;
      }
    } catch (e) {
      print("Error post penukaran: $e");
      return false;
    }
  }

  Future<void> _showTukarDialog(BuildContext context, String idMerch, String namaMerch, int poin, int stok) async {
    final TextEditingController _controller = TextEditingController();

    // Show dialog input jumlah tukar
    final jumlahTukar = await showDialog<int>(
      context: context,
      barrierDismissible: false,
      builder: (context) {
        return AlertDialog(
          title: Text('Tukar $namaMerch'),
          content: TextField(
            controller: _controller,
            keyboardType: TextInputType.number,
            decoration: InputDecoration(
              labelText: 'Masukkan jumlah yang ingin ditukar',
              hintText: 'Misal: 2',
            ),
          ),
          actions: [
            TextButton(
              onPressed: () => Navigator.of(context).pop(null), // batal
              child: Text('Batal'),
            ),
            ElevatedButton(
              onPressed: () {
                final input = _controller.text;
                final jumlah = int.tryParse(input);

                if (jumlah == null || jumlah <= 0) {
                  ScaffoldMessenger.of(context).showSnackBar(
                    SnackBar(content: Text('Masukkan jumlah valid!')),
                  );
                  return;
                }
                if (jumlah > stok) {
                  ScaffoldMessenger.of(context).showSnackBar(
                    SnackBar(content: Text('Jumlah melebihi stok tersedia!')),
                  );
                  return;
                }
                Navigator.of(context).pop(jumlah);
              },
              child: Text('Lanjutkan'),
            ),
          ],
        );
      },
    );

    if (jumlahTukar == null) return; // batal dialog input

    final totalPoin = poin * jumlahTukar;

    // Konfirmasi penukaran
    final konfirmasi = await showDialog<bool>(
      context: context,
      barrierDismissible: false,
      builder: (context) {
        return AlertDialog(
          title: Text('Konfirmasi Penukaran'),
          content: Text('Tukar $jumlahTukar x $namaMerch dengan total $totalPoin poin?'),
          actions: [
            TextButton(
              onPressed: () => Navigator.of(context).pop(false),
              child: Text('Batal'),
            ),
            ElevatedButton(
              onPressed: () => Navigator.of(context).pop(true),
              child: Text('Ya'),
            ),
          ],
        );
      },
    );

    if (konfirmasi != true) return; // batal konfirmasi

    // Proses tukar merch
    final sukses = await postTukarMerch(widget.idPembeli, idMerch, jumlahTukar, totalPoin);

    // Setelah proses selesai, tampilkan snackbar di context utama (bukan dialog)
    if (!mounted) return;

    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(
          sukses
              ? 'Berhasil menukar $jumlahTukar $namaMerch'
              : 'Poin tidak mencukupi atau terjadi kesalahan',
        ),
      ),
    );

    if (sukses) {
      fetchMerch(); // reload data kalau perlu
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text("Tukar Merch"),
        leading: IconButton(
          icon: Icon(Icons.arrow_back),
          onPressed: () {
            Navigator.pushReplacement(
              context,
              MaterialPageRoute(builder: (_) => ProfilePembeliScreen()),
            );
          },
        ),
      ),
      body: loading
          ? Center(child: CircularProgressIndicator())
          : merchList.isEmpty
              ? Center(child: Text("Tidak ada merch tersedia."))
              : Padding(
                  padding: const EdgeInsets.all(8.0),
                  child: GridView.builder(
                    itemCount: merchList.length,
                    gridDelegate: SliverGridDelegateWithFixedCrossAxisCount(
                      crossAxisCount: 3,
                      crossAxisSpacing: 10,
                      mainAxisSpacing: 10,
                      childAspectRatio: 0.6,
                    ),
                    itemBuilder: (context, index) {
                      final merch = merchList[index];
                      final idMerch = merch['ID_MERCHANDISE'] ?? '';
                      final nama = merch['NAMA_MERCHANDISE'] ?? '';
                      final poin = merch['HARGA_POIN'] ?? 0;
                      final stok = merch['JUMLAH_MERCH'] ?? 0;
                      final fotoUrl = 'http://10.0.2.2:8000/foto/merch/${idMerch}.jpg';

                      return Card(
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                        elevation: 4,
                        clipBehavior: Clip.hardEdge,
                        child: Column(
                          children: [
                            Expanded(
                              flex: 6,
                              child: ClipRRect(
                                borderRadius: BorderRadius.vertical(top: Radius.circular(12)),
                                child: Image.network(
                                  fotoUrl,
                                  fit: BoxFit.cover,
                                  width: double.infinity,
                                  loadingBuilder: (context, child, loadingProgress) {
                                    if (loadingProgress == null) return child;
                                    return Center(child: CircularProgressIndicator());
                                  },
                                  errorBuilder: (_, __, ___) => Icon(Icons.broken_image, size: 48),
                                ),
                              ),
                            ),
                            SizedBox(
                              height: 120,
                              child: Padding(
                                padding: const EdgeInsets.all(6.0),
                                child: Column(
                                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                  children: [
                                    Text(
                                      nama,
                                      maxLines: 1,
                                      overflow: TextOverflow.ellipsis,
                                      style: TextStyle(fontWeight: FontWeight.bold),
                                    ),
                                    Text(
                                      "$poin poin",
                                      style: TextStyle(color: Colors.green),
                                    ),
                                    ElevatedButton(
                                      onPressed: () {
                                        _showTukarDialog(context, idMerch, nama, poin, stok);
                                      },
                                      child: Text("Tukar", style: TextStyle(fontSize: 12)),
                                      style: ElevatedButton.styleFrom(
                                        minimumSize: Size(double.infinity, 30),
                                        padding: EdgeInsets.symmetric(horizontal: 4),
                                        tapTargetSize: MaterialTapTargetSize.shrinkWrap,
                                      ),
                                    ),
                                  ],
                                ),
                              ),
                            ),
                          ],
                        ),
                      );
                    },
                  ),
                ),
    );
  }
}
