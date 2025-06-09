import 'package:flutter/material.dart';
import '../utils/storage.dart';  // Import helper SecureStorage
import 'login_screen.dart';
import 'produk_show.dart';

class OrganisasiScreen extends StatelessWidget {
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
        title: Text("Halaman Organisasi"),
        actions: [
          IconButton(
            icon: Icon(Icons.logout),
            onPressed: () => _logout(context),
            tooltip: 'Logout',
          ),
        ],
      ),
      body: ProdukShow(),
    );
  }
}
