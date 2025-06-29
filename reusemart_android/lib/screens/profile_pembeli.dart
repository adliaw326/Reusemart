import 'package:flutter/material.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';

import 'login_screen.dart';
import 'tentang_kami.dart';
import 'pembeli_screen.dart';
import 'history_transaksi.dart';
import 'merch_screen.dart';
import 'claimed_merch_screen.dart';

class ProfilePembeliScreen extends StatefulWidget {
  @override
  _ProfilePembeliScreenState createState() => _ProfilePembeliScreenState();
}

class _ProfilePembeliScreenState extends State<ProfilePembeliScreen> {
  Map<String, dynamic>? _profile;
  bool _isLoading = true;
  final FlutterSecureStorage _storage = FlutterSecureStorage();
  String? _token; // ✅ Tambahkan variabel token

  @override
  void initState() {
    super.initState();
    _loadProfile();
  }

  // ✅ Ambil profil & simpan token
  _loadProfile() async {
    _token = await _storage.read(key: 'token'); // Simpan token

    if (_token != null) {
      var response = await http.get(
        Uri.parse('https://reusemartark.my.id/api/profile/mobile'),
        headers: {
          'Authorization': 'Bearer $_token',
        },
      );

      if (response.statusCode == 200) {
        var profileData = json.decode(response.body);
        setState(() {
          _profile = profileData;
          _isLoading = false;
        });
      } else {
        setState(() {
          _isLoading = false;
        });
        print("Failed to load profile: ${response.statusCode}");
      }
    } else {
      setState(() {
        _isLoading = false;
      });
      print("No token found");
    }
  }

  void _logout(BuildContext context) async {
    await _storage.deleteAll();
    Navigator.of(context).pushReplacement(
      MaterialPageRoute(builder: (_) => LoginScreen()),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text("Profile Pembeli"),
        actions: [
          IconButton(
            icon: Icon(Icons.logout),
            onPressed: () => _logout(context),
            tooltip: 'Logout',
          ),
        ],
      ),
      body: _isLoading
          ? Center(child: CircularProgressIndicator())
          : _profile == null
              ? Center(child: Text("Gagal memuat profil"))
              : Padding(
                  padding: EdgeInsets.all(16.0),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      _buildRoundedField("Nama", _profile!['name']),
                      SizedBox(height: 16),
                      _buildRoundedField("Email", _profile!['email']),
                      SizedBox(height: 16),
                      _buildRoundedField("Poin Reward", _profile!['points'].toString()),
                      SizedBox(height: 16),
                      Row(
                        children: [
                          // Tombol History Transaksi
                          Expanded(
                            child: ElevatedButton(
                              onPressed: () {
                                Navigator.push(
                                  context,
                                  MaterialPageRoute(builder: (_) => HistoryTransaksiScreen()),
                                );
                              },
                              child: Text("History Transaksi", style: TextStyle(fontSize: 16)),
                            ),
                          ),
                          SizedBox(width: 16),
                          // Tombol Tukar Merch
                          Expanded(
                            child: ElevatedButton(
                              onPressed: () {
                                Navigator.push(
                                  context,
                                  MaterialPageRoute(
                                    builder: (_) => MerchScreen(_profile!['id_pembeli'].toString()),
                                  ),
                                );
                              },
                              child: Text("Tukar Merch", style: TextStyle(fontSize: 16)),
                            ),
                          ),
                          SizedBox(width: 16),
                          // ✅ Tombol Merch Diklaim (pakai token)
                        ],
                      ),
                      Row(
                        children: [
                          Expanded(
                            child: ElevatedButton(
                              onPressed: _token == null
                                  ? null
                                  : () {
                                      Navigator.push(
                                        context,
                                        MaterialPageRoute(
                                          builder: (_) => ClaimedMerchScreen(token: _token!),
                                        ),
                                      );
                                    },
                              child: Text("Merch Diklaim", style: TextStyle(fontSize: 16)),
                            ),
                          ),
                          SizedBox(width: 16),
                        ],
                      ),
                    ],
                  ),
                ),
      bottomNavigationBar: BottomNavigationBar(
        currentIndex: 2,
        items: [
          BottomNavigationBarItem(
            icon: Icon(Icons.home),
            label: 'Home',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.info),
            label: 'Tentang Kami',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.person),
            label: 'Profile',
          ),
        ],
        onTap: (index) {
          if (index == 0) {
            Navigator.pushReplacement(
              context,
              MaterialPageRoute(builder: (_) => PembeliScreen()),
            );
          } else if (index == 1) {
            Navigator.push(
              context,
              MaterialPageRoute(builder: (_) => TentangKami()),
            );
          }
        },
      ),
    );
  }

  // Komponen untuk menampilkan data profil
  Widget _buildRoundedField(String label, String value) {
    return Container(
      padding: EdgeInsets.symmetric(horizontal: 16, vertical: 12),
      decoration: BoxDecoration(
        color: Colors.grey[200],
        borderRadius: BorderRadius.circular(12),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.1),
            spreadRadius: 1,
            blurRadius: 6,
            offset: Offset(0, 3),
          ),
        ],
      ),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(
            label,
            style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
          ),
          Text(
            value,
            style: TextStyle(fontSize: 16),
          ),
        ],
      ),
    );
  }
}
