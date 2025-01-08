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

// استلام الصورة وحفظها
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $image = $data['image'];

    // إزالة الجزء غير المرغوب فيه من البيانات
    $image = str_replace('data:image/png;base64,', '', $image);
    $image = str_replace(' ', '+', $image);
    $imageData = base64_decode($image);

    // التأكد من وجود مجلد uploads وإنشاؤه إذا لم يكن موجودًا
    if (!file_exists('uploads')) {
        mkdir('uploads', 0777, true);
    }

    $fileName = 'uploads/image_' . time() . '.png';
    file_put_contents($fileName, $imageData);

    $sql = "INSERT INTO images (image_path) VALUES ('$fileName')";
    if ($conn->query($sql) === TRUE) {
        echo "تم رفع الصورة بنجاح";
    } else {
        echo "خطأ: " . $sql . "<br>" . $conn->error;
    }

    exit();
}
?>
