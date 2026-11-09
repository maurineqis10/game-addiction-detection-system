<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/InferensiEngine.php';

class SkriningController {
    private $db;
    private $engine;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->engine = new InferensiEngine($this->db);
    }

    public function getGejala() {
        return $this->db->query("SELECT * FROM gejala WHERE aktif = 1 ORDER BY kode ASC");
    }

    public function simpanSkrining($postData) {
        if (!isset($postData['gejala']) || count($postData['gejala']) === 0) {
            header("Location: index.php?error=minimal_1");
            exit;
        }

        $gejalaTerpilih = $postData['gejala'];
        $inisial = !empty($postData['inisial']) ? $this->db->real_escape_string($postData['inisial']) : NULL;
        $usia = !empty($postData['usia']) ? intval($postData['usia']) : NULL;

        $terpilih = $this->engine->diagnosa($gejalaTerpilih);
        $kodeHasil = substr(md5(uniqid(mt_rand(), true)), 0, 12);
        $jumlahGejala = count($gejalaTerpilih);

        $stmt = $this->db->prepare("INSERT INTO skrining (kode_hasil, usia, inisial, jumlah_gejala, tingkat_id, aturan_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sisiii", $kodeHasil, $usia, $inisial, $jumlahGejala, $terpilih['tingkat_id'], $terpilih['id']);
        $stmt->execute();

        header("Location: hasil.php?kode=" . $kodeHasil);
        exit;
    }

    public function getHasil($kodeHasil) {
        $kode = $this->db->real_escape_string($kodeHasil);
        $sql = "SELECT s.*, t.nama AS nama_tingkat, t.deskripsi, t.saran 
                FROM skrining s 
                JOIN tingkat t ON s.tingkat_id = t.id 
                WHERE s.kode_hasil = '$kode'";
        return $this->db->query($sql)->fetch_assoc();
    }
}
?>