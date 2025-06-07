import 'package:flutter/material.dart';
import 'package:firebase_core/firebase_core.dart';
import 'package:reusemart_android/screens/tentang_kami.dart';
import 'screens/login_screen.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  await Firebase.initializeApp();

  // runApp(MaterialApp(
  //   home: LoginScreen(),
  //   debugShowCheckedModeBanner: false,
  // ));

  runApp(MaterialApp(
    home: TentangKami(),
    debugShowCheckedModeBanner: false,
  ));
}
