import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'pembeli_screen.dart'; // Import PembeliScreen
import 'tentang_kami.dart';  // Import Tentang Kami screen
import 'profile_pembeli.dart'; // Import ProfilePembeliScreen

class LeaderboardScreen extends StatefulWidget {
  @override
  _LeaderboardScreenState createState() => _LeaderboardScreenState();
}

class _LeaderboardScreenState extends State<LeaderboardScreen> {
  List<dynamic> leaderboardData = [];
  int currentIndex = 0;  // Set default index for Leaderboard to be highlighted
  String selectedMonth = '1'; // Default month is January
  String selectedYear = '2025'; // Default year

  List<String> months = [
    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
  ];

  List<String> monthsNumeric = [
    '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'
  ];

  List<String> years = [
    '2023', '2024', '2025', '2026'
  ];

  @override
  void initState() {
    super.initState();
    fetchLeaderboardData();  // Fetch data initially without any filter
  }

  // Fetch the leaderboard data from the server
  Future<void> fetchLeaderboardData() async {
    try {
      final response = await http.get(Uri.parse(
          'https://reusemartark.my.id/api/leaderboard-mobile?bulan=$selectedMonth&tahun=$selectedYear'));

      // Check if the widget is still mounted before calling setState()
      if (!mounted) return;

      print('Response status: ${response.statusCode}');
      print('Response body: ${response.body}');

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        if (mounted) {
          setState(() {
            leaderboardData = data;
          });
        }
      } else {
        if (mounted) {
          setState(() {
            leaderboardData = [];
          });
        }
        print('Failed to load leaderboard data');
      }
    } catch (e) {
      print('Error fetching leaderboard data: $e');
      if (mounted) {
        setState(() {
          leaderboardData = [];
        });
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Leaderboard'),
      ),
      body: Column(
        children: [
          Padding(
            padding: const EdgeInsets.all(8.0),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                // Dropdown for month
                DropdownButton<String>(
                  value: months[int.parse(selectedMonth) - 1],
                  onChanged: (String? newMonth) {
                    setState(() {
                      selectedMonth = monthsNumeric[months.indexOf(newMonth!)];
                    });
                    fetchLeaderboardData(); // Fetch data when month changes
                  },
                  items: months.map((String month) {
                    return DropdownMenuItem<String>(
                      value: month,
                      child: Text(month),
                    );
                  }).toList(),
                ),
                SizedBox(width: 20),
                // Dropdown for year
                DropdownButton<String>(
                  value: selectedYear,
                  onChanged: (String? newYear) {
                    setState(() {
                      selectedYear = newYear!;
                    });
                    fetchLeaderboardData(); // Fetch data when year changes
                  },
                  items: years.map((String year) {
                    return DropdownMenuItem<String>(
                      value: year,
                      child: Text(year),
                    );
                  }).toList(),
                ),
              ],
            ),
          ),
          Expanded(
            child: leaderboardData.isEmpty
                ? Center(
                    child: Text(
                      'Tidak ada penitip yang memiliki penjualan.',
                      style: TextStyle(fontSize: 18, color: Colors.grey),
                    ),
                  )
                : Padding(
                    padding: const EdgeInsets.all(8.0),
                    child: ListView.builder(
                      itemCount: leaderboardData.length,
                      itemBuilder: (context, index) {
                        // Determine the rank for images (1st, 2nd, 3rd)
                        String rankImage = '';
                        if (index == 0) {
                          rankImage = 'assets/images/gold.png'; // Gold for 1st
                        } else if (index == 1) {
                          rankImage = 'assets/images/silver.png'; // Silver for 2nd
                        } else if (index == 2) {
                          rankImage = 'assets/images/bronze.png'; // Bronze for 3rd
                        } else {
                          rankImage = ''; // No badge for ranks after 3rd
                        }

                        // Calculate 1% of the total bayar as bonus, only for top 3
                        String bonusText = '';
                        if (index == 0 || index == 1 || index == 2) {
                          double totalBayar = double.parse(leaderboardData[index]['TOTAL_BAYAR'].toString());
                          double bonus = totalBayar * 0.01; // Calculate 1% of total bayar
                          bonusText = 'Bonus Poin: ${bonus.toStringAsFixed(0)}'; // Display the calculated bonus
                        }

                        return Card(
                          elevation: 5.0,
                          margin: EdgeInsets.symmetric(vertical: 10.0),
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(15.0),
                          ),
                          child: ListTile(
                            contentPadding: EdgeInsets.all(15.0),
                            leading: rankImage.isNotEmpty
                                ? Image.asset(
                                    rankImage, // Load the corresponding image for top 3
                                    width: 40.0,
                                    height: 40.0,
                                  )
                                : null, // No badge for non-top 3
                            title: Text(
                              '${leaderboardData[index]['NAMA_PENITIP']}',
                              style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                            ),
                            subtitle: Text(
                              bonusText, // Show bonus text only for top 3
                              style: TextStyle(fontSize: 16, color: Colors.green),
                            ),
                          ),
                        );
                      },
                    ),
                  ),
          ),
        ],
      ),
      bottomNavigationBar: BottomNavigationBar(
        currentIndex: currentIndex,  // Set the current index for highlighting
        selectedItemColor: Colors.deepPurple, // Selected icon color
        unselectedItemColor: Colors.grey, // Unselected icon color
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
          setState(() {
            currentIndex = index; // Update the current index on tap
          });

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
              MaterialPageRoute(builder: (_) => TentangKami()),  // Ensure this is the correct import
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
    );
  }
}
