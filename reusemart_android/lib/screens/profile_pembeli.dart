import 'package:flutter/material.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';  // Correct import for version 9.0.0
import 'package:http/http.dart' as http;
import 'dart:convert';  // For JSON parsing
import 'login_screen.dart';
import 'tentang_kami.dart'; // Import Tentang Kami screen
import 'pembeli_screen.dart'; // Import PembeliScreen
import 'history_transaksi.dart'; // Import HistoryTransaksiScreen
import 'merch_screen.dart'; // Import screen Tukar Merch

class ProfilePembeliScreen extends StatefulWidget {
  @override
  _ProfilePembeliScreenState createState() => _ProfilePembeliScreenState();
}

class _ProfilePembeliScreenState extends State<ProfilePembeliScreen> {
  Map<String, dynamic>? _profile;
  bool _isLoading = true;
  final FlutterSecureStorage _storage = FlutterSecureStorage(); // Correct usage of SecureStorage

  @override
  void initState() {
    super.initState();
    _loadProfile();
  }

  // Fetch the profile when the screen is loaded
  _loadProfile() async {
    var token = await _storage.read(key: 'token'); // Get the token from SecureStorage

    if (token != null) {
      var response = await http.get(
        Uri.parse('http://10.0.2.2:8000/api/profile/mobile'), // Replace with your actual API URL
        headers: {
          'Authorization': 'Bearer $token',
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
    await _storage.deleteAll();  // Hapus semua data login
    Navigator.of(context).pushReplacement(
      MaterialPageRoute(builder: (_) => LoginScreen()),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
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
                      // Add "Melihat History Transaksi" button
                      Row(
                        children: [
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
                          Expanded(
                            child: ElevatedButton(
                              onPressed: () {
                                Navigator.push(
                                  context,
                                  MaterialPageRoute(builder: (_) => MerchScreen(_profile!['id_pembeli'].toString())),
                                );
                              },
                              child: Text("Tukar Merch", style: TextStyle(fontSize: 16)),
                            ),
                          ),
                        ],
                      ),
                    ],
                  ),
                ),
      bottomNavigationBar: BottomNavigationBar(
        currentIndex: 2, // Set current page index (Profile is now highlighted)
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
          } else if (index == 2) {
            // Stay on the Profile screen, since it's already selected
          }
        },
      ),
    );
  }

  // Helper method to create a rounded field for displaying profile information
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
            offset: Offset(0, 3), // Shadow position
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
