<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class EnvironmentService
{
    /**
     * Update or add a key-value pair in the .env file
     *
     * @param string $key The environment variable name (e.g., 'VAPID_PUBLIC_KEY')
     * @param string|null $value The value to set (null to remove the key)
     * @return array Response with success status and message
     */
    public static function updateEnvFile($key, $value = null)
    {
        try {
            // Get the .env file path
            $envPath = self::getEnvPath();

            // Validate file exists
            if (!file_exists($envPath)) {
                throw new \Exception(".env file not found at: {$envPath}");
            }

            // Check if file is readable
            if (!is_readable($envPath)) {
                throw new \Exception(".env file is not readable");
            }

            // Check if file is writable
            if (!is_writable($envPath)) {
                throw new \Exception(".env file is not writable. Check file permissions.");
            }

            // Read current content
            $content = file_get_contents($envPath);

            if ($content === false) {
                throw new \Exception("Failed to read .env file");
            }

            // Create backup before modifying
            self::createBackup($envPath, $content);

            // Update the content
            $newContent = self::updateEnvContent($content, $key, $value);

            // Write updated content back to file
            $writeResult = file_put_contents($envPath, $newContent);

            if ($writeResult === false) {
                throw new \Exception("Failed to write to .env file");
            }

            // Log the successful update
            Log::info("Environment variable updated", [
                'key' => $key,
                'timestamp' => now(),
            ]);

            return [
                'success' => true,
                'message' => "✓ {$key} updated in .env file successfully",
                'key' => $key,
            ];

        } catch (\Exception $e) {
            Log::error("Failed to update .env file", [
                'key' => $key,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => "Failed to update .env: " . $e->getMessage(),
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get the path to the .env file
     *
     * @return string
     */
    private static function getEnvPath()
    {
        return base_path('.env');
    }

    /**
     * Create a backup of the .env file
     *
     * @param string $filePath
     * @param string $content
     * @return void
     */
    private static function createBackup($filePath, $content)
    {
        try {
            $backupPath = $filePath . '.backup';

            // Only create backup if this is a new modification
            // (we keep only one backup to avoid clutter)
            if (!file_exists($backupPath)) {
                file_put_contents($backupPath, $content);
                Log::info("Created backup of .env file", ['backup_path' => $backupPath]);
            }
        } catch (\Exception $e) {
            Log::warning("Failed to create .env backup", ['error' => $e->getMessage()]);
            // Don't throw - backup failure shouldn't prevent the update
        }
    }

    /**
     * Update the content with new key-value pair
     * If key exists, update its value
     * If key doesn't exist, append it to the file
     *
     * @param string $content The original .env content
     * @param string $key The environment variable name
     * @param string|null $value The value to set
     * @return string Updated content
     */
    private static function updateEnvContent($content, $key, $value = null)
    {
        // If value is null, remove the key
        if ($value === null) {
            return self::removeEnvKey($content, $key);
        }

        // Parse the value to handle special characters
        $parsedValue = self::parseEnvValue($value);

        // Pattern to match the key (with or without existing value)
        // This pattern handles:
        // - KEY=value
        // - KEY="value"
        // - KEY='value'
        // - KEY= (empty)
        $pattern = "/^" . preg_quote($key) . "=.*$/m";

        $lines = explode("\n", $content);
        $found = false;

        foreach ($lines as &$line) {
            if (preg_match("/^" . preg_quote($key) . "=/", $line)) {
                $line = "{$key}={$parsedValue}";
                $found = true;
                break;
            }
        }

        // If key not found, append it to the end
        if (!$found) {
            // Remove trailing empty lines
            $lines = array_filter($lines, function ($line) {
                return trim($line) !== '';
            });

            $lines[] = "{$key}={$parsedValue}";
        }

        return implode("\n", $lines) . "\n";
    }

    /**
     * Remove a key from the .env content
     *
     * @param string $content
     * @param string $key
     * @return string
     */
    private static function removeEnvKey($content, $key)
    {
        $lines = explode("\n", $content);
        $filtered = [];

        foreach ($lines as $line) {
            if (!preg_match("/^" . preg_quote($key) . "=/", $line)) {
                $filtered[] = $line;
            }
        }

        return implode("\n", $filtered);
    }

    /**
     * Parse environment variable value
     * Handles quotes and special characters
     *
     * @param string $value
     * @return string
     */
    private static function parseEnvValue($value)
    {
        // If value contains spaces or special characters, quote it
        if (preg_match('/\s|["\'`\\\\#]/', $value)) {
            // Escape existing quotes and backslashes
            $value = str_replace('\\', '\\\\', $value);
            $value = str_replace('"', '\\"', $value);
            return '"' . $value . '"';
        }

        return $value;
    }

    /**
     * Validate that .env file permissions are correct
     *
     * @return array
     */
    public static function validatePermissions()
    {
        $envPath = self::getEnvPath();

        return [
            'exists' => file_exists($envPath),
            'readable' => is_readable($envPath),
            'writable' => is_writable($envPath),
            'path' => $envPath,
            'permissions' => decoct(fileperms($envPath) & 0777),
        ];
    }
}
