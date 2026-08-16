<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Смена пароля AFFiNE</title>
</head>
<body style="font-family: Arial, sans-serif; color: #111827; line-height: 1.6;">
    <h2 style="margin-bottom: 16px;">Смена пароля AFFiNE</h2>

    <p>Здравствуйте, {{ $recipientName }}.</p>

    <p>
        Для изменения пароля в AFFiNE откройте ссылку ниже:
    </p>

    <p>
        <a href="{{ $resetUrl }}">{{ $resetUrl }}</a>
    </p>

    <p>
        Сервис: <a href="{{ $baseUrl }}">{{ $baseUrl }}</a>
    </p>

    <p>
        Если вы не запрашивали смену пароля, просто проигнорируйте это письмо.
    </p>
</body>
</html>
