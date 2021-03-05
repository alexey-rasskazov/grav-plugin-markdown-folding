<?php
namespace Grav\Plugin;

use \Grav\Common\Grav;
use \Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;

/**
 * Class MarkdownFoldingPlugin
 * @package Grav\Plugin
 */
class MarkdownFoldingPlugin extends Plugin
{
    /**
     * @return array
     *
     * The getSubscribedEvents() gives the core a list of events
     *     that the plugin wants to listen to. The key of each
     *     array section is the event that the plugin listens to
     *     and the value (in the form of an array) contains the
     *     callable (or function) as well as the priority. The
     *     higher the number the higher the priority.
     */
    public static function getSubscribedEvents()
    {
        return [
            'onMarkdownInitialized' => ['onMarkdownInitialized', 0],
            'onTwigSiteVariables'   => ['onTwigSiteVariables', 0]
        ];
    }

    public function onMarkdownInitialized(Event $event)
    {
        $markdown = $event['markdown'];
    
        $markdown->addBlockType('!', 'Collapsible', true, false);

        $markdown->blockCollapsible = function($Line)
        {
            if (preg_match('/^!>\s?(\[(\w*)\]?)?\s?(\(((\w\-?\s?)*)\))?\s*(.*)$/', $Line['text'], $matches))
            {
                $tag = $matches[2] ? $matches[2] : 'span';
                $class = $matches[4] ? ' ' . $matches[4] : '';
                $title = $matches[6];

                $Block = array(
                    'name' => 'opener',
                    'markup' => '<div class="folding-block"><'. $tag .' class="collapse-switch'. $class .'" data-collapsed="true">'.
                        $title .'</'. $tag .'><div class="collapsible">',
                );
                return $Block;
            }
            if (preg_match('/^!@(.*)$/', $Line['text'], $matches))
            {
                $Block = array('name' => 'closer', 'markup' => '</div></div>');
                return $Block;
            }
        };
        $markdown->blockCollapsibleContinue = function($Line, array $Block) 
        {
            if ( isset( $Block['interrupted'] ) )
            {
                return;
            }
            if (preg_match('/^!@(.*)/', $Line['text'], $matches))
            {
                $Block = array('name' => 'closer', 'markup' => '</div></div>');
                $Block['closed'] = true;
                return $Block;
            }
        };
    }

    public function onTwigSiteVariables()
    {
        $this->grav['assets']->add('plugin://markdown-folding/assets/folding.css');
        $this->grav['assets']->add('plugin://markdown-folding/js/folding.js');
    }
}
