import 'package:flutter/material.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import 'package:http/http.dart' as http;
import 'package:reusemart_android/screens/hunter_screen.dart';
import 'dart:convert';

import 'login_screen.dart';
import 'history_komisi.dart';

class ProfileHunterScreen extends StatefulWidget {
  @override
  _ProfileHunterScreenState createState() => _ProfileHunterScreenState();
}

class _ProfileHunterScreenState extends State<ProfileHunterScreen> {
  Map<String, dynamic>? _profile;
  bool _isLoading = true;
  final FlutterSecureStorage _storage = FlutterSecureStorage();
  int _selectedIndex = 1; // Set ke 1 karena halaman ini adalah Profile

  @override
  void initState() {
    super.initState();
    _loadProfile();
  }

  _loadProfile() async {
    var token = await _storage.read(key: 'token');

    if (token != null) {
      var response = await http.get(
        Uri.parse('http://10.0.2.2:8000/api/hunter/profile/mobile'),
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
    await _storage.deleteAll();
    Navigator.of(context).pushReplacement(
      MaterialPageRoute(builder: (_) => LoginScreen()),
    );
  }

  void _onItemTapped(int index) {
    if (index == 0) {
      Navigator.of(context).pushReplacement(
        MaterialPageRoute(builder: (_) => HunterScreen()),
      );
    } else if (index == 1) {
      // Do nothing, stay on ProfileHunterScreen
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text("Profil Hunter"),
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
                      _buildRoundedField("Tanggal Lahir", _profile!['lahir'].toString()),
                      SizedBox(height: 16),
                      _buildRoundedField("Total Komisi", "Rp " + _profile!['total_komisi'].toString()),
                      SizedBox(height: 16),
                      ElevatedButton(
                        onPressed: () {
                          Navigator.push(
                            context,
                            MaterialPageRoute(builder: (_) => HistoryKomisiScreen()),
                          );
                        },
                        child: Text("History Komisi", style: TextStyle(fontSize: 16)),
                      ),
                    ],
                  ),
                ),
      bottomNavigationBar: BottomNavigationBar(
        currentIndex: _selectedIndex,
        items: const [
          BottomNavigationBarItem(
            icon: Icon(Icons.home),
            label: 'Home',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.person),
            label: 'Profile',
          ),
        ],
        onTap: _onItemTapped,
      ),
    );
  }

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
          Flexible(
            child: Text(
              value,
              style: TextStyle(fontSize: 16),
              textAlign: TextAlign.right,
              overflow: TextOverflow.ellipsis,
            ),
          ),
        ],
      ),
    );
  }
}
