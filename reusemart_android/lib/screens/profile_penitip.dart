import 'package:flutter/material.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';  // Correct import for version 9.0.0
import 'package:http/http.dart' as http;
import 'dart:convert';  // For JSON parsing
import 'login_screen.dart';  // Navigate to login if the user is not logged in
import 'history_penitipan.dart'; // Import HistoryPenitipanScreen

class ProfilePenitipScreen extends StatefulWidget {
  @override
  _ProfilePenitipScreenState createState() => _ProfilePenitipScreenState();
}

class _ProfilePenitipScreenState extends State<ProfilePenitipScreen> {
  Map<String, dynamic>? _profile;
  bool _isLoading = true;
  bool _hasError = false;
  final FlutterSecureStorage _storage = FlutterSecureStorage(); // Correct usage of SecureStorage

  @override
  void initState() {
    super.initState();
    _loadProfile();
  }

  // Fetch the profile directly when the screen is loaded
  _loadProfile() async {
    var token = await _storage.read(key: 'token'); // Get the token from SecureStorage

    if (token != null) {
      var response = await http.get(
        Uri.parse('http://10.0.2.2:8000/api/penitip/profile/mobile'), // Replace with your actual API URL
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

  // Navigate to HistoryPenitipanScreen when clicked
  void _navigateToHistoryPenitipan() {
    Navigator.push(
      context,
      MaterialPageRoute(builder: (_) => HistoryPenitipanScreen()),
    );
  }

  // Logout method
  void _logout(BuildContext context) async {
    await _storage.deleteAll();  // Hapus semua data login
    Navigator.of(context).pushReplacement(
      MaterialPageRoute(builder: (_) => LoginScreen()), // Navigate to the login screen after logout
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: _isLoading
          ? Center(child: CircularProgressIndicator()) // Show loading spinner while fetching data
          : _hasError
              ? Center(child: Text('Gagal memuat profil, coba lagi.'))
              : _profile == null
                  ? Center(child: Text('Tidak ada data profil'))
                  : Padding(
                      padding: const EdgeInsets.all(16.0),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          _buildProfileField("Nama Penitip", _profile!['NAMA_PENITIP']),
                          _buildProfileField("Email", _profile!['EMAIL_PENITIP']),
                          _buildProfileField("NIK", _profile!['NIK']),
                          _buildProfileField("No Telp", _profile!['NO_TELP_PENITIP']),
                          _buildProfileField("Rating Rata-Rata", _profile!['RATING_RATA_RATA_P']),
                          _buildProfileField("Saldo", "Rp. ${_profile!['SALDO_PENITIP']}"),
                          _buildProfileField("Poin Penitip", _profile!['POIN_PENITIP'].toString()),
                          _buildProfileField("Total Barang Terjual", _profile!['TOTAL_BARANG_TERJUAL'].toString()),
                          SizedBox(height: 20), // Add some spacing between fields and button
                          ElevatedButton(
                            onPressed: _navigateToHistoryPenitipan, // Navigate to HistoryPenitipanScreen
                            child: Text("Melihat History Penitipan", style: TextStyle(fontSize: 16)),
                          ),
                        ],
                      ),
                    ),
    );
  }

  // Helper method to build a rounded field for displaying profile information
  Widget _buildProfileField(String label, String? value) {
    // Handle null values
    String displayValue = value ?? "Tidak tersedia";  // Default text for null values

    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8.0),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(label, style: TextStyle(fontWeight: FontWeight.bold)),
          Text(displayValue, style: TextStyle(fontSize: 16)),
        ],
      ),
    );
  }
}
