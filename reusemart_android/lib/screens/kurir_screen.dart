import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import '../utils/storage.dart';  // pastikan ini sesuai dengan lokasi file SecureStorage kamu
import 'login_screen.dart';

class KurirScreen extends StatefulWidget {
  @override
  _KurirScreenState createState() => _KurirScreenState();
}

class _KurirScreenState extends State<KurirScreen> {
  int _selectedIndex = 0;

  String nama = "";
  String email = "";
  String tanggalLahir = "";
  List<Map<String, dynamic>> _dataKurir = [];
  List<Map<String, dynamic>> _dataHistory = [];

  @override
  void initState() {
    super.initState();

    // Panggil data setelah widget build selesai agar async storage sudah siap
    WidgetsBinding.instance.addPostFrameCallback((_) {
      _loadKurirDataFromBackend();
      _loadDataPesanan();
      _loadDataHistory();
    });
  }

  Future<void> _loadDataPesanan() async {
    
    final userId = await SecureStorage.read('userId');
    final token = await SecureStorage.read('token');

    print('userId: $userId');
    print('token: $token');

    final url = Uri.parse("http://reusemartark.my.id/api/kurir/findKurir/$userId");

    try {
      final response = await http.get(
        url,
        headers: {
          'Authorization': 'Bearer $token',
          'Accept': 'application/json',
        },
      );

      if (response.statusCode == 200) {
        final List<dynamic> jsonList = jsonDecode(response.body);
        print("$jsonList");
        setState(() {
          _dataKurir = jsonList.cast<Map<String, dynamic>>();
        });
      }
    } catch (e) {
      print("Error fetching data: $e");
    }
  }
  Future<void> _loadDataHistory() async {
    
    final userId = await SecureStorage.read('userId');
    final token = await SecureStorage.read('token');

    print('userId: $userId');
    print('token: $token');

    final url = Uri.parse("http://reusemartark.my.id/api/kurir/findKurirHistory/$userId");

    try {
      final response = await http.get(
        url,
        headers: {
          'Authorization': 'Bearer $token',
          'Accept': 'application/json',
        },
      );

      if (response.statusCode == 200) {
        final List<dynamic> jsonList = jsonDecode(response.body);
        print("$jsonList");
        setState(() {
          _dataHistory = jsonList.cast<Map<String, dynamic>>();
        });
      }
    } catch (e) {
      print("Error fetching data: $e");
    }
  }

