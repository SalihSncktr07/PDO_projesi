<?php include "../header.php"; ?>
<div class="container">
    <div class="page-header">
        <h1>Kullanıcı Listesi</h1>
    </div>
    <?php
    // veritabanı bağlantı dosyasını çağır
    include '../../config/vtabani.php';

    $islem = isset($_GET['islem']) ? $_GET['islem'] : "";
    // eğer silme (sil.php) sayfasından yönlendirme yapıldıysa
    if ($islem == 'silindi') {
        echo "<div class='alert alert-success'>Kayıt silindi.</div>";
    } else if ($islem == 'silinemedi') {
        echo "<div class='alert alert-danger'>Kayıt silinemedi.</div>";
    }
    // bütün kayıtları seç
    $sorgu = "SELECT id, adsoyad, kadi FROM kullanicilar ORDER BY id DESC";
    $stmt = $con->prepare($sorgu);
    $stmt->execute();

    // geriye dönen kayıt sayısı
    $sayi = $stmt->rowCount();

    // kayıt ekleme sayfasının linki
    echo "<a href='ekle.php' class='btn btn-primary m-b-1em'>Yeni Kullanıcı</a>";

    //kayıt varsa listele
    if ($sayi > 0) {

        echo "<table class='table table-hover table-responsive table-bordered'>";
        //tablo başlangıcı
        //tablo başlıkları
        echo "<tr>";
        echo "<th>ID</th>";
        echo "<th>Ad ve Soyad</th>";
        echo "<th>Kullanıcı adı</th>";
        echo "<th>İşlem</th>";
        echo "</tr>";

        // tablo verilerinin okunması
        while ($kayit = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // tablo alanlarını değişkene dönüştürür
            // $kayit['adsoyad'] => $adsoyad
            extract($kayit);

            // her kayıt için yeni bir tablo satırı oluştur
            echo "<tr>";
            echo "<td>{$id}</td>";
            echo "<td>{$adsoyad}</td>";
            echo "<td>{$kadi}</td>";
            echo "<td>";
            // kayıt güncelleme sayfa bağlantısı
            echo "<a href='duzelt.php?id={$id}' class='btn btn-primary m-r1em'>Düzelt</a>";
            // kayıt silme butonu
            echo "<a href='#' onclick='silme_onay({$id});' class='btn btndanger'>Sil</a>";
            echo "</td>";
            echo "</tr>";
        }

        echo "</table>"; // tablo sonu
    }
    // kayıt yoksa mesajla bildir
    else {
        echo "<div class='alert alert-danger'>Listelenecek kayıt bulunamadı.</div>";
    }
    ?>
</div> <!-- /container -->
<?php include "../footer.php"; ?>
<script type='text/javascript'>
    // kayıt silme işlemini onayla
    function silme_onay(id) {

        var cevap = confirm('Kaydı silmek istiyor musunuz?');
        if (cevap) {
            // kullanıcı evet derse, 
            // id bilgisini sil.php sayfasına yönlendirir
            window.location = 'sil.php?id=' + id;
        }
    }
</script>