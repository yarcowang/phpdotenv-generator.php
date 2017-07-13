<?php
/**
 * Proj. phpdotenv-generator.php
 *
 * @author Yarco Wang <yarco.wang@gmail.com>
 * @since 7/6/17 11:03 PM
 */

use PHPUnit\Framework\TestCase;
use Yarco\PHPDotEnvGenerator\Util;

class UtilTest extends TestCase
{
    const FILE = '/var/tmp/foo';
    const BACKUPFILE = '/var/tmp/foo~';

    public function testGuessDistFilename()
    {
        $original = '/foo/bar/.env.ini';
        $expect = '/foo/bar/.env';
        $this->assertEquals($expect, Util::guessDistFilename($original));

        $original = '/foo/bar/.env.ini';
        $expect = '/foo/bar/.env';
        $this->assertEquals($expect, Util::guessDistFilename($original, ''));

        $dist = '/var/tmp';
        $expect = $dist . DIRECTORY_SEPARATOR . '.env';
        $this->assertEquals($expect, Util::guessDistFilename($original, $dist));

        $dist = '/foo/bar';
        $expect = $dist;
        $this->assertEquals($expect, Util::guessDistFilename($original, $dist));
    }

    public function testBackupFile()
    {
        touch(self::FILE);
        $this->assertTrue(Util::backupFile(self::FILE));
        $this->assertFileExists(self::BACKUPFILE);
        unlink(self::FILE);
    }

    /**
     * @depends testBackupFile
     */
    public function testRevertBackupFile()
    {
        $this->assertTrue(Util::revertFile(self::FILE));
        $this->assertFileExists(self::FILE);
        unlink(self::FILE);
        unlink(self::BACKUPFILE);
    }
}
