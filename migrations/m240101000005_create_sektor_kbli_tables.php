<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%sektor}}` and `{{%kbli}}`.
 * Reference tables for IDX sector classification and KBLI codes.
 */
class m240101000005_create_sektor_kbli_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        // 1. Sektor table (IDX Sector Classification)
        $this->createTable('{{%sektor}}', [
            'id' => $this->primaryKey(),
            'kode' => $this->string(10)->notNull()->unique(),
            'nama' => $this->string(100)->notNull(),
            'deskripsi' => $this->text()->null(),
            'urutan' => $this->integer()->defaultValue(0),
            'aktif' => $this->boolean()->defaultValue(true),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);

        $this->createIndex('{{%idx_sektor_kode}}', '{{%sektor}}', 'kode');
        $this->createIndex('{{%idx_sektor_aktif}}', '{{%sektor}}', 'aktif');

        // 2. KBLI table (Klasifikasi Baku Lapangan Usaha Indonesia - BPS)
        $this->createTable('{{%kbli}}', [
            'id' => $this->primaryKey(),
            'kode' => $this->string(5)->notNull()->unique()->comment('Kode KBLI 5 digit, contoh: 64111'),
            'nama' => $this->string(255)->notNull()->comment('Nama lengkap usaha'),
            'kelompok' => $this->string(3)->null()->comment('Kelompok 3 digit, contoh: 641'),
            'golongan' => $this->string(2)->null()->comment('Golongan 2 digit, contoh: 64'),
            'bidang' => $this->string(1)->null()->comment('Bidang 1 huruf, contoh: K'),
            'deskripsi' => $this->text()->null(),
            'sektor_id' => $this->integer()->null()->comment('FK ke sektor'),
            'aktif' => $this->boolean()->defaultValue(true),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);

        $this->createIndex('{{%idx_kbli_kode}}', '{{%kbli}}', 'kode');
        $this->createIndex('{{%idx_kbli_kelompok}}', '{{%kbli}}', 'kelompok');
        $this->createIndex('{{%idx_kbli_golongan}}', '{{%kbli}}', 'golongan');
        $this->createIndex('{{%idx_kbli_bidang}}', '{{%kbli}}', 'bidang');
        $this->createIndex('{{%idx_kbli_sektor}}', '{{%kbli}}', 'sektor_id');
        $this->createIndex('{{%idx_kbli_aktif}}', '{{%kbli}}', 'aktif');
        $this->createIndex('{{%idx_kbli_nama}}', '{{%kbli}}', 'nama');

        // FK KBLI -> Sektor
        $this->addForeignKey(
            '{{%fk_kbli_sektor}}',
            '{{%kbli}}',
            'sektor_id',
            '{{%sektor}}',
            'id',
            'SET NULL',
            'CASCADE'
        );

        // 3. Add FK to Perusahaan table
        $this->addColumn('{{%perusahaan}}', 'sektor_id', $this->integer()->null()->after('KODE_KBLI')->comment('FK ke sektor'));
        $this->addColumn('{{%perusahaan}}', 'kbli_id', $this->integer()->null()->after('sektor_id')->comment('FK ke kbli'));

        $this->createIndex('{{%idx_perusahaan_sektor}}', '{{%perusahaan}}', 'sektor_id');
        $this->createIndex('{{%idx_perusahaan_kbli}}', '{{%perusahaan}}', 'kbli_id');

        $this->addForeignKey(
            '{{%fk_perusahaan_sektor}}',
            '{{%perusahaan}}',
            'sektor_id',
            '{{%sektor}}',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->addForeignKey(
            '{{%fk_perusahaan_kbli}}',
            '{{%perusahaan}}',
            'kbli_id',
            '{{%kbli}}',
            'id',
            'SET NULL',
            'CASCADE'
        );

        // 4. Insert default Sektor data (IDX Classification)
        $this->batchInsert('{{%sektor}}', ['kode', 'nama', 'deskripsi', 'urutan', 'aktif'], [
            ['KEU', 'Keuangan', 'Bank, Asuransi, Sekuritas, Multifinance', 1, true],
            ['IND', 'Industri', 'Manufaktur, Kimia, Logam, Makanan & Minuman', 2, true],
            ['PRO', 'Properti & Real Estate', 'Pengembang Properti, REIT', 3, true],
            ['INF', 'Infrastruktur', 'Tol, Bandara, Pelabuhan, PLN, Telekomunikasi', 4, true],
            ['PER', 'Perdagangan', 'Retail, Grossir, E-commerce', 5, true],
            ['JAS', 'Jasa', 'Transportasi, Hotel, Restoran, Pendidikan, Kesehatan', 6, true],
            ['PERT', 'Pertanian', 'Perkebunan, Peternakan, Perikanan, Kehutanan', 7, true],
            ['TAMB', 'Tambang', 'Minyak & Gas, Batu Bara, Mineral, Batubara', 8, true],
            ['KONS', 'Konstruksi', 'Bangunan, Jalan, Jembatan, Bangunan Air', 9, true],
            ['TEK', 'Teknologi', 'Software, Hardware, IT Services, Startup', 10, true],
            ['LAIN', 'Lainnya', 'Sektor lain yang belum terklasifikasi', 99, true],
        ]);

        // 5. Insert sample KBLI data (common ones for IDX companies)
        $this->batchInsert('{{%kbli}}', ['kode', 'nama', 'kelompok', 'golongan', 'bidang', 'sektor_id', 'aktif'], [
            // KEUANGAN (K)
            ['64111', 'Perbankan Umum', '641', '64', 'K', 1, true],
            ['64191', 'Bank Syariah', '641', '64', 'K', 1, true],
            ['64200', 'Perusahaan Pembiayaan', '642', '64', 'K', 1, true],
            ['64300', 'Perusahaan Penyewaan Dana', '643', '64', 'K', 1, true],
            ['64910', 'Perusahaan Penjaminan Kredit', '649', '64', 'K', 1, true],
            ['64991', 'Perusahaan Multifinance', '649', '64', 'K', 1, true],
            ['64992', 'Perusahaan Factoring', '649', '64', 'K', 1, true],
            ['65111', 'Asuransi Jiwa', '651', '65', 'K', 1, true],
            ['65121', 'Asuransi Umum', '651', '65', 'K', 1, true],
            ['65200', 'Reasuransi', '652', '65', 'K', 1, true],
            ['65300', 'Dana Pensiun', '653', '65', 'K', 1, true],
            ['66111', 'Bursa Efek', '661', '66', 'K', 1, true],
            ['66121', 'Perusahaan Efek/Sekuritas', '661', '66', 'K', 1, true],
            ['66191', 'KLPE (Kustodian)', '661', '66', 'K', 1, true],
            ['66210', 'Pengelola Dana Investasi', '662', '66', 'K', 1, true],
            ['66300', 'Penunjang Keuangan Lainnya', '663', '66', 'K', 1, true],

            // INDUSTRI - MAKANAN & MINUMAN (C)
            ['10101', 'Pengolahan & Pengawetan Daging', '101', '10', 'C', 2, true],
            ['10301', 'Pengolahan & Pengawetan Buah & Sayur', '103', '10', 'C', 2, true],
            ['10401', 'Minyak & Lemak Nabati & Hewani', '104', '10', 'C', 2, true],
            ['10501', 'Produk Susu', '105', '10', 'C', 2, true],
            ['10611', 'Pembuatan Tepung & Produk Tepung', '106', '10', 'C', 2, true],
            ['10711', 'Produk Roti & Kue', '107', '10', 'C', 2, true],
            ['10731', 'Cokelat & Permen', '107', '10', 'C', 2, true],
            ['10791', 'Makanan Olahan Lainnya', '107', '10', 'C', 2, true],
            ['11011', 'Minuman Beralkohol', '110', '11', 'C', 2, true],
            ['11041', 'Minuman Ringan', '110', '11', 'C', 2, true],

            // INDUSTRI - KIMIA (C)
            ['20111', 'Kimia Dasar Anorganik', '201', '20', 'C', 2, true],
            ['20121', 'Kimia Dasar Organik', '201', '20', 'C', 2, true],
            ['20211', 'Pupuk & Bahan Kimia Pertanian', '202', '20', 'C', 2, true],
            ['20221', 'Pestisida & Bahan Agrokimia', '202', '20', 'C', 2, true],
            ['20231', 'Cat, Tinta & Lak', '202', '20', 'C', 2, true],
            ['20291', 'Produk Kimia Lainnya', '202', '20', 'C', 2, true],
            ['21001', 'Farmasi', '210', '21', 'C', 2, true],
            ['22111', 'Ban Luar & Tabung Ban', '221', '22', 'C', 2, true],
            ['22201', 'Produk Plastik Lainnya', '222', '22', 'C', 2, true],

            // INDUSTRI - LOGAM & NON LOGAM (C)
            ['24101', 'Besi & Baja Dasar', '241', '24', 'C', 2, true],
            ['24201', 'Logam Mulia & Non Besi Dasar', '242', '24', 'C', 2, true],
            ['24311', 'Pengecoran Besi & Baja', '243', '24', 'C', 2, true],
            ['25111', 'Produk Logam untuk Konstruksi', '251', '25', 'C', 2, true],
            ['25911', 'Pengecatan & Pelapisan Logam', '259', '25', 'C', 2, true],

            // PROPERTI & REAL ESTATE (L)
            ['68101', 'Real Estate - Pengembangan Properti', '681', '68', 'L', 3, true],
            ['68201', 'Real Estate - Sewa & Pengelolaan Properti', '682', '68', 'L', 3, true],
            ['68301', 'Real Estate - Jasa Agen Properti', '683', '68', 'L', 3, true],

            // INFRASTRUKTUR (H, J)
            ['42101', 'Konstruksi Bangunan Gedung', '421', '42', 'F', 4, true],
            ['42201', 'Konstruksi Jalan & Jembatan', '422', '42', 'F', 4, true],
            ['42901', 'Konstruksi Bangunan Air', '429', '42', 'F', 4, true],
            ['35101', 'Ketenagalistrikan', '351', '35', 'D', 4, true],
            ['35201', 'Distribusi Gas', '352', '35', 'D', 4, true],
            ['61101', 'Telekomunikasi Kabel', '611', '61', 'J', 4, true],
            ['61201', 'Telekomunikasi Nirkabel', '612', '61', 'J', 4, true],
            ['61301', 'Telekomunikasi Satelit', '613', '61', 'J', 4, true],

            // PERDAGANGAN (G)
            ['46101', 'Perdagangan Besar (Wholesale)', '461', '46', 'G', 5, true],
            ['47111', 'Minimarket & Supermarket', '471', '47', 'G', 5, true],
            ['47191', 'Toko Departemen', '471', '47', 'G', 5, true],
            ['47911', 'E-commerce', '479', '47', 'G', 5, true],

            // TEKNOLOGI (J)
            ['62011', 'Jasa Pemrograman Komputer', '620', '62', 'J', 10, true],
            ['62091', 'Jasa TI Lainnya', '620', '62', 'J', 10, true],
            ['63111', 'Portal & Jasa Internet', '631', '63', 'J', 10, true],
            ['63121', 'Data Center & Hosting', '631', '63', 'J', 10, true],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fk_perusahaan_kbli}}', '{{%perusahaan}}');
        $this->dropForeignKey('{{%fk_perusahaan_sektor}}', '{{%perusahaan}}');
        $this->dropColumn('{{%perusahaan}}', 'kbli_id');
        $this->dropColumn('{{%perusahaan}}', 'sektor_id');

        $this->dropForeignKey('{{%fk_kbli_sektor}}', '{{%kbli}}');
        $this->dropTable('{{%kbli}}');
        $this->dropTable('{{%sektor}}');
    }
}