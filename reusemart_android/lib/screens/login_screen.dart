import 'package:flutter/material.dart';
import '../services/auth_service.dart';
import 'pegawai_screen.dart';
import 'penitip_screen.dart';
import 'organisasi_screen.dart';
import 'pembeli_screen.dart';
import 'admin_screen.dart';
import 'kurir_screen.dart';
import 'hunter_screen.dart';
import 'owner_screen.dart';
import 'cs_screen.dart';

class LoginScreen extends StatefulWidget {
  @override
  State<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final emailController = TextEditingController();
  final passwordController = TextEditingController();
  bool loading = false;
  String? error;

  void login() async {
    setState(() {
      loading = true;
      error = null;
    });

    final result = await AuthService.login(
      emailController.text.trim(),
      passwordController.text.trim(),
    );

    setState(() {
      loading = false;
    });

    if (result['success']) {
      switch (result['role']) {
        case 'cs':
        Navigator.pushReplacement(context, MaterialPageRoute(builder: (_) => CSScreen()));
          break;
        case 'owner':
        Navigator.pushReplacement(context, MaterialPageRoute(builder: (_) => OwnerScreen()));
          break;
        case 'hunter':
        Navigator.pushReplacement(context, MaterialPageRoute(builder: (_) => HunterScreen()));
          break;
        case 'kurir':
        Navigator.pushReplacement(context, MaterialPageRoute(builder: (_) => KurirScreen()));
          break;
        case 'admin':
          Navigator.pushReplacement(context, MaterialPageRoute(builder: (_) => AdminScreen()));
          break;
        case 'pegawai_gudang':
          Navigator.pushReplacement(context, MaterialPageRoute(builder: (_) => PegawaiScreen()));
          break;
        case 'penitip':
          Navigator.pushReplacement(context, MaterialPageRoute(builder: (_) => PenitipScreen()));
          break;
        case 'organisasi':
          Navigator.pushReplacement(context, MaterialPageRoute(builder: (_) => OrganisasiScreen()));
          break;
        case 'pembeli':
          Navigator.pushReplacement(context, MaterialPageRoute(builder: (_) => PembeliScreen()));
          break;
        default:
          setState(() {
            error = "Peran tidak dikenal.";
          });
      }
    } else {
      setState(() {
        error = result['message'];
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text("Login REUSEMART")),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            if (error != null) Text(error!, style: TextStyle(color: Colors.red)),
            TextField(controller: emailController, decoration: InputDecoration(labelText: "Email")),
            TextField(controller: passwordController, decoration: InputDecoration(labelText: "Password"), obscureText: true),
            SizedBox(height: 16),
            loading
                ? CircularProgressIndicator()
                : ElevatedButton(onPressed: login, child: Text("Login")),
          ],
        ),
      ),
    );
  }
}
