<?php
/**
 * Proj. phpdotenv-generator.php
 *
 * @author Yarco Wang <yarco.wang@gmail.com>
 * @since 7/6/17 10:31 PM
 */

namespace Yarco\PHPDotEnvGenerator;

use Composer\Script\Event;

class ScriptHandler
{
    public static function generate(Event $event)
    {
        $extra = $event->getComposer()->getPackage()->getExtra();

        if (!isset($extra['phpdotenv-parameters'])) {
            // ignore
            return;
        }

        $config = $extra['phpdotenv-parameters'];
        if (empty($config)) return;

        $default = ['warning' => false];
        $config = array_merge($default, $config);

        $generator = new Generator($config);
        $generator->execute();
    }
}