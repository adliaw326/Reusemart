import 'package:flutter/material.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';  // Correct import for version 9.0.0
import 'package:http/http.dart' as http;
import 'dart:convert';  // For JSON parsing
import 'login_screen.dart';
import 'tentang_kami.dart'; // Import Tentang Kami screen
import 'pembeli_screen.dart'; // Import PembeliScreen
import 'profile_pembeli.dart'; // Import ProfilePembeliScreen

class HistoryTransaksiScreen extends StatefulWidget {
  @override
  _HistoryTransaksiScreenState createState() => _HistoryTransaksiScreenState();
}

class _HistoryTransaksiScreenState extends State<HistoryTransaksiScreen> {
  List<Map<String, dynamic>> _transactions = [];  // To hold the list of transactions
  bool _isLoading = true;
  bool _hasError = false; // Flag to check if there is an error
  final FlutterSecureStorage _storage = FlutterSecureStorage(); // Correct usage of SecureStorage

  @override
  void initState() {
    super.initState();
    _loadTransactionHistory();
  }

  // Fetch the transaction history when the screen is loaded
  _loadTransactionHistory() async {
    var token = await _storage.read(key: 'token'); // Get the token from SecureStorage
    var userId = await _storage.read(key: 'userId'); // Get the userId from SecureStorage

    if (token != null && userId != null) {
      var url = 'https://reusemartark.my.id/api/transaksi-pembelian/mobile?ID_PEMBELI=$userId'; // Use userId in the URL

      try {
        var response = await http.get(
          Uri.parse(url), // Updated URL for transaction history
          headers: {
            'Authorization': 'Bearer $token',
          },
        );

        if (response.statusCode == 200) {
          var transactionsData = json.decode(response.body);
          setState(() {
            _transactions = List<Map<String, dynamic>>.from(transactionsData);
            _isLoading = false;
            _hasError = false; // Reset error flag if request is successful
          });
        } else {
          setState(() {
            _isLoading = false;
            _hasError = true; // Set error flag if response code is not 200
          });
          print("Failed to load transactions: ${response.statusCode}");
        }
      } catch (e) {
        setState(() {
          _isLoading = false;
          _hasError = true; // Set error flag on exception
        });
        print("Error fetching transactions: $e");
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
        title: Text("History Transaksi Pembelian"),
      ),
      body: _isLoading
          ? Center(child: CircularProgressIndicator()) // Show loading spinner while fetching data
          : _hasError
              ? Center(child: Text("Gagal memuat transaksi, coba lagi."))
              : _transactions.isEmpty
                  ? Center(child: Text("Tidak ada transaksi"))
                  : ListView.builder(
                      itemCount: _transactions.length,
                      itemBuilder: (context, index) {
                        return _buildTransactionCard(_transactions[index]);
                      },
                    ),
      bottomNavigationBar: BottomNavigationBar(
        currentIndex: 2, // Set current page index (Home is now highlighted)
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
            Navigator.push(
              context,
              MaterialPageRoute(builder: (_) => ProfilePembeliScreen()),
            );
          }
        },
      ),
    );
  }

  // Helper method to build each transaction card with enhanced styling
  Widget _buildTransactionCard(Map<String, dynamic> transaction) {
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
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text("Transaksi ID", style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
              Text("${transaction['ID_PEMBELIAN']}", style: TextStyle(fontSize: 16)),
            ],
          ),
          Divider(),
          SizedBox(height: 8),
          _buildTransactionDetail("Tanggal Pesan", transaction['TANGGAL_PESAN']),
          _buildTransactionDetail("Tanggal Ambil", transaction['TANGGAL_AMBIL']),
          _buildTransactionDetail("Status Rating", transaction['STATUS_RATING']),
          _buildTransactionDetail("Total Bayar", "Rp. ${transaction['TOTAL_BAYAR']}"),
          SizedBox(height: 10),

        ],
      ),
    );
  }

  // Helper method to build transaction details with a custom title and value
  Widget _buildTransactionDetail(String title, String value) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 4.0),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(title, style: TextStyle(fontSize: 14, fontWeight: FontWeight.bold)),
          Text(value, style: TextStyle(fontSize: 14)),
        ],
      ),
    );
  }
}
