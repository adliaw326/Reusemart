import 'package:flutter/material.dart';
import '../utils/storage.dart';  // Import helper SecureStorage
import 'login_screen.dart';
import 'pembeli_screen.dart'; // Make sure to import PembeliScreen

class TentangKami extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Tentang Kami'),
      ),
      body: SingleChildScrollView(  // Wrap the body with SingleChildScrollView to make it scrollable
        child: Padding(
          padding: const EdgeInsets.all(16.0),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Menambahkan gambar logoBesar.webp
              Center(
                child: Container(
                  margin: EdgeInsets.only(top: 30.0), // Menambahkan margin untuk gambar agar sedikit turun
                  child: Image.asset(
                    'assets/images/logoBesar.webp',  // Path gambar
                    width: 300,  // Ukuran lebar gambar, sesuaikan dengan kebutuhan
                    height: 350, // Ukuran tinggi gambar, sesuaikan dengan kebutuhan
                    fit: BoxFit.cover,  // Opsi untuk pengaturan tampilan gambar
                  ),
                ),
              ),
              SizedBox(height: 16),
              Text(
                'Kami adalah platform e-commerce yang berfokus pada penjualan barang bekas berkualitas dengan harga terjangkau. Dengan berbagai pilihan produk mulai dari elektronik, pakaian, hingga perabot rumah tangga, kami membantu konsumen mendapatkan barang bekas yang masih layak pakai tanpa mengurangi kualitasnya.',
                style: TextStyle(fontSize: 16),
              ),
              SizedBox(height: 16),
              Text(
                'Komitmen kami tidak hanya untuk memberikan solusi hemat bagi konsumen, tetapi juga untuk menjaga kelestarian lingkungan. Dengan mendukung ekonomi sirkular, kami turut mengurangi limbah dan sampah dengan memperpanjang masa pakai barang. Melalui platform kami, setiap transaksi dilakukan dengan aman dan nyaman, memberikan pengalaman berbelanja yang mudah dan terpercaya.',
                style: TextStyle(fontSize: 16),
              ),
            ],
          ),
        ),
      ),
      bottomNavigationBar: BottomNavigationBar(
        currentIndex: 1,  // Set current page index to "Tentang Kami"
        items: [
          BottomNavigationBarItem(
            icon: Icon(Icons.home),
            label: 'Home',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.info),
            label: 'Tentang Kami',
            backgroundColor: Colors.blue, // Highlight color for current page
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
            // Stay on Tentang Kami page
            Navigator.pushReplacement(
              context,
              MaterialPageRoute(builder: (_) => TentangKami()),
            );
          }
        },
      ),
    );
  }
}
