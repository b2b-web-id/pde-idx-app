<?php

use yii\db\Migration;

/**
 * Handles seeding idx_klasifikasi table with IDX classification hierarchy.
 */
class m240101000007_seed_idx_klasifikasi extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Get sektor IDs
        $sektors = [];
        $rows = (new \yii\db\Query())
            ->select(['id', 'kode'])
            ->from('{{%sektor}}')
            ->where(['aktif' => 1])
            ->all();
        foreach ($rows as $row) {
            $sektors[$row['kode']] = $row['id'];
        }

        // Helper function to insert and get ID
        $insertAndGetId = function ($k, $n, $lvl, $pid = null, $sid = null, $urut = 0) {
            $this->insert('{{%idx_klasifikasi}}', [
                'kode' => $k,
                'nama' => $n,
                'level' => $lvl,
                'parent_id' => $pid,
                'sektor_id' => $sid,
                'urutan' => $urut,
                'aktif' => 1,
            ]);
            return $this->db->getLastInsertID();
        };

        // =============================================
        // 1. KEUANGAN (Kode: 10)
        // =============================================
        $keuangan = $insertAndGetId('10', 'Keuangan', 2, null, $sektors['KEU'] ?? null, 1);

        $bank = $insertAndGetId('1010', 'Perbankan', 3, $keuangan, null, 1);
        $insertAndGetId('101010', 'Bank Umum', 4, $bank, null, 1);
        $insertAndGetId('101020', 'Bank Syariah', 4, $bank, null, 2);
        $insertAndGetId('101030', 'Bank Campuran', 4, $bank, null, 3);

        $multifinance = $insertAndGetId('1020', 'Multifinance', 3, $keuangan, null, 2);
        $insertAndGetId('102010', 'Pembiayaan Konsumen', 4, $multifinance, null, 1);
        $insertAndGetId('102020', 'Pembiayaan Komersial', 4, $multifinance, null, 2);
        $insertAndGetId('102030', 'Factoring', 4, $multifinance, null, 3);

        $asuransi = $insertAndGetId('1030', 'Asuransi', 3, $keuangan, null, 3);
        $insertAndGetId('103010', 'Asuransi Jiwa', 4, $asuransi, null, 1);
        $insertAndGetId('103020', 'Asuransi Umum', 4, $asuransi, null, 2);
        $insertAndGetId('103030', 'Reasuransi', 4, $asuransi, null, 3);

        $sekuritas = $insertAndGetId('1040', 'Sekuritas & Bursa', 3, $keuangan, null, 4);
        $insertAndGetId('104010', 'Perusahaan Efek', 4, $sekuritas, null, 1);
        $insertAndGetId('104020', 'Bursa Efek', 4, $sekuritas, null, 2);
        $insertAndGetId('104030', 'Kustodian', 4, $sekuritas, null, 3);

        $dana = $insertAndGetId('1050', 'Dana Investasi', 3, $keuangan, null, 5);
        $insertAndGetId('105010', 'Reksa Dana', 4, $dana, null, 1);
        $insertAndGetId('105020', 'Dana Pensiun', 4, $dana, null, 2);

        // =============================================
        // 2. INDUSTRI (Kode: 20)
        // =============================================
        $industri = $insertAndGetId('20', 'Industri', 2, null, $sektors['IND'] ?? null, 2);

        $mm = $insertAndGetId('2010', 'Makanan & Minuman', 3, $industri, null, 1);
        $insertAndGetId('201010', 'Makanan Olahan', 4, $mm, null, 1);
        $insertAndGetId('201020', 'Minuman', 4, $mm, null, 2);
        $insertAndGetId('201030', 'Tembakau', 4, $mm, null, 3);

        $kimia = $insertAndGetId('2020', 'Kimia Dasar', 3, $industri, null, 2);
        $insertAndGetId('202010', 'Petrokimia', 4, $kimia, null, 1);
        $insertAndGetId('202020', 'Kimia Anorganik', 4, $kimia, null, 2);
        $insertAndGetId('202030', 'Kimia Organik', 4, $kimia, null, 3);

        $farmasi = $insertAndGetId('2030', 'Farmasi', 3, $industri, null, 3);
        $insertAndGetId('203010', 'Obat Farmasi', 4, $farmasi, null, 1);
        $insertAndGetId('203020', 'Biologis & Vaksin', 4, $farmasi, null, 2);

        $logam = $insertAndGetId('2040', 'Logam & Mineral', 3, $industri, null, 4);
        $insertAndGetId('204010', 'Besi & Baja', 4, $logam, null, 1);
        $insertAndGetId('204020', 'Aluminium', 4, $logam, null, 2);
        $insertAndGetId('204030', 'Mineral Lainnya', 4, $logam, null, 3);

        $kendaraan = $insertAndGetId('2050', 'Otomotif & Komponen', 3, $industri, null, 5);
        $insertAndGetId('205010', 'Kendaraan Bermotor', 4, $kendaraan, null, 1);
        $insertAndGetId('205020', 'Komponen & Aksesoris', 4, $kendaraan, null, 2);

        $kertas = $insertAndGetId('2060', 'Kertas & Pulp', 3, $industri, null, 6);
        $insertAndGetId('206010', 'Pulp', 4, $kertas, null, 1);
        $insertAndGetId('206020', 'Kertas & Karton', 4, $kertas, null, 2);

        // =============================================
        // 3. PROPERTI & REAL ESTATE (Kode: 30)
        // =============================================
        $properti = $insertAndGetId('30', 'Properti & Real Estate', 2, null, $sektors['PRO'] ?? null, 3);

        $pengembang = $insertAndGetId('3010', 'Pengembang Properti', 3, $properti, null, 1);
        $insertAndGetId('301010', 'Perumahan', 4, $pengembang, null, 1);
        $insertAndGetId('301020', 'Komersial (Mall/Kantor)', 4, $pengembang, null, 2);
        $insertAndGetId('301030', 'Industri & Gudang', 4, $pengembang, null, 3);

        $reit = $insertAndGetId('3020', 'REIT', 3, $properti, null, 2);
        $insertAndGetId('302010', 'REIT Perumahan', 4, $reit, null, 1);
        $insertAndGetId('302020', 'REIT Komersial', 4, $reit, null, 2);

        $jasa_properti = $insertAndGetId('3030', 'Jasa Properti', 3, $properti, null, 3);
        $insertAndGetId('303010', 'Manajemen Properti', 4, $jasa_properti, null, 1);
        $insertAndGetId('303020', 'Agen & Broker', 4, $jasa_properti, null, 2);

        // =============================================
        // 4. INFRASTRUKTUR (Kode: 40)
        // =============================================
        $infra = $insertAndGetId('40', 'Infrastruktur', 2, null, $sektors['INF'] ?? null, 4);

        $energi = $insertAndGetId('4010', 'Energi', 3, $infra, null, 1);
        $insertAndGetId('401010', 'Listrik (PLN/IPP)', 4, $energi, null, 1);
        $insertAndGetId('401020', 'Minyak & Gas Bumi', 4, $energi, null, 2);
        $insertAndGetId('401030', 'Energi Terbarukan', 4, $energi, null, 3);

        $telekom = $insertAndGetId('4020', 'Telekomunikasi', 3, $infra, null, 2);
        $insertAndGetId('402010', 'Telco (GSM/CDMA)', 4, $telekom, null, 1);
        $insertAndGetId('402020', 'Internet & Data', 4, $telekom, null, 2);
        $insertAndGetId('402030', 'Satelit & Tower', 4, $telekom, null, 3);

        $transport = $insertAndGetId('4030', 'Transportasi Infrastruktur', 3, $infra, null, 3);
        $insertAndGetId('403010', 'Tol & Jalan', 4, $transport, null, 1);
        $insertAndGetId('403020', 'Bandara & Pelabuhan', 4, $transport, null, 2);
        $insertAndGetId('403030', 'KA & MRT/LRT', 4, $transport, null, 3);

        $air = $insertAndGetId('4040', 'Air & Sanitasi', 3, $infra, null, 4);
        $insertAndGetId('404010', 'PDAM', 4, $air, null, 1);
        $insertAndGetId('404020', 'Waste Management', 4, $air, null, 2);

        // =============================================
        // 5. PERDAGANGAN (Kode: 50)
        // =============================================
        $perdagangan = $insertAndGetId('50', 'Perdagangan', 2, null, $sektors['PER'] ?? null, 5);

        $ritel = $insertAndGetId('5010', 'Ritel', 3, $perdagangan, null, 1);
        $insertAndGetId('501010', 'Minimarket & Supermarket', 4, $ritel, null, 1);
        $insertAndGetId('501020', 'Department Store', 4, $ritel, null, 2);
        $insertAndGetId('501030', 'E-commerce', 4, $ritel, null, 3);

        $grosir = $insertAndGetId('5020', 'Grosir & Distribusi', 3, $perdagangan, null, 2);
        $insertAndGetId('502010', 'Distributor', 4, $grosir, null, 1);
        $insertAndGetId('502020', 'Importir', 4, $grosir, null, 2);

        // =============================================
        // 6. JASA (Kode: 60)
        // =============================================
        $jasa = $insertAndGetId('60', 'Jasa', 2, null, $sektors['JAS'] ?? null, 6);

        $transport = $insertAndGetId('6010', 'Transportasi & Logistik', 3, $jasa, null, 1);
        $insertAndGetId('601010', 'Angkutan Laut', 4, $transport, null, 1);
        $insertAndGetId('601020', 'Angkutan Udara', 4, $transport, null, 2);
        $insertAndGetId('601030', 'Logistik & Gudang', 4, $transport, null, 3);

        $hotel = $insertAndGetId('6020', 'Hotel & Restoran', 3, $jasa, null, 2);
        $insertAndGetId('602010', 'Hotel', 4, $hotel, null, 1);
        $insertAndGetId('602020', 'Restoran & Kafe', 4, $hotel, null, 2);

        $pendidikan = $insertAndGetId('6030', 'Pendidikan', 3, $jasa, null, 3);
        $insertAndGetId('603010', 'Pendidikan Formal', 4, $pendidikan, null, 1);
        $insertAndGetId('603020', 'Kursus & Pelatihan', 4, $pendidikan, null, 2);

        $kesehatan = $insertAndGetId('6040', 'Kesehatan', 3, $jasa, null, 4);
        $insertAndGetId('604010', 'Rumah Sakit', 4, $kesehatan, null, 1);
        $insertAndGetId('604020', 'Laboratorium & Klinik', 4, $kesehatan, null, 2);

        // =============================================
        // 7. PERTANIAN (Kode: 70)
        // =============================================
        $pertanian = $insertAndGetId('70', 'Pertanian', 2, null, $sektors['PERT'] ?? null, 7);

        $perkebunan = $insertAndGetId('7010', 'Perkebunan', 3, $pertanian, null, 1);
        $insertAndGetId('701010', 'Kelapa Sawit', 4, $perkebunan, null, 1);
        $insertAndGetId('701020', 'Karet', 4, $perkebunan, null, 2);
        $insertAndGetId('701030', 'Kopi & Kakao', 4, $perkebunan, null, 3);

        $peternakan = $insertAndGetId('7020', 'Peternakan', 3, $pertanian, null, 2);
        $insertAndGetId('702010', 'Unggas', 4, $peternakan, null, 1);
        $insertAndGetId('702020', 'Ternak Besar', 4, $peternakan, null, 2);

        $perikanan = $insertAndGetId('7030', 'Perikanan', 3, $pertanian, null, 3);
        $insertAndGetId('703010', 'Budidaya', 4, $perikanan, null, 1);
        $insertAndGetId('703020', 'Tangkapan', 4, $perikanan, null, 2);

        // =============================================
        // 8. TAMBANG (Kode: 80)
        // =============================================
        $tambang = $insertAndGetId('80', 'Tambang', 2, null, $sektors['TAMB'] ?? null, 8);

        $migas = $insertAndGetId('8010', 'Minyak & Gas Bumi', 3, $tambang, null, 1);
        $insertAndGetId('801010', 'Eksplorasi & Produksi', 4, $migas, null, 1);
        $insertAndGetId('801020', 'Raffinasi & Petrokimia', 4, $migas, null, 2);

        $batubara = $insertAndGetId('8020', 'Batubara & Mineral', 3, $tambang, null, 2);
        $insertAndGetId('802010', 'Batubara', 4, $batubara, null, 1);
        $insertAndGetId('802020', 'Emas & Tembaga', 4, $batubara, null, 2);
        $insertAndGetId('802030', 'Nickel & Bauksit', 4, $batubara, null, 3);

        // =============================================
        // 9. KONSTRUKSI (Kode: 90)
        // =============================================
        $konstruksi = $insertAndGetId('90', 'Konstruksi', 2, null, $sektors['KONS'] ?? null, 9);

        $bangunan = $insertAndGetId('9010', 'Konstruksi Bangunan', 3, $konstruksi, null, 1);
        $insertAndGetId('901010', 'Gedung Tinggi', 4, $bangunan, null, 1);
        $insertAndGetId('901020', 'Perumahan', 4, $bangunan, null, 2);

        $sipil = $insertAndGetId('9020', 'Konstruksi Sipil', 3, $konstruksi, null, 2);
        $insertAndGetId('902010', 'Jalan & Jembatan', 4, $sipil, null, 1);
        $insertAndGetId('902020', 'Bendungan & Irigasi', 4, $sipil, null, 2);

        // =============================================
        // 10. TEKNOLOGI (Kode: 95)
        // =============================================
        $teknologi = $insertAndGetId('95', 'Teknologi', 2, null, $sektors['TEK'] ?? null, 10);

        $software = $insertAndGetId('9510', 'Software & IT Services', 3, $teknologi, null, 1);
        $insertAndGetId('951010', 'Aplikasi Enterprise', 4, $software, null, 1);
        $insertAndGetId('951020', 'SaaS & Cloud', 4, $software, null, 2);
        $insertAndGetId('951030', 'Fintech & Digital Payment', 4, $software, null, 3);
        $insertAndGetId('951040', 'E-commerce & Marketplace', 4, $software, null, 4);
        $insertAndGetId('951050', 'On-Demand Services', 4, $software, null, 5);

        $hardware = $insertAndGetId('9520', 'Hardware & Device', 3, $teknologi, null, 2);
        $insertAndGetId('952010', 'Semiconductor', 4, $hardware, null, 1);
        $insertAndGetId('952020', 'IoT Device', 4, $hardware, null, 2);

        // =============================================
        // 11. LAINNYA (Kode: 99)
        // =============================================
        $lainnya = $insertAndGetId('99', 'Lainnya', 2, null, $sektors['LAIN'] ?? null, 11);
        $insertAndGetId('9910', 'Belum Terklasifikasi', 3, $lainnya, null, 1);
        $insertAndGetId('991010', 'Lain-lain', 4, $lainnya, null, 1);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->truncateTable('{{%idx_klasifikasi}}');
    }
}