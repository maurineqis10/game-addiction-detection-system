<?php
class InferensiEngine {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function diagnosa($gejalaTerpilih) {
        $jumlahGejala = count($gejalaTerpilih);

        $sql = "SELECT a.*, t.urutan, t.nama AS nama_tingkat 
                FROM aturan a 
                JOIN tingkat t ON a.tingkat_id = t.id 
                WHERE a.aktif = 1";
        $resAturan = $this->conn->query($sql);

        $aturanCocok = [];

        while ($rule = $resAturan->fetch_assoc()) {
            $syaratMin = ($jumlahGejala >= $rule['min_gejala']);
            $syaratMax = (is_null($rule['max_gejala']) || $jumlahGejala <= $rule['max_gejala']);
            
            $syaratWajib = true;
            $resWajib = $this->conn->query("SELECT gejala_id FROM aturan_gejala WHERE aturan_id = " . $rule['id']);
            if ($resWajib->num_rows > 0) {
                while ($w = $resWajib->fetch_assoc()) {
                    if (!in_array($w['gejala_id'], $gejalaTerpilih)) {
                        $syaratWajib = false;
                        break;
                    }
                }
            }

            if ($syaratMin && $syaratMax && $syaratWajib) {
                $aturanCocok[] = $rule;
            }
        }

        usort($aturanCocok, function($a, $b) {
            return $b['urutan'] - $a['urutan'];
        });

        return $aturanCocok[0];
    }
}
?>