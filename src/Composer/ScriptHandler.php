<?php
/**
 * File part of the GMaissa Composer Parameter Handler package
 *
 * @category  GMaissa
 * @package   GMaissa.ComposerParameterHandler
 * @author    Guillaume Maïssa <prod.g@maissa.fr>
 * @copyright 2016 Guillaume Maïssa
 * @license   https://opensource.org/licenses/MIT MIT
 */
namespace GMaissa\ComposerParameterHandler\Composer;

use Sensio\Bundle\DistributionBundle\Composer\ScriptHandler as DistributionBundleScriptHandler;
use Composer\Script\Event;

/**
 * Parameter Handler Composer Script class
 *
 * @category  GMaissa
 * @package   GMaissa.ComposerParameterHandler
 * @author    Guillaume Maïssa <prod.g@maissa.fr>
 * @copyright 2016 Guillaume Maïssa
 */
class ScriptHandler extends DistributionBundleScriptHandler
{
    /**
     * Remove files generated with Incenteev ParameterHandler package
     *
     * @param Event $event composer command even
     */
    public static function removeHandledFiles(Event $event)
    {
        $extras = $event->getComposer()->getPackage()->getExtra();

        if (!isset($extras['incenteev-parameters'])) {
            throw new \InvalidArgumentException(
                'The parameter handler needs to be configured through the extra.incenteev-parameters setting.'
            );
        }

        $configs = $extras['incenteev-parameters'];

        if (!is_array($configs)) {
            throw new \InvalidArgumentException(
                'The extra.incenteev-parameters setting must be an array or a configuration object.'
            );
        }

        if (array_keys($configs) !== range(0, count($configs) - 1)) {
            $configs = array($configs);
        }

        foreach ($configs as $config) {
            if (!is_array($config)) {
                throw new \InvalidArgumentException(
                    'The extra.incenteev-parameters setting must be an array of configuration objects.'
                );
            }
            if (empty($config['file'])) {
                throw new \InvalidArgumentException(
                    'The extra.incenteev-parameters.file setting is required to use this script handler.'
                );
            }

            $file = $config['file'];
            $exists = is_file($file);

            if ($exists) {
                $event->getIO()->write(sprintf('<info>Removing the "%s" file</info>', $file));
                unlink($file);
            }
        }
    }
}
