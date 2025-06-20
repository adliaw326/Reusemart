import 'package:flutter/material.dart';
import '../utils/storage.dart'; // Import helper SecureStorage
import 'login_screen.dart';
import 'profile_hunter.dart';

class HunterScreen extends StatefulWidget {
  @override
  _HunterScreenState createState() => _HunterScreenState();
}

class _HunterScreenState extends State<HunterScreen> {
  int _selectedIndex = 0;

  void _logout(BuildContext context) async {
    await SecureStorage.clear();
    Navigator.of(context).pushReplacement(
      MaterialPageRoute(builder: (_) => LoginScreen()),
    );
  }

  static List<Widget> _pages = <Widget>[
    Center(child: Text("Selamat datang, Hunter!")), // Hanya halaman Home
  ];

  void _onItemTapped(int index) {
    if (index == 0) {
      setState(() {
        _selectedIndex = 0;
      });
    } else if (index == 1) {
      // Navigasi penuh ke ProfileHunterScreen
      Navigator.push(
        context,
        MaterialPageRoute(builder: (_) => ProfileHunterScreen()),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text("Halaman Hunter"),
        actions: [
          IconButton(
            icon: Icon(Icons.logout),
            onPressed: () => _logout(context),
            tooltip: 'Logout',
          ),
        ],
      ),
      body: _pages[_selectedIndex],
      bottomNavigationBar: BottomNavigationBar(
        currentIndex: _selectedIndex,
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
        onTap: _onItemTapped,
      ),
    );
  }
}
