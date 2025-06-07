import 'package:flutter/material.dart';
import '../utils/storage.dart';  // Import helper SecureStorage
import 'login_screen.dart';
import 'tentang_kami.dart'; // Import Tentang Kami screen
import 'profile_pembeli.dart'; // Import ProfilePembeliScreen

class PembeliScreen extends StatelessWidget {
  void _logout(BuildContext context) async {
    await SecureStorage.clear();  // Hapus semua data login
    Navigator.of(context).pushReplacement(
      MaterialPageRoute(builder: (_) => LoginScreen()),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text("Halaman Pembeli"),
        actions: [
          IconButton(
            icon: Icon(Icons.logout),
            onPressed: () => _logout(context),
            tooltip: 'Logout',
          ),
        ],
      ),
      body: Center(child: Text("Selamat datang, Pembeli!")),
      bottomNavigationBar: BottomNavigationBar(
        currentIndex: 0, // Set current page index (Home is the first page here)
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
            // Navigate to Home (PembeliScreen)
            Navigator.pushReplacement(
              context,
              MaterialPageRoute(builder: (_) => PembeliScreen()),
            );
          } else if (index == 1) {
            // Navigate to Tentang Kami
            Navigator.push(
              context,
              MaterialPageRoute(builder: (_) => TentangKami()),  // Ensure this is the correct import
            );
          } else if (index == 2) {
            // Navigate to Profile (ProfilePembeliScreen)
            Navigator.push(
              context,
              MaterialPageRoute(builder: (_) => ProfilePembeliScreen()),
            );
          }
        },
      ),
    );
  }
}
