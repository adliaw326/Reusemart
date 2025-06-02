import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:firebase_messaging/firebase_messaging.dart';
import '../utils/storage.dart';

class AuthService {
  static const String baseUrl = 'http://10.0.2.2:8000/api/login';

  // Fungsi login
  static Future<Map<String, dynamic>> login(String email, String password) async {
    final fcmToken = await FirebaseMessaging.instance.getToken() ?? '';
    print('FCM Token: $fcmToken');
    try {
      final response = await http.post(
        Uri.parse(baseUrl),
        headers: {'Accept': 'application/json'},
        body: {'email': email, 'password': password,  'fcm_token': fcmToken,},
      );

      print('Response status: ${response.statusCode}');
      print('Response body: ${response.body}');

      final data = json.decode(response.body);

      if (response.statusCode == 200) {
        // Simpan token, role, dan userId ke secure storage
        await SecureStorage.saveToken(data['token']);
        await SecureStorage.saveRole(data['role']);
        await SecureStorage.saveUserId(data['userId'].toString());

        return {
          'success': true,
          'role': data['role'],
          'userId': data['userId'],
          'user': data['user'],
        };
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Login gagal',
        };
      }
    } catch (e) {
      print('Error connecting to backend: $e');
      return {
        'success': false,
        'message': 'Tidak bisa konek ke server: $e',
      };
    }
  }

  // Cek apakah token masih ada (user sudah login)
  static Future<bool> isLoggedIn() async {
    String? token = await SecureStorage.getToken();
    return token != null && token.isNotEmpty;
  }

  // Logout dan hapus semua data login
  static Future<void> logout() async {
    await SecureStorage.clear();
  }
}
