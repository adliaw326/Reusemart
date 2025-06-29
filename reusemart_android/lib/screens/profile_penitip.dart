import 'package:flutter/material.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';  // Correct import for version 9.0.0
import 'package:http/http.dart' as http;
import 'dart:convert';  // For JSON parsing
import 'login_screen.dart';  // Navigate to login if the user is not logged in
import 'daftar_barang_titipan.dart'; // Import DaftarBarangTitipanScreen
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
        Uri.parse('http://reusemartark.my.id/api/penitip/profile/mobile'), // Replace with your actual API URL
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

  // Navigate to DaftarBarangTitipanScreen when clicked
  void _navigateToDaftarBarangTitipan() {
    Navigator.push(
      context,
      MaterialPageRoute(builder: (_) => DaftarBarangTitipanScreen()),
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
                          _buildProfileField("Rating Rata-Rata", _profile!['RATING_RATA_RATA_P']),
                          _buildProfileField("Saldo", "Rp. ${_formatCurrency(_profile!['SALDO_PENITIP'])}"),
                          _buildProfileField("Poin Penitip", _profile!['POIN_PENITIP'].toString()),
                          _buildProfileField("Total Barang Terjual", _profile!['TOTAL_BARANG_TERJUAL'].toString()),
                          SizedBox(height: 20), // Add some spacing between fields and button
                          ElevatedButton(
                            onPressed: _navigateToHistoryPenitipan, // Navigate to HistoryPenitipanScreen
                            child: Text("Melihat History Penitipan", style: TextStyle(fontSize: 16)),
                          ),
                          SizedBox(height: 10), // Spacing between buttons
                          ElevatedButton(
                            onPressed: _navigateToDaftarBarangTitipan, // Navigate to Daftar Barang Titipan Screen
                            child: Text("Daftar Barang Titipan", style: TextStyle(fontSize: 16)),
                          ),
                        ],
                      ),
                    ),
    );
  }

  // Helper method to format currency for Saldo
  String _formatCurrency(int value) {
    String formattedValue = value.toString();
    int length = formattedValue.length;
    if (length > 3) {
      int commaPosition = length % 3;
      if (commaPosition == 0) commaPosition = 3;
      formattedValue = formattedValue.substring(0, commaPosition) + ',' + formattedValue.substring(commaPosition);
      while (formattedValue.length > 4) {
        commaPosition = formattedValue.length - 3;
        formattedValue = formattedValue.substring(0, commaPosition) + ',' + formattedValue.substring(commaPosition);
      }
    }
    return formattedValue;
  }

  // Helper method to format float values (like rating)
  String _formatFloat(double value) {
    return value.toStringAsFixed(2);  // Format to 2 decimal places
  }

  // Helper method to build a rounded field for displaying profile information
  Widget _buildProfileField(String label, dynamic value) {
    String displayValue;

    // Handle different types of values
    if (value == null) {
      displayValue = "Tidak tersedia";
    } else if (value is double) {
      displayValue = _formatFloat(value);
    } else if (value is int) {
      displayValue = _formatCurrency(value);
    } else {
      displayValue = value.toString();
    }

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
