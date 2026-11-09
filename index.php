<?php
require_once 'app/SkriningController.php';
$controller = new SkriningController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->simpanSkrining($_POST);
}

$gejalaList = $controller->getGejala();
$gejalaArray = [];
while($g = $gejalaList->fetch_assoc()) {
    $gejalaArray[] = $g;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skrining Interaktif - GameGuard System</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --bg-body: #0f172a;
            --card-bg: #1e293b;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --border: #334155;
            --accent: #38bdf8;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background-color: var(--bg-body); color: var(--text-main); min-height: 100vh; display: flex; flex-direction: column; }
        
        .navbar { background: rgba(15, 23, 42, 0.9); backdrop-filter: blur(12px); border-bottom: 1px solid var(--border); padding: 1.2rem 2.5rem; display: flex; justify-content: space-between; align-items: center; }
        .logo { font-weight: 800; font-size: 1.25rem; color: var(--accent); text-decoration: none; display: flex; align-items: center; gap: 8px; }

        .main-wrapper { max-width: 760px; margin: 35px auto; padding: 0 20px; width: 100%; }
        .wizard-card { background: var(--card-bg); border: 1px solid var(--border); border-radius: 24px; padding: 40px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4); }

        .progress-container { margin-bottom: 28px; }
        .progress-header { display: flex; justify-content: space-between; font-size: 0.85rem; color: var(--text-muted); font-weight: 700; margin-bottom: 10px; }
        .progress-bar { height: 10px; background: var(--border); border-radius: 10px; overflow: hidden; }
        .progress-fill { height: 100%; width: 33.3%; background: linear-gradient(90deg, #6366f1, #38bdf8); transition: width 0.4s ease; }

        .wizard-step { display: none; animation: fadeIn 0.4s ease forwards; }
        .wizard-step.active { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        .step-title { font-size: 1.6rem; font-weight: 800; margin-bottom: 6px; color: #ffffff; }
        .step-desc { color: var(--text-muted); font-size: 0.92rem; margin-bottom: 26px; }

        .input-group { display: flex; flex-direction: column; gap: 8px; margin-bottom: 20px; }
        .field-label { font-size: 0.88rem; font-weight: 700; color: var(--accent); }
        .custom-input { padding: 14px 18px; background: #0f172a; border: 1px solid var(--border); border-radius: 12px; color: #fff; font-size: 1rem; outline: none; transition: all 0.2s; }
        .custom-input:focus { border-color: var(--primary); box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.2); }

        .interactive-item { display: flex; align-items: flex-start; gap: 16px; padding: 16px 20px; background: #0f172a; border: 1.5px solid var(--border); border-radius: 14px; cursor: pointer; transition: all 0.2s ease; margin-bottom: 12px; }
        .interactive-item:hover { border-color: var(--primary); transform: translateX(4px); }
        .interactive-item input[type="checkbox"] { width: 20px; height: 20px; margin-top: 2px; accent-color: var(--primary); cursor: pointer; }
        .interactive-item.checked { background: rgba(99, 102, 241, 0.18); border-color: var(--primary); }
        .item-text strong { color: #fff; font-size: 0.95rem; display: block; margin-bottom: 2px; }
        .item-text span { color: var(--text-muted); font-size: 0.83rem; }

        .wizard-nav { display: flex; justify-content: space-between; gap: 15px; margin-top: 30px; }
        .btn-nav { padding: 14px 28px; border-radius: 12px; font-size: 0.95rem; font-weight: 700; cursor: pointer; border: none; transition: all 0.2s; }
        .btn-prev { background: var(--border); color: var(--text-muted); }
        .btn-prev:hover { background: #475569; color: #fff; }
        .btn-next { background: linear-gradient(135deg, #6366f1, #4f46e5); color: white; box-shadow: 0 4px 15px rgba(99, 102, 241, 0.35); margin-left: auto; }
        .btn-next:hover { transform: translateY(-2px); box-shadow: 0 8px 22px rgba(99, 102, 241, 0.45); }
    </style>
</head>
<body>

    <nav class="navbar">
        <a href="index.php" class="logo">🎮 <span>GameGuard System</span></a>
        <div style="font-size: 0.85rem; color: var(--text-muted); font-weight: 600;">Forward Chaining (Agile Scrum)</div>
    </nav>

    <div class="main-wrapper">
        <div class="wizard-card">
            
            <div class="progress-container">
                <div class="progress-header">
                    <span id="stepIndicator">Langkah 1 dari 3</span>
                    <span id="progressPercent">33% Selesai</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" id="progressFill"></div>
                </div>
            </div>

            <form action="index.php" method="POST" id="wizardForm">

                <!-- STEP 1: Profil -->
                <div class="wizard-step active" id="step1">
                    <div class="step-title">Profil Responden</div>
                    <div class="step-desc">Isi profil singkat di bawah ini sebelum memulai kuesioner skrining mandiri.</div>

                    <div class="input-group">
                        <label class="field-label">Inisial Responden (Opsional):</label>
                        <input type="text" name="inisial" class="custom-input" placeholder="Contoh: QM">
                    </div>
                    <div class="input-group">
                        <label class="field-label">Usia Responden (Tahun):</label>
                        <input type="number" name="usia" class="custom-input" placeholder="15 - 20" min="10" max="25">
                    </div>

                    <div class="wizard-nav">
                        <button type="button" class="btn-nav btn-next" onclick="nextStep(2)">Lanjut ke 7 Kriteria GAS ➔</button>
                    </div>
                </div>

                <!-- STEP 2: 7 Scales GAS Lemmens -->
                <div class="wizard-step" id="step2">
                    <div class="step-title">7 Kriteria Utama (GAS Lemmens)</div>
                    <div class="step-desc">Centang kriteria yang kamu rasakan secara signifikan dalam 6 bulan terakhir:</div>

                    <?php for($i = 0; $i < min(7, count($gejalaArray)); $i++): ?>
                        <label class="interactive-item">
                            <input type="checkbox" name="gejala[]" value="<?= $gejalaArray[$i]['id']; ?>" onchange="updateCardStyle(this)">
                            <div class="item-text">
                                <strong><?= $gejalaArray[$i]['kode']; ?>: <?= $gejalaArray[$i]['nama']; ?></strong>
                                <span>Kriteria Baku: <?= $gejalaArray[$i]['dasar']; ?></span>
                            </div>
                        </label>
                    <?php endfor; ?>

                    <div class="wizard-nav">
                        <button type="button" class="btn-nav btn-prev" onclick="nextStep(1)">⬅ Kembali</button>
                        <button type="button" class="btn-nav btn-next" onclick="nextStep(3)">Lanjut ke Aspek Tambahan ➔</button>
                    </div>
                </div>

                <!-- STEP 3: Aspek Pendukung -->
                <div class="wizard-step" id="step3">
                    <div class="step-title">5 Aspek Lanjutan / Pendukung</div>
                    <div class="step-desc">Centang dampak tambahan yang memengaruhi kesehatan, akademik, atau sosial:</div>

                    <?php for($i = 7; $i < count($gejalaArray); $i++): ?>
                        <label class="interactive-item">
                            <input type="checkbox" name="gejala[]" value="<?= $gejalaArray[$i]['id']; ?>" onchange="updateCardStyle(this)">
                            <div class="item-text">
                                <strong><?= $gejalaArray[$i]['kode']; ?>: <?= $gejalaArray[$i]['nama']; ?></strong>
                                <span>Ref Aspect: <?= $gejalaArray[$i]['dasar']; ?></span>
                            </div>
                        </label>
                    <?php endfor; ?>

                    <div class="wizard-nav">
                        <button type="button" class="btn-nav btn-prev" onclick="nextStep(2)">⬅ Kembali</button>
                        <button type="submit" class="btn-nav btn-next" style="background: linear-gradient(135deg, #10b981, #059669);">Analisis Hasil Forward Chaining 🚀</button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <script>
        function nextStep(step) {
            document.querySelectorAll('.wizard-step').forEach(s => s.classList.remove('active'));
            document.getElementById('step' + step).classList.add('active');

            const percent = step === 1 ? '33.3%' : (step === 2 ? '66.6%' : '100%');
            document.getElementById('progressFill').style.width = percent;
            document.getElementById('progressPercent').innerText = (step === 1 ? '33%' : (step === 2 ? '66%' : '100%')) + ' Selesai';
            document.getElementById('stepIndicator').innerText = 'Langkah ' + step + ' dari 3';
        }

        function updateCardStyle(checkbox) {
            const label = checkbox.closest('.interactive-item');
            if (checkbox.checked) {
                label.classList.add('checked');
            } else {
                label.classList.remove('checked');
            }
        }
    </script>
</body>
</html>