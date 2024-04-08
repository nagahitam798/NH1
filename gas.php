<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TUNNEL AUTO CREATE FOLDER & SITEMAP</title>
</head>
<body>
  <h1>TUNNEL AUTO CREATE FOLDER & SITEMAP</h1>

  <form action="" method="post">
    <label for="listFile">Masukkan Nama File List:</label>
    <input type="text" id="listFile" name="listFile">
    <button type="submit">Submit</button>
  </form>

  <div>
    <h2>Hasil:</h2>
    <ul>
    <?php
    function clean_dir_name($dirName) {
      return preg_replace('/[^\w\d]/u', '', $dirName);
    }

    function generate_sitemap($dirList) {
      $xml = '<?xml version="1.0" encoding="UTF-8"?>';
      $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

      //jangan lupa ganti nama https://domain.com/ sesuai dengan posisi tunnel anda
      foreach ($dirList as $dir) {
        $xml .= '<url>';
        $xml .= '<loc>https://domain.com/' . $dir . '/</loc>';
        $xml .= '<lastmod>' . date("Y-m-d") . '</lastmod>';
        $xml .= '</url>';
      }

      $xml .= '</urlset>';
      
      file_put_contents("sitemap.xml", $xml);
      echo "<li>Sitemap telah berhasil dibuat.</li>";
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $fileName = $_POST["listFile"];
      $list_nama_dir = file($fileName, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
      $isi_content = file_get_contents("landingpage.txt");

      foreach ($list_nama_dir as $nama_dir) {
        $clean_nama_dir = clean_dir_name($nama_dir);
        $path = "./$clean_nama_dir";

        if (!is_dir($path)) {
          mkdir($path, 0755, true);
        }

        $file_path = "$path/index.html";
        $updated_content = str_replace("BRANDD", $clean_nama_dir, $isi_content);
        file_put_contents($file_path, $updated_content);

        echo "<li>Direktori dan file index.html untuk $clean_nama_dir telah berhasil dibuat.</li>";
      }

      generate_sitemap($list_nama_dir);
    }
    ?>
    </ul>
  </div>
</body>
</html>
