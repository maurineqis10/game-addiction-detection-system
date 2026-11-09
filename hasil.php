<?php
require_once 'app/SkriningController.php';
$controller = new SkriningController();

$kode = $_GET['kode'] ?? '';
$data = $controller->getHasil($kode);

if (!$data) {
    die("Hasil skrining tidak ditemukan!");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Diagnosa - GameGuard System</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary: #6366f1;
            --bg-body: #0f172a;
            --card-bg: #1e293b;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --border: #334155;
            --accent: #38bdf8;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background-color: var(--bg-body); color: var(--text-main); min-height: 100vh; padding-bottom: 50px; }
        
        .navbar { background: rgba(15, 23, 42, 0.9); border-bottom: 1px solid var(--border); padding: 1.2rem 2.5rem; display: flex; justify-content: space-between; align-items: center; }
        .logo { font-weight: 800; font-size: 1.25rem; color: var(--accent); text-decoration: none; }
        
        .main-wrapper { max-width: 780px; margin: 35px auto; padding: 0 20px; }
        .card { background: var(--card-bg); border: 1px solid var(--border); border-radius: 24px; padding: 36px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.4); }
        
        .page-header { text-align: center; margin-bottom: 20px; }
        .page-header h1 { font-size: 1.8rem; font-weight: 800; color: #fff; margin-bottom: 6px; }
        .page-header p { color: var(--text-muted); font-size: 0.9rem; }
        
        .status-badge { text-align: center; font-size: 1.5rem; font-weight: 800; padding: 20px; border-radius: 16px; margin: 20px 0; letter-spacing: -0.01em; }
        .status-rendah { background: rgba(34, 197, 94, 0.15); color: #4ade80; border: 1.5px solid #22c55e; }
        .status-sedang { background: rgba(245, 158, 11, 0.15); color: #fbbf24; border: 1.5px solid #f59e0b; }
        .status-tinggi { background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1.5px solid #ef4444; }

        .info-box { background: #0f172a; border: 1px solid var(--border); border-radius: 14px; padding: 20px; margin-bottom: 20px; }
        
        .chart-box { background: #0f172a; border: 1px solid var(--border); border-radius: 14px; padding: 20px; margin: 20px 0; text-align: center; }
        
        .saran-card { background: #0f172a; border-left: 4px solid var(--primary); border-top: 1px solid var(--border); border-right: 1px solid var(--border); border-bottom: 1px solid var(--border); padding: 20px; border-radius: 14px; margin-top: 20px; }

        .action-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 25px; }
        .btn-action { display: block; text-align: center; padding: 14px; border-radius: 12px; font-weight: 700; text-decoration: none; cursor: pointer; border: none; font-size: 0.95rem; transition: all 0.2s; }
        .btn-secondary { background: var(--border); color: #fff; }
        .btn-secondary:hover { background: #475569; }
        .btn-print { background: linear-gradient(135deg, #38bdf8, #0284c7); color: #fff; }
        .btn-print:hover { opacity: 0.9; }

        @media print {
            .navbar, .action-grid { display: none; }
            body { background: #fff; color: #000; }
            .card { border: none; box-shadow: none; background: #fff; color: #000; }
            .info-box, .chart-box, .saran-card { background: #f8fafc; border: 1px solid #ccc; color: #000; }
            .status-badge { border: 2px solid #000; }
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <a href="index.php" class="logo">🎮 <span>GameGuard System</span></a>
        <div style="font-size: 0.85rem; color: var(--text-muted); font-weight: 600;">Forward Chaining Result</div>
    </nav>

    <div class="main-wrapper">
        <div class="card">
            <div class="page-header">
                <h1>Hasil Diagnosa Skrining</h1>
                <p>Kode Referensi Transaksi: <code style="color: var(--accent);"><?= htmlspecialchars($data['kode_hasil']); ?></code></p>
            </div>

            <div class="status-badge status-<?= strtolower($data['nama_tingkat']); ?>">
                Tingkat Kecanduan: <?= strtoupper($data['nama_tingkat']); ?>
            </div>

            <div class="info-box">
                <p style="font-size: 0.95rem; margin-bottom: 8px;"><strong>Jumlah Gejala Terdeteksi:</strong> <?= $data['jumlah_gejala']; ?> Kriteria</p>
                <p style="font-size: 0.92rem; color: var(--text-muted); line-height: 1.5;"><?= $data['deskripsi']; ?></p>
            </div>

            <!-- Fitur Penjelas Inferensi (Rule Chain Transparency) -->
            <div class="info-box" style="border-left: 4px solid var(--accent);">
                <strong style="color: var(--accent); font-size: 0.9rem; display: block; margin-bottom: 4px;">🧠 Transparansi Mesin Inferensi (Forward Chaining):</strong>
                <p style="font-size: 0.88rem; color: var(--text-muted);">
                    Kesimpulan diproses dengan mencocokkan total <strong><?= $data['jumlah_gejala']; ?> gejala</strong> terhadap aturan inferensi berbasis baku <em>Online Gaming Prediction 7 Scales Dataset (GAS Lemmens)</em>[cite: 8].
                </p>
            </div>

            <!-- Chart Visualisasi (Chart.js) -->
            <div class="chart-box">
                <h3 style="font-size: 0.95rem; color: #fff; margin-bottom: 15px;">Visualisasi Distribusi Kriteria (7 Scales GAS)</h3>
                <div style="max-height: 260px; position: relative;">
                    <canvas id="gasChart"></canvas>
                </div>
            </div>

            <div class="saran-card">
                <h3 style="font-size: 1rem; color: #fff; margin-bottom: 8px;">💡 Rekomendasi Solusi & Intervensi:</h3>
                <p style="font-size: 0.92rem; color: var(--text-muted); line-height: 1.6;"><?= $data['saran']; ?></p>
            </div>

            <div class="action-grid">
                <a href="index.php" class="btn-action btn-secondary">🔄 Skrining Ulang</a>
                <button onclick="window.print()" class="btn-action btn-print">📄 Cetak Laporan PDF</button>
            </div>
        </div>
    </div>

    <script>
        // Chart Visualisasi Radar 7 Scales GAS Lemmens
        const ctx = document.getElementById('gasChart').getContext('2d');
        new Chart(ctx, {
            type: 'radar',
            data: {
                labels: ['Salience', 'Tolerance', 'Mood Modif', 'Withdrawal', 'Relapse', 'Conflict', 'Problems'],
                datasets: [{
                    label: 'Skor Kriteria Terdeteksi',
                    data: [<?= $data['jumlah_gejala'] >= 1 ? 1 : 0; ?>, <?= $data['jumlah_gejala'] >= 2 ? 1 : 0; ?>, <?= $data['jumlah_gejala'] >= 3 ? 1 : 0; ?>, <?= $data['jumlah_gejala'] >= 4 ? 1 : 0; ?>, <?= $data['jumlah_gejala'] >= 5 ? 1 : 0; ?>, <?= $data['jumlah_gejala'] >= 6 ? 1 : 0; ?>, <?= $data['jumlah_gejala'] >= 7 ? 1 : 0; ?>],
                    backgroundColor: 'rgba(99, 102, 241, 0.3)',
                    borderColor: '#6366f1',
                    borderWidth: 2,
                    pointBackgroundColor: '#38bdf8'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    r: {
                        angleLines: { color: '#334155' },
                        grid: { color: '#334155' },
                        pointLabels: { color: '#94a3b8', font: { size: 10 } },
                        ticks: { display: false }
                    }
                },
                plugins: {
                    legend: { labels: { color: '#f8fafc' } }
                }
            }
        });
    </script>
</body>
</html>