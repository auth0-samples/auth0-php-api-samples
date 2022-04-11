<?php

declare(strict_types=1);

namespace Auth0\Quickstart;

use Auth0\Quickstart\Contract\QuickstartExample;
use Auth0\SDK\Auth0;
use Auth0\SDK\Configuration\SdkConfiguration;
use Auth0\SDK\Exception\InvalidTokenException;

final class Application
{
    /**
     * An instance of our SDK's Auth0 configuration, so we could potentially make changes later.
     */
    private SdkConfiguration $configuration;

    /**
     * An instance of the Auth0 SDK.
     */
    private Auth0 $sdk;

    /**
     * An instance of our application's template rendering helper class, for sending responses.
     */
    private ApplicationTemplates $templates;

    /**
     * An instance of our application's error handling class, for gracefully reporting exceptions.
     */
    private ApplicationErrorHandler $errorHandler;

    /**
     * An instance of our application's router class, for handling end-user requests to URIs.
     */
    private ApplicationRouter $router;

    /**
     * An instance of a QuickstartExample class, specified from the AUTH0_USE_EXAMPLE env.
     */
    private ?QuickstartExample $example = null;

    /**
     * An array of hooks with callback functions for examples to override default behavior.
     *
     * @var array<string, callable>
     */
    private array $exampleHooks = [];

    /**
     * Setup our Quickstart application.
     *
     * @param array<string,mixed> $env Auth0 configuration imported from .env file.
     */
    public function __construct(
        array $env
    ) {
        // Configure the SDK using our .env configuration.
        $this->setupAuth0($env);

        // Setup our template engine, for sending responses back to the browser.
        $this->templates = new ApplicationTemplates($this);
        $this->errorHandler = new ApplicationErrorHandler($this);
        $this->router = new ApplicationRouter($this);
        $this->example = null;
    }

    /**
     * Configure the Auth0 SDK using the .env configuration.
     *
     * @param array<string,mixed> $env Auth0 configuration imported from .env file.
     */
    public function setupAuth0(
        array $env
    ): void {
        // Build our SdkConfiguration.
        $this->configuration = new SdkConfiguration([
            'domain' => $env['AUTH0_DOMAIN'] ?? null,
            'clientId' => $env['AUTH0_CLIENT_ID'] ?? null,
            'clientSecret' => $env['AUTH0_CLIENT_SECRET'] ?? null,
            'audience' => ($env['AUTH0_AUDIENCE'] ?? null) !== null ? [trim($env['AUTH0_AUDIENCE'])] : null,
            'organization' => ($env['AUTH0_ORGANIZATION'] ?? null) !== null ? [trim($env['AUTH0_ORGANIZATION'])] : null,
        ]);

        // Setup the Auth0 SDK.
        $this->sdk = new Auth0($this->configuration);
    }

    /**
     * "Run" our application, responding to end-user requests.
     */
    public function run(): void
    {
        // Intercept exceptions to gracefully report them.
        $this->errorHandler->hook();

        // Handle incoming requests through the router.
        $this->router->run();
    }

    /**
     * Return our instance of Auth0.
     */
    public function &getSdk(): Auth0
    {
        return $this->sdk;
    }

    /**
     * Return our instance of SdkConfiguration.
     */
    public function &getConfiguration(): SdkConfiguration
    {
        return $this->configuration;
    }

    /**
     * Return our instance of ApplicationTemplates.
     */
    public function &getTemplate(): ApplicationTemplates
    {
        return $this->templates;
    }

    /**
     * Return our instance of ApplicationErrorHandler.
     */
    public function &getErrorHandler(): ApplicationErrorHandler
    {
        return $this->errorHandler;
    }

    /**
     * Return our instance of ApplicationRouter.
     */
    public function &getRouter(): ApplicationRouter
    {
        return $this->router;
    }

    /**
     * Called from the ApplicationRouter when end user loads '/'.
     */
    public function onIndexRoute(
        ApplicationRouter $router
    ): void {
        // Send response to browser.
        $this->templates->render('spa', [
            'config' => $this->getConfiguration(),
            'router' => $router,
        ]);
    }

    /**
     * Called from the ApplicationRouter when end user loads '/api'.
     */
    public function onApiRoute(
        ApplicationRouter $router
    ): void {
        $session = $this->getToken();

        // Send response to browser.
        $this->templates->render('logged-' . ($session === null ? 'out' : 'in'), [
            'session' => $session,
            'router' => $router,
        ]);
    }

    /**
     * Called from the ApplicationRouter when end user loads an unknown route.
     */
    public function onError404(
        ApplicationRouter $router
    ): void {
        $router->setHttpStatus(404);
    }

    /**
     * Process a token from the request query or via request headers.
     *
     * @throws InvalidTokenException When an invalid token is provided.
     */
    private function getToken(): ?\Auth0\SDK\Contract\TokenInterface
    {
        // Look for token in ?token=... param, followed by an `HTTP_AUTHORIZATION` or `Authorization` header.
        // @phpstan-ignore-next-line
        $token = $_GET['token'] ?? $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['Authorization'] ?? null;

        // If no token was present, abort processing.
        if ($token === null) {
            return null;
        }

        // Trim whitespace from token string.
        $token = trim($token);

        // Remove the 'Bearer ' prefix, if present, in the event we're using an Authorization header that's using it.
        if (substr($token, 0, 7) === 'Bearer ') {
            $token = substr($token, 7);
        }

        return $this->getSdk()->decode($token, null, null, null, null, null, null, \Auth0\SDK\Token::TYPE_TOKEN);
    }
}
