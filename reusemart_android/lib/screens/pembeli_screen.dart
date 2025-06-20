import 'package:flutter/material.dart';
import '../utils/storage.dart';  // Import helper SecureStorage
import 'login_screen.dart';
import 'tentang_kami.dart'; // Import Tentang Kami screen
import 'profile_pembeli.dart'; // Import ProfilePembeliScreen
import 'produk_show.dart';  // pastikan path sesuai struktur folder kamu
import 'notifikasi_screen.dart';
import 'leaderboard_screen.dart'; // Import LeaderboardScreen

class PembeliScreen extends StatelessWidget {

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text("Halaman Pembeli"),
        actions: [
          IconButton(
            icon: Icon(Icons.notifications),  // Icon lonceng notifikasi
            onPressed: () => _openNotifications(context),
            tooltip: 'Notifikasi',
          ),
          IconButton(
            icon: Icon(Icons.logout),
            onPressed: () => _logout(context),
            tooltip: 'Logout',
          ),
        ],
      ),
      body: ProdukShow(),
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
              MaterialPageRoute(builder: (_) => TentangKami()),
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
      floatingActionButton: FloatingActionButton(
        onPressed: () {
          // Navigate to LeaderboardScreen when clicked
          Navigator.push(
            context,
            MaterialPageRoute(builder: (_) => LeaderboardScreen()), // Navigate to LeaderboardScreen
          );
        },
        child: Icon(Icons.leaderboard),
        tooltip: 'Leaderboard',
      ),
    );
  }

  void _openNotifications(BuildContext context) {
    Navigator.push(
      context,
      MaterialPageRoute(builder: (_) => NotifikasiScreen()),
    );
  }
}
