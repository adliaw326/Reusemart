import 'package:flutter/material.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';  // Correct import for version 9.0.0
import 'package:http/http.dart' as http;
import 'dart:convert';  // For JSON parsing
import 'profile_penitip.dart';  // Navigate to login if the user is not logged in
import 'penitip_screen.dart'; // Import PembeliScreen

class DaftarBarangTitipanScreen extends StatefulWidget {
  @override
  _DaftarBarangTitipanScreenState createState() => _DaftarBarangTitipanScreenState();
}

class _DaftarBarangTitipanScreenState extends State<DaftarBarangTitipanScreen> {
  List<Map<String, dynamic>> _penitipan = [];  // To hold the list of penitipan
  bool _isLoading = true;
  bool _hasError = false; // Flag to check if there is an error
  final FlutterSecureStorage _storage = FlutterSecureStorage(); // Correct usage of SecureStorage

  @override
  void initState() {
    super.initState();
    _loadPenitipanHistory();
  }

  // Fetch the penitipan history when the screen is loaded
  _loadPenitipanHistory() async {
    var token = await _storage.read(key: 'token'); // Get the token from SecureStorage
    var userId = await _storage.read(key: 'userId'); // Get the userId from SecureStorage

    if (token != null && userId != null) {
      var url = 'https://reusemartark.my.id/api/transaksi-penitipan/$userId'; // Use userId in the URL

      try {
        var response = await http.get(
          Uri.parse(url), // Updated URL for penitipan history
          headers: {
            'Authorization': 'Bearer $token',
          },
        );

        if (response.statusCode == 200) {
          var penitipanData = json.decode(response.body);
          setState(() {
            _penitipan = List<Map<String, dynamic>>.from(penitipanData);
            _isLoading = false;
            _hasError = false; // Reset error flag if request is successful
          });
        } else {
          setState(() {
            _isLoading = false;
            _hasError = true; // Set error flag if response code is not 200
          });
          print("Failed to load penitipan: ${response.statusCode}");
        }
      } catch (e) {
        setState(() {
          _isLoading = false;
          _hasError = true; // Set error flag on exception
        });
        print("Error fetching penitipan: $e");
      }
    } else {
      setState(() {
        _isLoading = false;
        _hasError = true; // Set error flag if token or userId is null
      });
      print("No token or userId found");
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text("Daftar Barang Titipan"),
      ),
      body: _isLoading
          ? Center(child: CircularProgressIndicator()) // Show loading spinner while fetching data
          : _hasError
              ? Center(child: Text("Gagal memuat penitipan, coba lagi."))
              : _penitipan.isEmpty
                  ? Center(child: Text("Tidak ada penitipan"))
                  : ListView.builder(
                      itemCount: _penitipan.length,
                      itemBuilder: (context, index) {
                        return _buildPenitipanCard(_penitipan[index]);
                      },
                    ),
      bottomNavigationBar: BottomNavigationBar(
        currentIndex: 1, // Set current page index (Home is now highlighted)
        items: [
          BottomNavigationBarItem(
            icon: Icon(Icons.home),
            label: 'Home',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.person),
            label: 'Profile',
          ),
        ],
        onTap: (index) {
          if (index == 1) {
            Navigator.pushReplacement(
              context,
              MaterialPageRoute(builder: (_) => PenitipScreen()),
            );
          } else if (index == 1) {
            Navigator.push(
              context,
              MaterialPageRoute(builder: (_) => ProfilePenitipScreen()),
            );
          }
        },
      ),
    );
  }

  // Helper method to build each penitipan card with relevant details
  Widget _buildPenitipanCard(Map<String, dynamic> penitipan) {
    String statusPenitipan = penitipan['STATUS_PENITIPAN'];

    // Convert Tanggal Expired to DateTime
    DateTime tanggalExpired = DateTime.parse(penitipan['TANGGAL_EXPIRED']);
    DateTime currentDate = DateTime.now();

    // Check if Tanggal Expired is 7 days in the past
    if (currentDate.isAfter(tanggalExpired.add(Duration(days: 7)))) {
      statusPenitipan = "Barang untuk Donasi"; // Update status if expired + 7 days
    }

    return Container(
      padding: EdgeInsets.symmetric(horizontal: 16, vertical: 16),
      margin: EdgeInsets.symmetric(vertical: 12),
      decoration: BoxDecoration(
        color: Colors.white,
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
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          _buildPenitipanDetail("Nama Barang", penitipan['produk']?['NAMA_PRODUK'] ?? 'N/A'), // Nama Barang
          _buildPenitipanDetail("Tanggal Expired", penitipan['TANGGAL_EXPIRED'] ?? 'N/A'), // Tanggal Expired
          _buildPenitipanDetail("Status Penitipan", statusPenitipan ?? 'N/A'), // Status Penitipan (updated)
          SizedBox(height: 10),
          Divider(),
        ],
      ),
    );
  }

  // Helper method to build penitipan details with a custom title and value
  Widget _buildPenitipanDetail(String title, dynamic value) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 4.0),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(
            title,
            style: TextStyle(fontSize: 14, fontWeight: FontWeight.bold),
          ),
          Text(
            value != null ? value.toString() : 'N/A', // Convert value to string if it's not null
            style: TextStyle(fontSize: 14),
          ),
        ],
      ),
    );
  }
}
