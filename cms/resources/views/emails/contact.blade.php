<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
</head>
<body style="font-family: sans-serif; color: #1a1a2e; padding: 24px;">
    <h2 style="margin-bottom: 16px;">Новое сообщение с bozheslav.com</h2>
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="padding: 8px 0; font-weight: bold; width: 120px;">Имя:</td>
            <td style="padding: 8px 0;">{{ $senderName }}</td>
        </tr>
        <tr>
            <td style="padding: 8px 0; font-weight: bold;">Email:</td>
            <td style="padding: 8px 0;">
                <a href="mailto:{{ $senderEmail }}">{{ $senderEmail }}</a>
            </td>
        </tr>
        <tr>
            <td style="padding: 8px 0; font-weight: bold;">Тема:</td>
            <td style="padding: 8px 0;">{{ $mailSubject }}</td>
        </tr>
        <tr>
            <td style="padding: 8px 0; font-weight: bold; vertical-align: top;">Сообщение:</td>
            <td style="padding: 8px 0;">{{ $mailMessage ?? 'Не указано' }}</td>
        </tr>
    </table>
</body>
</html>
