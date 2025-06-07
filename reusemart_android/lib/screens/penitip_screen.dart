import 'package:flutter/material.dart';
import '../utils/storage.dart';  // Import helper SecureStorage
import 'login_screen.dart';
import 'profile_penitip.dart'; // Import Profile Penitip screen

class PenitipScreen extends StatefulWidget {
  @override
  _PenitipScreenState createState() => _PenitipScreenState();
}

class _PenitipScreenState extends State<PenitipScreen> {
  int _currentIndex = 0; // Track the current tab index

  // Function to log out the user
  void _logout(BuildContext context) async {
    await SecureStorage.clear();  // Hapus semua data login
    Navigator.of(context).pushReplacement(
      MaterialPageRoute(builder: (_) => LoginScreen()), // Navigate to login screen
    );
  }

  // Function to switch pages based on the bottom navigation index
  void _onTap(int index) {
    setState(() {
      _currentIndex = index;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(_currentIndex == 0 ? "Halaman Penitip" : "Profile Penitip"), // Dynamic title
        actions: [
          IconButton(
            icon: Icon(Icons.logout),
            onPressed: () => _logout(context),
            tooltip: 'Logout',
          ),
        ],
      ),
      body: _currentIndex == 0
          ? Center(child: Text("Selamat datang, Penitip!")) // Home page (Penitip Dashboard)
          : ProfilePenitipScreen(), // Navigate to Profile Penitip Screen
      bottomNavigationBar: BottomNavigationBar(
        currentIndex: _currentIndex,  // Set current page index
        onTap: _onTap,  // Handle the tab change
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
      ),
    );
  }
}
