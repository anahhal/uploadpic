<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>عرض الصور المخزنة</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>الصور المخزنة</h1>
    <div class="image-container">
        <?php
        $servername = "fdb1033.awardspace.net";
        $username = "4062881_invoice";
        $password = "ucas@2024";
        $dbname = "4062881_invoice";

        // إنشاء الاتصال بقاعدة البيانات
        $conn = new mysqli($servername, $username, $password, $dbname);

        // التحقق من الاتصال
        if ($conn->connect_error) {
            die("فشل الاتصال: " . $conn->connect_error);
        }

        // حذف الصورة إذا تم إرسال طلب الحذف
        if (isset($_POST['delete']) && isset($_POST['id']) && isset($_POST['path'])) {
            $id = $_POST['id'];
            $path = $_POST['path'];

            // حذف الصورة من المجلد
            if (file_exists($path)) {
                unlink($path);
            }

            // حذف الصورة من قاعدة البيانات
            $sql = "DELETE FROM images WHERE id = $id";
            $conn->query($sql);
        }

        // جلب الصور من قاعدة البيانات
        $sql = "SELECT id, image_path FROM images";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<div class='image-wrapper' data-id='" . $row['id'] . "'>";
                echo "<img src='" . $row['image_path'] . "' style='max-width: 100%; height: auto;'><br>";
                echo "<button onclick=\"confirmDelete('" . $row['id'] . "', '" . $row['image_path'] . "')\">حذف</button>";
                echo "<a href='" . $row['image_path'] . "' download><button>تحميل</button></a>";
                echo "</div>";
            }
        } else {
            echo "لا توجد صور بعد";
        }

        $conn->close();
        ?>
    </div>

    <script>
        function confirmDelete(imageId, imagePath) {
            if (confirm('هل أنت متأكد أنك تريد حذف هذه الصورة؟')) {
                // استخدام AJAX لحذف الصورة
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'view_images.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        // إزالة الصورة من الصفحة عند نجاح الحذف
                        const imageDiv = document.querySelector(`[data-id='${imageId}']`);
                        if (imageDiv) {
                            imageDiv.remove();
                        }
                    }
                };
                xhr.send('delete=true&id=' + imageId + '&path=' + encodeURIComponent(imagePath));
            }
        }
    </script>
</body>
</html>
