<?php
/**
 * @copyright Copyright (c) Pixel & Tonic, Inc.
 */

namespace craft\microsoftgraphmailer\mail\transportadapters;

use Craft;
use craft\behaviors\EnvAttributeParserBehavior;
use craft\helpers\App;
use craft\mail\transportadapters\BaseTransportAdapter;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Component\Mailer\Bridge\MicrosoftGraph\TokenManager;
use Symfony\Component\Mailer\Bridge\MicrosoftGraph\Transport\MicrosoftGraphApiTransport;
use Symfony\Component\Mailer\Transport\AbstractTransport;

/**
 * Adapter implements a Microsoft Graph Mailer transport adapter into Craft’s mailer.
 *
 * @property mixed $settingsHtml
 * @author Pixel & Tonic, Inc. <support@pixelandtonic.com>
 * @since 3.0
 */
class Adapter extends BaseTransportAdapter
{
    public const DEFAULT_GRAPH_ENDPOINT = 'graph.microsoft.com';
    public const DEFAULT_AUTH_ENDPOINT = 'login.microsoftonline.com';

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return 'Microsoft Graph Mailer';
    }

    /**
     * @var string The client app id
     */
    public string $clientId = '';

    /**
     * @var string The client app secret
     */
    public string $clientSecret = '';

    /**
     * @var string The tenant id
     */
    public string $tenantId = '';

    /**
     * @var string The graph endpoint
     */
    public string $graphEndpoint = '';

    /**
     * @var string The auth endpoint
     */
    public string $authEndpoint = '';

    /**
     * @var bool Whether to save the messages to sent items.
     */
    public bool $save = false;

    /**
     * @inheritdoc
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['parser'] = [
            'class' => EnvAttributeParserBehavior::class,
            'attributes' => [
                'clientId',
                'clientSecret',
                'tenantId',
                'graphEndpoint',
                'authEndpoint',
                'save',
            ],
        ];
        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'clientId' => Craft::t('microsoft-graph-mailer', 'Client ID'),
            'clientSecret' => Craft::t('microsoft-graph-mailer', 'Client Secret'),
            'tenantId' => Craft::t('microsoft-graph-mailer', 'Tenant ID'),
            'graphEndpoint' => Craft::t('microsoft-graph-mailer', 'Graph Endpoint'),
            'authEndpoint' => Craft::t('microsoft-graph-mailer', 'Auth Endpoint'),
            'save' => Craft::t('microsoft-graph-mailer', 'Save to sent items'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function defineRules(): array
    {
        return [
            [['clientId', 'clientSecret', 'tenantId'], 'required'],
            [['clientId', 'clientSecret', 'tenantId', 'graphEndpoint', 'authEndpoint'], 'string'],
            [['save'], 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml(): ?string
    {
        return Craft::$app->getView()->renderTemplate('microsoft-graph-mailer/_settings', [
            'adapter' => $this,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function defineTransport(): array|AbstractTransport
    {
        $tokenManager = new TokenManager(
            $this->getGraphEndpoint(),
            $this->getAuthEndpoint(),
            App::parseEnv($this->tenantId),
            App::parseEnv($this->clientId),
            App::parseEnv($this->clientSecret),
            new CurlHttpClient(),
        );

        return new MicrosoftGraphApiTransport($this->graphEndpoint, $tokenManager, !$this->save);
    }

    private function getGraphEndpoint(): string
    {
        $endpoint = null;
        if (!empty($this->graphEndpoint)) {
            $endpoint = str_replace(['https://', 'http://'], '', App::parseEnv($this->graphEndpoint));
        }

        if (empty($endpoint)) {
            $endpoint = self::DEFAULT_GRAPH_ENDPOINT;
        }

        return $endpoint;
    }

    private function getAuthEndpoint(): string
    {
        $endpoint = null;
        if (!empty($this->authEndpoint)) {
            $endpoint = str_replace(['https://', 'http://'], '', App::parseEnv($this->authEndpoint));
        }

        if (empty($endpoint)) {
            $endpoint = self::DEFAULT_AUTH_ENDPOINT;
        }

        return $endpoint;
    }
}
