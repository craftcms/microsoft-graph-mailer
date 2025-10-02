<?php

namespace craft\microsoftgraphmailer;

use Craft;
use craft\base\Plugin as BasePlugin;
use craft\events\RegisterComponentTypesEvent;
use craft\helpers\MailerHelper;
use craft\microsoftgraphmailer\mail\transportadapters\Adapter;
use yii\base\Event;

/**
 * Microsoft Graph Mailer plugin
 *
 * @method static Plugin getInstance()
 * @author Pixel & Tonic <support@craftcms.com>
 * @copyright Pixel & Tonic
 * @license MIT
 */
class Plugin extends BasePlugin
{
    public function init(): void
    {
        parent::init();

        // Defer most setup tasks until Craft is fully initialized
        Craft::$app->onInit(function() {
            $this->attachEventHandlers();
        });
    }

    protected function settingsHtml(): ?string
    {
        return Craft::$app->view->renderTemplate('microsoft-graph-mailer/_settings.twig', [
            'plugin' => $this,
            'settings' => $this->getSettings(),
        ]);
    }

    private function attachEventHandlers(): void
    {
        // Register event handlers here ...
        // (see https://craftcms.com/docs/5.x/extend/events.html to get started)
        Event::on(
            MailerHelper::class,
            MailerHelper::EVENT_REGISTER_MAILER_TRANSPORTS,
            function(RegisterComponentTypesEvent $event) {
                $event->types[] = Adapter::class;
            }
        );
    }
}
