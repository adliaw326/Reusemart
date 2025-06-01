import 'package:flutter/material.dart';
import '../services/auth_service.dart';
import 'pegawai_screen.dart';
import 'penitip_screen.dart';
import 'organisasi_screen.dart';
import 'pembeli_screen.dart';

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
        case 'owner':
        case 'hunter':
        case 'kurir':
        case 'admin':
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
      appBar: AppBar(title: Text("Login")),
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
