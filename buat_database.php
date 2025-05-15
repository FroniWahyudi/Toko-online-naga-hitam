<?php
if (isset($_POST['buat'])) {
    $host = "localhost";
    $user = "root";
    $pass = "";
    $dbname = "db_tokoonline_nagahitam";

    $conn = new mysqli($host, $user, $pass);
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    $conn->query("DROP DATABASE IF EXISTS $dbname") or die("Gagal menghapus database.");
    $conn->query("CREATE DATABASE $dbname") or die("Gagal membuat database.");
    $conn->select_db($dbname);

    $sql = <<<SQL
    CREATE TABLE failed_jobs (
        id BIGINT AUTO_INCREMENT PRIMARY KEY,
        uuid VARCHAR(255) NOT NULL,
        connection TEXT NOT NULL,
        queue TEXT NOT NULL,
        payload LONGTEXT NOT NULL,
        exception LONGTEXT NOT NULL,
        failed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE kategori (
        id BIGINT AUTO_INCREMENT PRIMARY KEY,
        nama_kategori VARCHAR(255) NOT NULL
    );

    INSERT INTO kategori (id, nama_kategori) VALUES
    (1, 'Brownies'),
    (2, 'Combro'),
    (3, 'Dawet'),
    (4, 'Mochi'),
    (5, 'Wingko');

    CREATE TABLE migrations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        migration VARCHAR(255) NOT NULL,
        batch INT NOT NULL
    );

    INSERT INTO migrations (id, migration, batch) VALUES
    (1, '2014_10_12_000000_create_users_table', 1),
    (2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
    (3, '2019_08_19_000000_create_failed_jobs_table', 1),
    (4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
    (5, '2025_04_28_114357_create_kategori_table', 1),
    (6, '2025_04_29_085053_create_produk_table', 1);

    CREATE TABLE password_reset_tokens (
        email VARCHAR(255) NOT NULL PRIMARY KEY,
        token VARCHAR(255) NOT NULL,
        created_at TIMESTAMP NULL
    );

    CREATE TABLE personal_access_tokens (
        id BIGINT AUTO_INCREMENT PRIMARY KEY,
        tokenable_type VARCHAR(255) NOT NULL,
        tokenable_id BIGINT NOT NULL,
        name VARCHAR(255) NOT NULL,
        token VARCHAR(64) NOT NULL,
        abilities TEXT,
        last_used_at TIMESTAMP NULL,
        expires_at TIMESTAMP NULL,
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL
    );

    CREATE TABLE produk (
        id BIGINT AUTO_INCREMENT PRIMARY KEY,
        kategori_id BIGINT NOT NULL,
        user_id BIGINT NOT NULL,
        status TINYINT(1) NOT NULL,
        nama_produk VARCHAR(255) NOT NULL,
        detail TEXT NOT NULL,
        harga DOUBLE NOT NULL,
        stok INT NOT NULL,
        berat DOUBLE(8,2) NOT NULL,
        foto VARCHAR(255) NOT NULL,
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL
    );

    CREATE TABLE user (
        id BIGINT AUTO_INCREMENT PRIMARY KEY,
        nama VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        role ENUM('0', '1', '2') NOT NULL DEFAULT '0',
        status TINYINT(1) NOT NULL,
        password VARCHAR(255) NOT NULL,
        hp VARCHAR(13) NOT NULL,
        foto VARCHAR(255),
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL
    );
    SQL;

    if ($conn->multi_query($sql)) {
        do {
            if ($result = $conn->store_result()) {
                $result->free();
            }
        } while ($conn->next_result());
        echo "<h3>✅ Database <code>$dbname</code> berhasil dibuat ulang.</h3>";
    } else {
        echo "❌ Terjadi kesalahan: " . $conn->error;
    }

    $conn->close();
} else {
    ?>
    <form method="post">
        <h3>Tekan tombol di bawah untuk membuat ulang database <code>db_tokoonline_nagahitam</code>.</h3>
        <button type="submit" name="buat" onclick="return confirm('Yakin ingin membuat ulang database? Semua data lama akan dihapus!')">
            🔁 Buat Ulang Database
        </button>
    </form>
    <?php
}
?>
