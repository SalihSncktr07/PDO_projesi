<?php include "../header.php"; ?>

<div class="container">
    <div class="page-header">
        <h1>Kullanıcı Güncelleme</h1>
    </div>
    <?php
    // gelen parametre değerini oku, bizim örneğimizde bu Id bilgisidir
    $id = isset($_GET['id']) ? $_GET['id'] : die('HATA: Id bilgisi bulunamadı.');

    // veritabanı bağlantı dosyasını dahil et
    include '../../config/vtabani.php';

    // aktif kayıt bilgilerini oku
    try {
        // seçme sorgusunu hazırla
        $sorgu = "SELECT id, adsoyad, kadi, sifre FROM kullanicilar WHERE id = ? 
LIMIT 0,1";
        $stmt = $con->prepare($sorgu);

        // id parametresini bağla (? işaretini id değeri ile değiştir)
        $stmt->bindParam(1, $id);

        // sorguyu çalıştır
        $stmt->execute();

        // okunan kayıt bilgilerini bir değişkene kaydet
        $kayit = $stmt->fetch(PDO::FETCH_ASSOC);

        // formu dolduracak değişken bilgileri
        $adsoyad = $kayit['adsoyad'];
        $kadi = $kayit['kadi'];
        $sifre = $kayit['sifre'];
    }
    // hatayı göster
    catch (PDOException $exception) {
        die('HATA: ' . $exception->getMessage());
    }
    ?>
    <?php
    // Kaydet butonu tıklanmışsa
    if ($_POST) {

        try {
            // güncelleme sorgusu
            // çok fazla parametre olduğundan karışmaması için 
            // soru işaretleri yerine etiketler kullanacağız
            $sorgu = "UPDATE kullanicilar SET adsoyad=:adsoyad, kadi=:kadi, 
sifre=:sifre WHERE id = :id";

            // sorguyu hazırla
            $stmt = $con->prepare($sorgu);

            // gelen bilgileri değişkenlere kaydet
            $adsoyad = htmlspecialchars(strip_tags($_POST['adsoyad']));
            $kadi = htmlspecialchars(strip_tags($_POST['kadi']));
            $sifre = htmlspecialchars(strip_tags($_POST['sifre']));

            // parametreleri bağla
            $stmt->bindParam(':adsoyad', $adsoyad);
            $stmt->bindParam(':kadi', $kadi);
            $stmt->bindParam(':sifre', $sifre);
            $stmt->bindParam(':id', $id);

            // sorguyu çalıştır
            if ($stmt->execute()) {
                echo "<div class='alert alert-success'>Kayıt güncellendi.</div>";
            } else {
                echo "<div class='alert alert-danger'>Kayıt güncellenemedi.</div>";
            }
        }
        // hata varsa göster
        catch (PDOException $exception) {
            die('HATA: ' . $exception->getMessage());
        }
    }
    ?>
    <!-- kayıt bilgilerini güncelleyebileceğimiz HTML formu -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$id}"); ?>" method="post">
        <table class='table table-hover table-responsive table-bordered'>
            <tr>
                <td>Ad ve Soyad</td>
                <td><input type='text' name='adsoyad' value="<?php echo
                                                                htmlspecialchars($adsoyad, ENT_QUOTES); ?>" class='form-control' /></td>
            </tr>
            <tr>
                <td>Kullanıcı adı</td>
                <td><input type='text' name='kadi' value="<?php echo
                                                            htmlspecialchars($kadi, ENT_QUOTES); ?>" class='form-control' /></td>
            </tr>
            <tr>
                <td>Şifre</td>
                <td><input type='password' name='sifre' value="<?php echo
                                                                htmlspecialchars($sifre, ENT_QUOTES); ?>" class='form-control' /></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input type='submit' value='Kaydet' class='btn btn-primary' />
                    <a href='liste.php' class='btn btn-danger'>Kullanıcı listesi</a>
                </td>
            </tr>
        </table>
    </form>
</div> <!-- container -->

<?php include "../footer.php"; ?>