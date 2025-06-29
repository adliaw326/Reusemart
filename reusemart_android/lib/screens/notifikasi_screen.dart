import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import '../utils/storage.dart';  // Asumsi ini SecureStorage kamu

class NotifikasiScreen extends StatefulWidget {
  @override
  _NotifikasiScreenState createState() => _NotifikasiScreenState();
}

class _NotifikasiScreenState extends State<NotifikasiScreen> {
  List<Map<String, String>> notifikasiList = [];
  bool isLoading = true;
  String errorMessage = '';

  String? userId;
  String? role;  // misal 'pembeli' atau 'penitip'

  final Color darkNavy = Color(0xFF0b1e33);
  final Color navy = Color(0xFF013c58);
  final Color blue = Color(0xFF00537a);
  final Color orangeBright = Color(0xFFf5a201);
  final Color orangeYellow = Color(0xFFffba42);

  @override
  void initState() {
    super.initState();
    _loadUserDataAndFetchNotif();
  }

  Future<void> _loadUserDataAndFetchNotif() async {
    setState(() {
      isLoading = true;
      errorMessage = '';
    });

    try {
      final storedUserId = await SecureStorage.read('userId');
      final storedRole = await SecureStorage.read('role');

      if (storedUserId == null || storedRole == null) {
        setState(() {
          errorMessage = 'User tidak ditemukan. Silakan login ulang.';
          isLoading = false;
        });
        return;
      }

      setState(() {
        userId = storedUserId;
        role = storedRole;
      });

      await fetchNotifikasi();
    } catch (e) {
      setState(() {
        errorMessage = 'Gagal mengambil data user: $e';
        isLoading = false;
      });
    }
  }

  Future<void> fetchNotifikasi() async {
    final baseUrl = 'http://reusemartark.my.id/api'; // Ganti sesuai backend kamu
    if (userId == null || role == null) return;

    final endpoint = role == 'pembeli'
        ? '/pembeli/notif/$userId'
        : '/penitip/notif/$userId';

    final url = Uri.parse('$baseUrl$endpoint');

    try {
      final response = await http.get(url);

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);

        setState(() {
          notifikasiList = List<Map<String, String>>.from(data.map((item) => {
            'judul': (item['JUDUL'] ?? '').toString(),
            'isi': (item['ISI'] ?? '').toString(),
          }));
          isLoading = false;
        });
      } else {
        setState(() {
          errorMessage = 'Gagal mengambil data: ${response.statusCode}';
          isLoading = false;
        });
      }
    } catch (e) {
      setState(() {
        errorMessage = 'Terjadi kesalahan: $e';
        isLoading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: darkNavy,
      appBar: AppBar(
        backgroundColor: navy,
        foregroundColor: Colors.white,  // <-- ini buat teks & ikon jadi putih
        title: Row(
          children: [
            Text('Notifikasi'),
            SizedBox(width: 8),
            if (!isLoading && notifikasiList.isNotEmpty)
              Container(
                padding: EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                decoration: BoxDecoration(
                  color: orangeBright,
                  borderRadius: BorderRadius.circular(12),
                ),
                child: Text(
                  '${notifikasiList.length}',
                  style: TextStyle(
                    color: darkNavy,
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ),
          ],
        ),
        actions: [
          IconButton(
            icon: Icon(Icons.refresh),
            tooltip: 'Refresh',
            onPressed: _loadUserDataAndFetchNotif,
          ),
        ],
      ),
      body: isLoading
          ? Center(child: CircularProgressIndicator(color: orangeBright))
          : errorMessage.isNotEmpty
              ? Center(
                  child: Text(
                    errorMessage,
                    style: TextStyle(color: orangeBright),
                  ),
                )
              : notifikasiList.isEmpty
                  ? Center(
                      child: Text(
                        'Belum ada notifikasi',
                        style: TextStyle(color: orangeBright),
                      ),
                    )
                  : ListView.builder(
                      itemCount: notifikasiList.length,
                      itemBuilder: (context, index) {
                        final notif = notifikasiList[index];
                        return Card(
                          color: blue,
                          margin: EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                          child: ListTile(
                            leading: Icon(Icons.notifications, color: orangeYellow),
                            title: Text(
                              notif['judul'] ?? '',
                              style: TextStyle(color: orangeYellow, fontWeight: FontWeight.bold),
                            ),
                            subtitle: Text(
                              notif['isi'] ?? '',
                              style: TextStyle(color: orangeBright),
                            ),
                            // onTap dihapus supaya tidak bisa ditekan
                          ),
                        );
                      },
                    ),
    );
  }
}