  Future<void> _loadKurirDataFromBackend() async {
    print("Mulai load data kurir");
    final userId = await SecureStorage.read('userId');
    final token = await SecureStorage.read('token');

    print('userId: $userId');
    print('token: $token');

    if (userId == null || token == null) {
      setState(() {
        nama = "Gagal mengambil ID/token";
      });
      return;
    }

    final url = Uri.parse("http://reusemartark.my.id/api/pegawai/showKurir/$userId");

    try {
      final response = await http.get(
        url,
        headers: {
          'Authorization': 'Bearer $token',
          'Accept': 'application/json',
        },
      );

      print("Response status: ${response.statusCode}");

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        print("Data diterima: $data");

        setState(() {
          nama = data['NAMA_PEGAWAI'] ?? 'Tidak diketahui';
          email = data['EMAIL_PEGAWAI'] ?? 'Tidak diketahui';
          tanggalLahir = data['TANGGAL_LAHIR_PEGAWAI'] ?? 'Tidak diketahui';
        });
      } else {
        setState(() {
          nama = "Kurir tidak ditemukan";
          email = "";
          tanggalLahir = "";
        });
      }
    } catch (e) {
      print("Error saat fetch data: $e");
      setState(() {
        nama = "Gagal terhubung ke server";
        email = "";
        tanggalLahir = "";
      });
    }
  }

  void _logout(BuildContext context) async {
    await SecureStorage.clear();
    Navigator.of(context).pushReplacement(
      MaterialPageRoute(builder: (_) => LoginScreen()),
    );
  }

  Widget _buildPage(int index) {
    switch (index) {
      case 0:
        return Container(
          color: Color(0xFF0b1e33),
          padding: const EdgeInsets.all(16.0),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                "Data Pesanan Harus Diantarkan",
                style: TextStyle(
                  fontSize: 20,
                  fontWeight: FontWeight.bold,
                  color: Color(0xFFF5A201),
                ),
              ),
              SizedBox(width: 16,),
              ElevatedButton(
                  onPressed: () => _loadDataPesanan(),
                  style: ElevatedButton.styleFrom(
                    backgroundColor: Color.fromARGB(255, 0, 102, 255),
                    padding: EdgeInsets.all(12), // padding agar tombol tidak terlalu kecil
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(8),
                    ),
                  ),
                  child: Icon(
                    Icons.refresh,
                    color: Colors.white,
                    size: 24,
                  ),
                ),
              SizedBox(height: 16),
              Table(
                border: TableBorder.all(color: Colors.white),
                columnWidths: const {
                  0: FlexColumnWidth(2),
                  1: FlexColumnWidth(3),
                  2: FlexColumnWidth(3),
                  3: FlexColumnWidth(2),
                },
                children: [
                  TableRow(
                    decoration: BoxDecoration(color: Color(0xFF013c58)),
                    children: [
                      _buildTableHeader("ID"),
                      _buildTableHeader("Lokasi"),
                      _buildTableHeader("Nama Pembeli"),
                      _buildTableHeader("Aksi"),
                    ],
                  ),
                  for (var item in _dataKurir)
                    TableRow(
                      decoration: BoxDecoration(color: Color(0xFF014a6a)),
                      children: [
                        _buildTableCell("TPD${item['ID_PEMBELIAN']}"),
                        _buildTableCell(item['alamat']?['LOKASI'] ?? "-"),
                        _buildTableCell(item['pembeli']?['NAMA_PEMBELI'] ?? "-"),
                        Padding(
                          padding: const EdgeInsets.all(16.0),
                          child: ElevatedButton(
                            onPressed: () => showTransaksiDialog(item),
                            style: ElevatedButton.styleFrom(
                              backgroundColor: Color(0xFFF5A201),
                              padding: EdgeInsets.all(12), // padding agar tombol tidak terlalu kecil
                              shape: RoundedRectangleBorder(
                                borderRadius: BorderRadius.circular(8),
                              ),
                            ),
                            child: Icon(
                              Icons.done,
                              color: Colors.white,
                              size: 24,
                            ),
                          ),
                        ),
                      ],
                    ),
                ],
              ),
            ],
          ),
        );
      case 1:
        return Container(
          width: double.infinity,
          color: Color(0xFF0b1e33),
          padding: const EdgeInsets.all(16.0),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.center,
            children: [
              // Background berbeda untuk tulisan Profil Kurir
              Container(
                width: double.infinity,
                padding: EdgeInsets.symmetric(vertical: 12),
                decoration: BoxDecoration(
                  color: Color(0xFFF5A201),
                  borderRadius: BorderRadius.circular(16), // sudut tidak tajam
                ),
                child: Text(
                  "Profil Kurir",
                  textAlign: TextAlign.center,
                  style: TextStyle(
                    fontSize: 24,
                    fontWeight: FontWeight.bold,
                    color: Color(0xFF0b1e33),
                  ),
                ),
              ),
              SizedBox(height: 20),
              // Card yang memenuhi lebar layar dengan warna berbeda
              SizedBox(
                width: double.infinity,
                child: Card(
                  color: Color(0xFF013c58),
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(16),
                  ),
                  elevation: 4,
                  child: Padding(
                    padding: const EdgeInsets.all(20.0),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        _buildProfileItem("Nama", nama),
                        SizedBox(height: 12),
                        _buildProfileItem("Email", email),
                        SizedBox(height: 12),
                        _buildProfileItem("Tanggal Lahir", tanggalLahir),
                      ],
                    ),
                  ),
                ),
              ),
            ],
          ),
        );
      case 2:
        return Container(
          color: Color(0xFF0b1e33),
          padding: const EdgeInsets.all(16.0),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                "History Pengantaran",
                style: TextStyle(
                  fontSize: 20,
                  fontWeight: FontWeight.bold,
                  color: Color(0xFFF5A201),
                ),
              ),
              SizedBox(width: 16,),
              ElevatedButton(
                  onPressed: () => _loadDataHistory(),
                  style: ElevatedButton.styleFrom(
                    backgroundColor: Color.fromARGB(255, 0, 102, 255),
                    padding: EdgeInsets.all(12), // padding agar tombol tidak terlalu kecil
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(8),
                    ),
                  ),
                  child: Icon(
                    Icons.refresh,
                    color: Colors.white,
                    size: 24,
                  ),
                ),
              SizedBox(height: 16),
              Table(
                border: TableBorder.all(color: Colors.white),
                columnWidths: const {
                  0: FlexColumnWidth(2),
                  1: FlexColumnWidth(3),
                  2: FlexColumnWidth(3),
                  3: FlexColumnWidth(2),
                },
                children: [
                  TableRow(
                    decoration: BoxDecoration(color: Color(0xFF013c58)),
                    children: [
                      _buildTableHeader("ID"),
                      _buildTableHeader("Lokasi"),
                      _buildTableHeader("Nama Pembeli"),
                      _buildTableHeader("Status"),
                    ],
                  ),
                  for (var item in _dataHistory)
                    TableRow(
                      decoration: BoxDecoration(color: Color(0xFF014a6a)),
                      children: [
                        _buildTableCell("TPD${item['ID_PEMBELIAN']}"),
                        _buildTableCell(item['alamat']?['LOKASI'] ?? "-"),
                        _buildTableCell(item['pembeli']?['NAMA_PEMBELI'] ?? "-"),
                        _buildTableCell(item['STATUS_TRANSAKSI']?? "-"),                        
                      ],
                    ),
                ],
              ),
            ],
          ),
        );
      default:
        return Center(child: Text("Halaman tidak ditemukan."));
    }
  }

  @override
  Widget build(BuildContext context) {
    print('Build dipanggil, selectedIndex=$_selectedIndex');
    return Scaffold(
      appBar: AppBar(
        backgroundColor: Color(0xFF013c58), // Warna latar AppBar (biru tua)
        title: Text(
          "Selamat Datang Kurir $nama!",
          style: TextStyle(color: Color(0xFFF5A201)), // Warna teks oranye
        ),
        iconTheme: IconThemeData(color: Color(0xFFF5A201)), // Warna icon di AppBar
        actionsIconTheme: IconThemeData(color: Color(0xFFF5A201)), // Warna icon action
        actions: [
          IconButton(
            icon: Icon(Icons.logout),
            onPressed: () => _logout(context),
            tooltip: 'Logout',
          ),
        ],
      ),
      body: _buildPage(_selectedIndex),
      bottomNavigationBar: BottomNavigationBar(
        backgroundColor: Color(0xFF013c58), // Warna latar navbar (biru tua)
        selectedItemColor: Color(0xFFF5A201), // Warna item terpilih (oranye)
        unselectedItemColor: Colors.white70, // Warna item tidak terpilih (abu terang)
        currentIndex: _selectedIndex,
        onTap: (index) => setState(() => _selectedIndex = index),
        items: const [
          BottomNavigationBarItem(
            icon: Icon(Icons.home),
            label: "Beranda",
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.person),
            label: "Profil",
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.history),
            label: "History",
          ),
        ],
      ),
    );
  }

  Widget _buildProfileItem(String label, String value) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          label,
          style: TextStyle(
            fontSize: 20,
            fontWeight: FontWeight.w600,
            color: Color(0xFFF5A201),
          ),
        ),
        SizedBox(height: 1),
        Text(
          value,
          style: TextStyle(
            fontSize: 16,
            fontWeight: FontWeight.normal,
            color: Color(0xFFFFBA42),
          ),
        ),
      ],
    );
  }

  Widget _buildTableCell(String value) {
    return Padding(
      padding: const EdgeInsets.all(8.0),
      child: Text(
        value,
        style: TextStyle(color: Colors.white),
      ),
    );
  }

  Widget _buildTableHeader(String label) {
    return Padding(
      padding: const EdgeInsets.all(8.0),
      child: Text(
        label,
        style: TextStyle(
          fontWeight: FontWeight.bold,
          color: Color(0xFFF5A201),
        ),
      ),
    );
  }

  void showTransaksiDialog(Map<String, dynamic> item) {
    showDialog(
      context: context,
      builder: (BuildContext dialogContext) {
        return Builder(
          builder: (BuildContext innerContext) {
            return AlertDialog(
              title: Text("Konfirmasi Pengantaran"),
              content: Column(
                mainAxisSize: MainAxisSize.min,
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text("ID Pembelian: TPD${item['ID_PEMBELIAN']}"),
                  Text("Lokasi: ${item['alamat']?['LOKASI'] ?? '-'}"),
                  Text("Nama Pembeli: ${item['pembeli']?['NAMA_PEMBELI'] ?? '-'}"),
                ],
              ),
              actions: [
                TextButton(
                  child: Text("Batal"),
                  onPressed: () {
                    Navigator.of(innerContext).pop();
                  },
                ),
                ElevatedButton(
                  style: ElevatedButton.styleFrom(
                    backgroundColor: Color(0xFFF5A201),
                  ),
                  child: Text("Konfirmasi"),
                  onPressed: () async {
                    Navigator.of(innerContext).pop(); // tutup dialog
                    final response = await http.post(
                      Uri.parse('http://reusemartark.my.id/api/kurir/selesaiKurir/${item['ID_PEMBELIAN']}'),
                      headers: {'Accept': 'application/json'},
                    );

                    if (response.statusCode == 200) {
                      final result = json.decode(response.body);
                      if (result['success'] == true) {
                        ScaffoldMessenger.of(context).showSnackBar(
                          SnackBar(content: Text("Pengantaran Berhasil")),
                        );
                        await _loadDataPesanan();
                      } else {
                        ScaffoldMessenger.of(context).  showSnackBar(
                          SnackBar(content: Text("Gagal: ${result['message']}")),
                        );
                      }
                      _loadDataPesanan();
                      _loadDataHistory();
                    } else {
                      ScaffoldMessenger.of(context).showSnackBar(
                        SnackBar(content: Text("Terjadi kesalahan saat mengirim data.")),
                      );
                    }
                  },
                ),
              ],
            );
          },
        );
      },
    );
  }

}
