<?php
$host = 'localhost';
$user = 'u82410';  
$pass = '4348747';   
$dbname = 'u82410';   

// Подключаемся к БД
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Валидация
    $fio = trim($_POST['fio'] ?? '');
    if (empty($fio)) $errors[] = "Заполните ФИО";
    elseif (strlen($fio) > 150) $errors[] = "ФИО не длиннее 150 символов";
    elseif (!preg_match('/^[a-zA-Zа-яА-ЯёЁ\s]+$/u', $fio)) $errors[] = "ФИО только буквы и пробелы";
    
    $phone = trim($_POST['phone'] ?? '');
    if (empty($phone)) $errors[] = "Заполните телефон";
    
    $email = trim($_POST['email'] ?? '');
    if (empty($email)) $errors[] = "Заполните email";
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Неверный формат email";
    
    $birthdate = $_POST['birthdate'] ?? '';
    if (empty($birthdate)) $errors[] = "Заполните дату рождения";
    
    $gender = $_POST['gender'] ?? '';
    if (!in_array($gender, ['male', 'female'])) $errors[] = "Выберите пол";
    
    $languages = $_POST['languages'] ?? [];
    $valid = ['Pascal','C','C++','JavaScript','PHP','Python','Java','Haskell','Clojure','Prolog','Scala','Go'];
    if (empty($languages)) $errors[] = "Выберите хотя бы один язык";
    foreach ($languages as $lang) {
        if (!in_array($lang, $valid)) $errors[] = "Недопустимый язык: $lang";
    }
    
    $bio = trim($_POST['bio'] ?? '');
    if (strlen($bio) > 5000) $errors[] = "Биография не длиннее 5000 символов";
    
    $agree = isset($_POST['agree']) ? 1 : 0;
    if (!$agree) $errors[] = "Нужно согласиться с контрактом";
    
    // Если нет ошибок - сохраняем
    if (empty($errors)) {
        try {
            $pdo->beginTransaction();
            
            // Вставляем в таблицу applications
            $stmt = $pdo->prepare("INSERT INTO applications 
                (full_name, phone, email, birth_date, gender, bio, contract_agreed) 
                VALUES (?, ?, ?, ?, ?, ?, ?)");
            
            $stmt->execute([$fio, $phone, $email, $birthdate, $gender, $bio, $agree]);
            
            $app_id = $pdo->lastInsertId();
            
            // Вставляем языки
            $stmt = $pdo->prepare("INSERT INTO application_languages (application_id, language_id) VALUES (?, ?)");
            
            // Получаем ID языков из БД
            $placeholders = implode(',', array_fill(0, count($languages), '?'));
            $lang_stmt = $pdo->prepare("SELECT id, name FROM programming_languages WHERE name IN ($placeholders)");
            $lang_stmt->execute($languages);
            $lang_map = [];
            while ($row = $lang_stmt->fetch()) {
                $lang_map[$row['name']] = $row['id'];
            }
            
            foreach ($languages as $lang) {
                if (isset($lang_map[$lang])) {
                    $stmt->execute([$app_id, $lang_map[$lang]]);
                }
            }
            
            $pdo->commit();
            
            // Успех - редирект на форму с сообщением
            header('Location: form.html?success=1');
            exit();
            
        } catch(PDOException $e) {
            $pdo->rollBack();
            $errors[] = "Ошибка БД: " . $e->getMessage();
        }
    }
}

// Если есть ошибки - показываем их
if (!empty($errors)) {
    echo "<!DOCTYPE html>";
    echo "<html><head><link rel='stylesheet' href='style.css'><title>Ошибки</title></head><body>";
    echo "<div class='container' style='max-width:500px;margin:20px auto;'>";
    echo "<h1>❌ Ошибки</h1>";
    echo "<ul style='color:red;margin-bottom:20px;'>";
    foreach ($errors as $err) {
        echo "<li>$err</li>";
    }
    echo "</ul>";
    echo "<a href='form.html' style='display:inline-block;padding:8px 16px;background:#4CAF50;color:white;text-decoration:none;border-radius:4px;'>← Вернуться</a>";
    echo "</div></body></html>";
}
?>