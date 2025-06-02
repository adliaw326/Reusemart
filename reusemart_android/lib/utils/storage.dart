import 'package:flutter_secure_storage/flutter_secure_storage.dart';

class SecureStorage {
  static const _storage = FlutterSecureStorage();

  static Future<void> saveToken(String token) async {
    await _storage.write(key: 'token', value: token);
  }

  static Future<String?> getToken() async {
    return await _storage.read(key: 'token');
  }

  static Future<void> saveRole(String role) async {
    await _storage.write(key: 'role', value: role);
  }

  static Future<String?> getRole() async {
    return await _storage.read(key: 'role');
  }

  static Future<void> saveUserId(String userId) async {
    await _storage.write(key: 'userId', value: userId);
  }

  static Future<String?> getUserId() async {
    return await _storage.read(key: 'userId');
  }

  static Future<void> clear() async {
    await _storage.deleteAll();
  }
}
