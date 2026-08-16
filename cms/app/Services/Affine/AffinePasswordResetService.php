<?php

namespace App\Services\Affine;

use Illuminate\Support\Facades\Mail;
use PDO;
use RuntimeException;

class AffinePasswordResetService
{
    private const TOKEN_TYPE_CHANGE_PASSWORD = 3;
    private const NONCE_LENGTH = 12;
    private const AUTH_TAG_LENGTH = 12;

    public function sendResetLink(string $email): array
    {
        $user = $this->findUserByEmail($email);

        if ($user === null) {
            throw new RuntimeException("AFFiNE user with email [{$email}] was not found.");
        }

        $resetUrl = $this->createResetUrl($user['id']);

        Mail::to($email)->send(new \App\Mail\AffinePasswordResetMail(
            recipientName: $user['name'] ?: $email,
            resetUrl: $resetUrl,
            baseUrl: $this->baseUrl(),
        ));

        return [
            'email' => $email,
            'name' => $user['name'] ?: $email,
            'reset_url' => $resetUrl,
        ];
    }

    public function createResetUrl(string $userId): string
    {
        $plaintextToken = $this->storeResetToken($userId);
        $encryptedToken = $this->encryptToken($plaintextToken);
        $callbackPath = '/' . ltrim((string) config('affine.reset_callback_path', '/auth/changePassword'), '/');

        return $this->baseUrl() . $callbackPath . '?' . http_build_query([
            'userId' => $userId,
            'token' => $encryptedToken,
        ]);
    }

    private function findUserByEmail(string $email): ?array
    {
        $statement = $this->pdo()->prepare('select id, name, email from users where lower(email) = lower(:email) limit 1');
        $statement->execute(['email' => $email]);

        $user = $statement->fetch(PDO::FETCH_ASSOC);

        return is_array($user) ? $user : null;
    }

    private function storeResetToken(string $userId): string
    {
        $token = $this->uuidV4();
        $ttl = (int) config('affine.reset_ttl', 1800);
        $expiresAt = gmdate('Y-m-d H:i:sP', time() + $ttl);

        $statement = $this->pdo()->prepare(
            'insert into verification_tokens (token, type, credential, "expiresAt") values (:token, :type, :credential, :expires_at)'
        );

        $statement->execute([
            'token' => $token,
            'type' => self::TOKEN_TYPE_CHANGE_PASSWORD,
            'credential' => $userId,
            'expires_at' => $expiresAt,
        ]);

        return $token;
    }

    private function encryptToken(string $plaintextToken): string
    {
        $privateKey = @file_get_contents((string) config('affine.private_key_path'));

        if ($privateKey === false || $privateKey === '') {
            throw new RuntimeException('AFFiNE private key file could not be read.');
        }

        $key = hash('sha256', $privateKey, true);
        $iv = random_bytes(self::NONCE_LENGTH);
        $tag = '';
        $ciphertext = openssl_encrypt(
            $plaintextToken,
            'aes-256-gcm',
            $key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag,
            '',
            self::AUTH_TAG_LENGTH
        );

        if ($ciphertext === false) {
            throw new RuntimeException('Could not encrypt AFFiNE reset token.');
        }

        return base64_encode($iv . $tag . $ciphertext);
    }

    private function pdo(): PDO
    {
        static $pdo = null;

        if ($pdo instanceof PDO) {
            return $pdo;
        }

        $env = $this->readAffineEnv();
        $dbName = $env['DB_DATABASE'] ?? 'affine';
        $dbUser = $env['DB_USERNAME'] ?? null;
        $dbPassword = $env['DB_PASSWORD'] ?? null;

        if ($dbUser === null || $dbPassword === null) {
            throw new RuntimeException('AFFiNE database credentials are missing in the AFFiNE .env file.');
        }

        $pdo = new PDO(
            sprintf(
                'pgsql:host=%s;port=%d;dbname=%s',
                (string) config('affine.db_host', '127.0.0.1'),
                (int) config('affine.db_port', 55433),
                $dbName
            ),
            $dbUser,
            $dbPassword,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );

        return $pdo;
    }

    private function readAffineEnv(): array
    {
        static $parsed = null;

        if (is_array($parsed)) {
            return $parsed;
        }

        $path = (string) config('affine.env_file');
        $contents = @file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if ($contents === false) {
            throw new RuntimeException("AFFiNE env file [{$path}] could not be read.");
        }

        $parsed = [];

        foreach ($contents as $line) {
            $line = trim($line);

            if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
                continue;
            }

            [$key, $value] = explode('=', $line, 2);
            $parsed[trim($key)] = trim($value, " \t\n\r\0\x0B\"'");
        }

        return $parsed;
    }

    private function baseUrl(): string
    {
        return rtrim((string) config('affine.base_url'), '/');
    }

    private function uuidV4(): string
    {
        $bytes = random_bytes(16);
        $bytes[6] = chr((ord($bytes[6]) & 0x0f) | 0x40);
        $bytes[8] = chr((ord($bytes[8]) & 0x3f) | 0x80);

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($bytes), 4));
    }
}
