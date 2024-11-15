<?php declare(strict_types=1);

namespace Src\Config;

use Exception;
use Throwable;

final class Security
{
    /**
     * @return void
     */
    public static function captureErrors(): void
    {
        set_error_handler(function (?int $errno, ?string $error, ?string $file, ?int $line): bool {
            self::writeError(
                "Error: {$error} -> {$file}:{$line}"
            );

            return true;
        });

        set_exception_handler(function (Throwable $e): bool {
            self::writeError(
                "Exception: {$e->getMessage()} -> {$e->getFile()}:{$e->getLine()}"
            );

            return true;
        });
    }

    /**
     * @param string $message
     * @return void
     */
    private static function writeError(string $message): void
    {
        try {
            $dateNow = date("Y-m-d H:i:s");
            $filename = AppConfig::BASE_FOLDER . "/errors.log";
            $log = file_exists($filename) ? file_get_contents($filename) : "";
            $stream = null;

            if (!str_contains($log, $message)) {
                $log .= "{$dateNow} {$message}" . PHP_EOL;

                $stream = fopen($filename, "c");

                if (flock($stream, LOCK_EX)) {
                    fwrite($stream, $log);
                    flock($stream, LOCK_UN);
                }
            }
        } catch (Throwable $th) {
            error_log("Failed write to log: {$th->getMessage()}");
        } finally {
            if (!is_null($stream)) {
                fclose($stream);
            }
        }
    }

    /**
     * Encrypts data using OpenSSL.
     *
     * @param string $data
     * @return string
     * @throws Exception
     */
    public static function encrypt(string &$data): string
    {
        try {
            $cipher = Env::get("SECRET_CIPHER");
            $key = Env::get("SECRET_KEY");
            $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));

            $encrypted = openssl_encrypt($data, $cipher, $key, 0, $iv);
            if ($encrypted === false) {
                throw new Exception("Encryption failed.");
            }

            $data = base64_encode($encrypted . "::" . $iv);
            return $data;
        } catch (Throwable $th) {
            throw new Exception("The data couldn't be encrypted: {$th->getMessage()}");
        }
    }

    /**
     * Decrypts data using OpenSSL.
     *
     * @param string $data
     * @return string
     * @throws Exception
     */
    public static function decrypt(string &$data): string
    {
        try {
            $cipher = Env::get("SECRET_CIPHER");
            $key = Env::get("SECRET_KEY");

            // Decode and separate encrypted data and IV
            $decoded = base64_decode($data);
            if ($decoded === false)
                throw new Exception("Base64 decoding failed.");


            @[$encrypted, $iv] = explode("::", $decoded, 2);
            if (!$iv)
                throw new Exception("IV not found in encrypted data.");

            $decrypted = openssl_decrypt($encrypted, $cipher, $key, 0, $iv);
            if ($decrypted === false)
                throw new Exception("Decryption failed.");

            $data = $decrypted;
            return $data;
        } catch (Throwable $th) {
            throw new Exception("The data couldn't be decrypted: {$th->getMessage()}");
        }
    }
}