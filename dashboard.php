<?php
include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style2.css">
    <title>Dashboard</title>
    <style>
        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-top: 20px;
        }

        .card {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s ease, background-color 0.3s ease, box-shadow 0.3s ease;
        }

        .card h3 {
            margin-bottom: 10px;
            font-size: 20px;
        }

        .card:hover {
            background-color: #e0f7fa;
            transform: translateY(-10px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            cursor: pointer;
        }

        .calendar-container {
            display: none;
            grid-column: span 4;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .calendar {
            width: 100%;
            margin-top: 20px;
        }

        .holiday {
            background-color: #ffcccc;
        }

        .calendar-container.active {
            display: block;
        }

        /* Style untuk tanggal saat ini */
        .current-date {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <aside class="left-section">
            <div class="logo">
                <button class="menu-btn" id="menu-close">
                    <i class='bx bx-log-out-circle'></i>
                </button>
                <img src="assets/logo.png">
                <a href="#">Dashboard</a>
            </div>

            <div class="sidebar">
                <div class="item" id="active">
                    <i class='bx bx-home-alt-2'></i>
                    <a href="#">Overview</a>
                </div>
                <div class="item">
                    <i class='bx bx-user'></i>
                    <a href="user.php">Halaman User</a>
                </div>
                <div class="item">
                    <i class='bx bx-plus-circle'></i>
                    <a href="target.php">Tambah Target Baru</a>
                </div>
                <div class="item">
                    <i class='bx bx-bar-chart'></i>
                    <a href="data_nilai.php">Data Nilai Progres</a>
                </div>
                <div class="item">
                    <i class='bx bx-cog'></i>
                    <a href="setting.php">Setting</a>
                </div>
            </div>
        </aside>

        <main id="content">
            <h1>Selamat Datang di Dashboard</h1>
            <p>Pilih menu di sidebar untuk mengakses halaman yang diinginkan.</p>

            <div class="dashboard-cards">
                <div class="card">
                    <h3>Jumlah User</h3>
                    <p>150</p>
                </div>
                <div class="card">
                    <h3>Jumlah Target</h3>
                    <p>200</p>
                </div>
                <div class="card">
                    <h3>Data Nilai Progres</h3>
                    <p>85%</p>
                </div>
                <div class="card" id="show-calendar">
                    <h3>Kalender</h3>
                    <p>Klik untuk melihat kalender lengkap</p>
                </div>
            </div>

            <div class="calendar-container" id="calendar-container">
                <h3>Kalender Tahun 2025</h3>
                <!-- Tambahkan elemen untuk tanggal saat ini -->
                <div id="current-date" class="current-date"></div>
                <div id="full-calendar" class="calendar"></div>
            </div>
        </main>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" />

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarContainer = document.getElementById('calendar-container');
            var calendarEl = document.getElementById('full-calendar');
            var calendarInitialized = false;

            // Menambahkan event listener untuk card Kalender
            document.getElementById('show-calendar').addEventListener('click', function () {
                // Tampilkan elemen kalender-container saat card di-klik
                if (calendarContainer.style.display === 'none' || calendarContainer.style.display === '') {
                    calendarContainer.style.display = 'block';
                    
                    // Hanya inisialisasi FullCalendar jika belum diinisialisasi sebelumnya
                    if (!calendarInitialized) {
                        var calendar = new FullCalendar.Calendar(calendarEl, {
                            initialView: 'dayGridMonth',
                            headerToolbar: {
                                left: 'prev,next today',
                                center: 'title',
                                right: 'dayGridMonth,timeGridWeek,timeGridDay'
                            },
                            events: [
                                {
                                    title: 'Tahun Baru',
                                    start: '2025-01-01',
                                    className: 'holiday'
                                },
                                {
                                    title: 'Hari Raya Nyepi',
                                    start: '2025-03-29',
                                    className: 'holiday'
                                },
                                {
                                    title: 'Hari Buruh Internasional',
                                    start: '2025-05-01',
                                    className: 'holiday'
                                },
                                {
                                    title: 'Hari Kemerdekaan',
                                    start: '2025-08-17',
                                    className: 'holiday'
                                },
                                {
                                    title: 'Maulid Nabi Muhammad',
                                    start: '2025-12-01',
                                    className: 'holiday'
                                },
                                {
                                    title: 'Hari Raya Natal',
                                    start: '2025-12-25',
                                    className: 'holiday'
                                }
                            ]
                        });
                        calendar.render();
                        calendarInitialized = true;
                    }
                } else {
                    calendarContainer.style.display = 'none';
                }
            });

            // Tampilkan tanggal saat ini
            function showCurrentDate() {
                var currentDateEl = document.getElementById('current-date');
                var now = moment(); // Dapatkan tanggal sekarang menggunakan moment.js
                var formattedDate = now.format('dddd, D MMMM YYYY'); // Format: Hari, Tanggal Bulan Tahun
                currentDateEl.textContent = formattedDate;
            }

            // Panggil fungsi untuk menampilkan tanggal saat ini
            showCurrentDate();
        });
    </script>
</body>

</html>
