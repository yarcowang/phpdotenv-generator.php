<?php
/**
 * Proj. phpdotenv-generator.php
 *
 * @author Yarco Wang <yarco.wang@gmail.com>
 * @since 7/8/17 11:30 PM
 */

namespace Yarco\PHPDotEnvGenerator;


class Generator
{
    protected $config;

    protected $warning;
    protected $projects;

    public function __construct($config)
    {
        $this->config = $config;

        $this->warning = $config['warning'] ?? false;
        $this->projects = isset($config['project']) ? [$config['project']] : ($config['projects'] ?? []);
    }

    public function execute()
    {
        array_walk($this->projects, [$this, 'doGenerate']);
    }

    public function doGenerate($project)
    {
        try {
            $dist = Util::guessDistFilename($project['source'], $project['dist'] ?? null);
        } catch (InvalidArgumentException $e) {
            $this->warning && syslog(LOG_NOTICE, '[phpdotenv]' . $e->getMessage());
            return;
        }

        if (file_exists($dist)) {
            if ($this->warning) {
                $new = parse_ini_file($project['source'], true, INI_SCANNER_RAW);
                $old = parse_ini_file($dist, false, INI_SCANNER_RAW);
                $rest = array_diff($old, $new['default']);
                array_walk($rest, function($v, $k) {
                    syslog(LOG_WARNING, "[phpdotenv] old setting $k=$v disappear");
                });
            }
            // backup
            Util::backupFile($dist);
        }

        $this->generate($project['source'], $dist);
    }

    public function generate($source, $dist)
    {
        $env = getenv('PHPDOTENV');
        $new = parse_ini_file($source, true, INI_SCANNER_RAW);
        $kvs = isset($new[$env]) ? array_merge($new['default'], $new[$env]) : $new['default'];
        $c = '';
        foreach($kvs as $k => $v) {
            $c .= "$k=$v\n";
        }
        file_put_contents($dist, $c);
    }
}