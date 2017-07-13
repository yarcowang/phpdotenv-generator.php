<?php
/**
 * Proj. phpdotenv-generator.php
 *
 * @author Yarco Wang <yarco.wang@gmail.com>
 * @since 7/9/17 11:42 PM
 */

use PHPUnit\Framework\TestCase;

class GeneratorTest extends TestCase
{
    private $source;

    public function setUp()
    {
        parent::setUp();

        $this->source = __DIR__ . '/Fixtures/tpl.ini';
    }

    public function testGenerate()
    {
        putenv('PHPDOTENV=default');
        $generator = new \Yarco\PHPDotEnvGenerator\Generator([]);
        $generator->generate($this->source, '/var/tmp/1.env');
        $data = parse_ini_file('/var/tmp/1.env');
        $this->assertEquals('local', $data['APP_ENV']);
        $this->assertEmpty($data['APP_KEY']);

        putenv('PHPDOTENV=dev');
        $generator = new \Yarco\PHPDotEnvGenerator\Generator([]);
        $generator->generate($this->source, '/var/tmp/1.env');
        $data = parse_ini_file('/var/tmp/1.env');
        $this->assertEquals('dev', $data['APP_KEY']);
    }
}
