<?php
/**
 * Proj. phpdotenv-generator.php
 *
 * @author Yarco Wang <yarco.wang@gmail.com>
 * @since 7/6/17 10:41 PM
 */

namespace Yarco\PHPDotEnvGenerator;


class Util
{
    /**
     * Guess dist filename by supplying original and dist info.
     *
     * Example:
     *   Util::guessDistFilename('/home/yarco/.env.ini', null) will result '/home/yarco/.env'
     *   Util::guessDistFilename('/home/yarco/.env.ini', '/var/www') will result '/var/www/.env'
     *
     * @param string $original
     * @param string|null $dist
     * @param array $info
     * @return string
     * @throws InvalidArgumentException
     */
    public static function guessDistFilename(string $original, string $dist = null, & $info = []): string
    {
        $info = pathinfo($original);

        if ($info['extension'] !== 'ini') {
            throw new InvalidArgumentException("original file must be with ini extension, got ${info['extension']}");
        }

        if (!$dist) {
            $dist = $info['dirname'] . DIRECTORY_SEPARATOR . $info['filename'];
        } else if (file_exists($dist) && is_dir($dist)) {
            $dist = $dist . DIRECTORY_SEPARATOR . $info['filename'];
        }

        return $dist;
    }

    /**
     * Backup config file
     *
     * @param string $file
     * @return bool
     * @throws InvalidArgumentException
     */
    public static function backupFile(string $file): bool
    {
        if (!file_exists($file)) {
            throw new InvalidArgumentException("invalid file $file, can not backup");
        }
        $backup_filename = $file . '~';
        return copy($file, $backup_filename);
    }

    /**
     * Revert backup file
     *
     * @param string $file ORIGINAL FILENAME, not the BACKUP FILENAME
     * @return bool
     * @throws InvalidArgumentException
     */
    public static function revertFile(string $file): bool
    {
        $backup_filename = $file . '~';
        if (!file_exists($backup_filename)) {
            throw new InvalidArgumentException("invalid backup file $backup_filename, can not revert");
        }
        return copy($backup_filename, $file);
    }
}