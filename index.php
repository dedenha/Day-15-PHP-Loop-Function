<?php
// Fungsi untuk menghitung rata-rata nilai dari sebuah array nilai
function hitungRataRata($nilai) {
    $total = 0;
    $jumlahMataPelajaran = count($nilai); // Menghitung jumlah mata pelajaran
    foreach ($nilai as $n) { // Menggunakan loop foreach untuk menjumlahkan nilai
        $total += $n;
    }
    return $total / $jumlahMataPelajaran; // Mengembalikan rata-rata
}

// Fungsi untuk menentukan predikat berdasarkan rata-rata
function tentukanPredikat($rataRata) {
    if ($rataRata >= 90) {
        return "A (Sangat Baik)";
    } elseif ($rataRata >= 80) {
        return "B (Baik)";
    } elseif ($rataRata >= 70) {
        return "C (Cukup)";
    } elseif ($rataRata >= 60) {
        return "D (Kurang)";
    } else {
        return "E (Sangat Kurang)";
    }
}

// Pengecekan apakah form telah disubmit dengan metode POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['jumlah_siswa'])) {
    $jumlahSiswa = $_POST['jumlah_siswa']; // Mendapatkan jumlah siswa dari form
    $hasil = []; // Array untuk menyimpan hasil penilaian masing-masing siswa

    // Loop untuk memproses setiap siswa berdasarkan jumlah siswa yang diinput
    for ($i = 0; $i < $jumlahSiswa; $i++) {
        // Mengambil nilai dari input form untuk setiap siswa
        $nilai = [
            $_POST['matematika'][$i],
            $_POST['bahasa_indonesia'][$i],
            $_POST['ipa'][$i],
            $_POST['ips'][$i],
            $_POST['bahasa_inggris'][$i]
        ];

        // Menghitung rata-rata dan menentukan predikat untuk setiap siswa
        $rataRata = hitungRataRata($nilai);
        $predikat = tentukanPredikat($rataRata);

        // Menyimpan hasil penilaian (nama, rata-rata, predikat) dalam array hasil
        $hasil[] = [
            'nama' => $_POST['nama_siswa'][$i],
            'rataRata' => $rataRata,
            'predikat' => $predikat
        ];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Penilaian Siswa</title>
</head>
<body>
    <h2>Form Penilaian Siswa</h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        Jumlah Siswa: <input type="number" name="jumlah_siswa" required><br><br>
        
        <!-- Div untuk menambahkan input dinamis untuk setiap siswa -->
        <div id="siswa-form"></div>
        
        <input type="submit" value="Submit">
    </form>

    <script>
        // JavaScript untuk membuat form dinamis berdasarkan jumlah siswa yang diinput
        document.querySelector('input[name="jumlah_siswa"]').addEventListener('input', function() {
            var jumlah = this.value; // Mendapatkan jumlah siswa
            var formDiv = document.getElementById('siswa-form'); // Div tempat form siswa ditampilkan
            formDiv.innerHTML = '';  // Mengosongkan isi div form setiap kali ada perubahan

            // Loop untuk menambahkan input form sebanyak jumlah siswa yang diinput
            for (var i = 0; i < jumlah; i++) {
                formDiv.innerHTML += `
                    <h4>Siswa ${i + 1}</h4>
                    Nama Siswa: <input type="text" name="nama_siswa[]" required><br>
                    Matematika: <input type="number" name="matematika[]" required><br>
                    Bahasa Indonesia: <input type="number" name="bahasa_indonesia[]" required><br>
                    IPA: <input type="number" name="ipa[]" required><br>
                    IPS: <input type="number" name="ips[]" required><br>
                    Bahasa Inggris: <input type="number" name="bahasa_inggris[]" required><br><br>
                `;
            }
        });
    </script>

    <!-- Menampilkan hasil penilaian jika form telah disubmit -->
    <?php if (isset($hasil)): ?>
        <h3>Hasil Penilaian Siswa</h3>
        <table border="1" cellpadding="10">
            <tr>
                <th>Nama Siswa</th>
                <th>Rata-rata</th>
                <th>Predikat</th>
            </tr>
            <!-- Looping untuk menampilkan hasil penilaian setiap siswa -->
            <?php foreach ($hasil as $h): ?>
                <tr>
                    <td><?php echo $h['nama']; ?></td>
                    <td><?php echo number_format($h['rataRata'], 2); ?></td>
                    <td><?php echo $h['predikat']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>
