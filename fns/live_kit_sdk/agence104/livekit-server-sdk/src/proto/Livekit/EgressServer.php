<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT! (protoc-gen-twirp_php 0.8.1)
# source: livekit_egress.proto

declare(strict_types=1);

namespace Livekit;

use Google\Protobuf\Internal\GPBDecodeException;
use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Twirp\BaseServerHooks;
use Twirp\Context;
use Twirp\ErrorCode;
use Twirp\ServerHooks;

/**
 * @see Egress
 *
 * Generated from protobuf service <code>livekit.Egress</code>
 */
final class EgressServer implements RequestHandlerInterface
{
    /**
     * A convenience constant that may identify URL paths.
     *
     * Should be used with caution, it only matches routes with the default "/twirp" prefix
     * and default CamelCase service and method names.
     *
     * Use EgressServer::getPathPrefix instead.
     */
    public const PATH_PREFIX = '/twirp/livekit.Egress/';

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var StreamFactoryInterface
     */
    private $streamFactory;

    /**
     * @var Egress
     */
    private $svc;

    /**
     * @var ServerHooks
     */
    private $hook;

    /**
     * @var string
     */
    private $prefix;

    public function __construct(
        Egress $svc,
        ServerHooks $hook = null,
        ResponseFactoryInterface $responseFactory = null,
        StreamFactoryInterface $streamFactory = null,
        string $prefix = '/twirp'
    ) {
        if ($hook === null) {
            $hook = new BaseServerHooks();
        }

        if ($responseFactory === null) {
            $responseFactory = Psr17FactoryDiscovery::findResponseFactory();
        }

        if ($streamFactory === null) {
            $streamFactory = Psr17FactoryDiscovery::findStreamFactory();
        }

        $this->svc = $svc;
        $this->hook = $hook;
        $this->responseFactory = $responseFactory;
        $this->streamFactory = $streamFactory;
        $this->prefix = rtrim($prefix, '/');
    }

    /**
     * Returns the base service path, in the form: "/<prefix>/<package>.<Service>/"
     * that is everything in a Twirp route except for the <Method>. This can be used for routing,
     * for example to identify the requests that are targeted to this service in a mux.
     */
    public function getPathPrefix(): string
    {
        return $this->prefix.'/livekit.Egress/';
    }

    /**
     * Handle the request and return a response.
     */
    public function handle(ServerRequestInterface $req): ResponseInterface
    {
        $ctx = $req->getAttributes();
        $ctx = Context::withPackageName($ctx, 'livekit');
        $ctx = Context::withServiceName($ctx, 'Egress');

        try {
            $ctx = $this->hook->requestReceived($ctx);
        } catch (\Throwable $e) {
            return $this->writeError($ctx, $e);
        }

        if ($req->getMethod() !== 'POST') {
            $msg = sprintf('unsupported method "%s" (only POST is allowed)', $req->getMethod());

            return $this->writeError($ctx, $this->badRouteError($msg, $req->getMethod(), $req->getUri()->getPath()));
        }

        list($prefix, $service, $method) = $this->parsePath($req->getUri()->getPath());

        if ($service != 'livekit.Egress') {
            return $this->writeError($ctx, $this->noRouteError($req));
        }

        if ($prefix != $this->prefix) {
            $msg = sprintf('invalid path prefix "%s", expected "%s", on path "%s"', $prefix, $this->prefix, $req->getUri()->getPath());

            return $this->writeError($ctx, $this->badRouteError($msg, $req->getMethod(), $req->getUri()->getPath()));
        }

        switch ($method) {
            case 'StartRoomCompositeEgress':
                return $this->handleStartRoomCompositeEgress($ctx, $req);
            case 'StartTrackCompositeEgress':
                return $this->handleStartTrackCompositeEgress($ctx, $req);
            case 'StartTrackEgress':
                return $this->handleStartTrackEgress($ctx, $req);
            case 'StartWebEgress':
                return $this->handleStartWebEgress($ctx, $req);
            case 'UpdateLayout':
                return $this->handleUpdateLayout($ctx, $req);
            case 'UpdateStream':
                return $this->handleUpdateStream($ctx, $req);
            case 'ListEgress':
                return $this->handleListEgress($ctx, $req);
            case 'StopEgress':
                return $this->handleStopEgress($ctx, $req);

            default:
                return $this->writeError($ctx, $this->noRouteError($req));
        }
    }

    private function handleStartRoomCompositeEgress(array $ctx, ServerRequestInterface $req): ResponseInterface
    {
        $header = $req->getHeaderLine('Content-Type');
        $i = strpos($header, ';');

        if ($i === false) {
            $i = strlen($header);
        }

        $respHeaders = [];
        $ctx[Context::RESPONSE_HEADER] = &$respHeaders;

        switch (trim(strtolower(substr($header, 0, $i)))) {
            case 'application/json':
                $resp = $this->handleStartRoomCompositeEgressJson($ctx, $req);
                break;

            case 'application/protobuf':
                $resp = $this->handleStartRoomCompositeEgressProtobuf($ctx, $req);
                break;

            default:
                $msg = sprintf('unexpected Content-Type: "%s"', $req->getHeaderLine('Content-Type'));

                return $this->writeError($ctx, $this->badRouteError($msg, $req->getMethod(), $req->getUri()->getPath()));
        }

        foreach ($respHeaders as $key => $value) {
            $resp = $resp->withHeader($key, $value);
        }

        return $resp;
    }

    private function handleStartRoomCompositeEgressJson(array $ctx, ServerRequestInterface $req): ResponseInterface
    {
        $ctx = Context::withMethodName($ctx, 'StartRoomCompositeEgress');

        try {
            $ctx = $this->hook->requestRouted($ctx);

            $in = new \Livekit\RoomCompositeEgressRequest();
            $in->mergeFromJsonString((string)$req->getBody(), true);

            $out = $this->svc->StartRoomCompositeEgress($ctx, $in);

            if ($out === null) {
                return $this->writeError($ctx, TwirpError::newError(ErrorCode::Internal, 'received a null response while calling StartRoomCompositeEgress. null responses are not supported'));
            }

            $ctx = $this->hook->responsePrepared($ctx);
        } catch (GPBDecodeException $e) {
            return $this->writeError($ctx, TwirpError::newError(ErrorCode::Internal, 'failed to parse request json'));
        } catch (\Throwable $e) {
            return $this->writeError($ctx, $e);
        }

        $data = $out->serializeToJsonString();

        $body = $this->streamFactory->createStream($data);

        $resp = $this->responseFactory
            ->createResponse(200)
            ->withHeader('Content-Type', 'application/json')
            ->withBody($body);

        $this->callResponseSent($ctx);

        return $resp;
    }

    private function handleStartRoomCompositeEgressProtobuf(array $ctx, ServerRequestInterface $req): ResponseInterface
    {
        $ctx = Context::withMethodName($ctx, 'StartRoomCompositeEgress');

        try {
            $ctx = $this->hook->requestRouted($ctx);

            $in = new \Livekit\RoomCompositeEgressRequest();
            $in->mergeFromString((string)$req->getBody());

            $out = $this->svc->StartRoomCompositeEgress($ctx, $in);

            if ($out === null) {
                return $this->writeError($ctx, TwirpError::newError(ErrorCode::Internal, 'received a null response while calling StartRoomCompositeEgress. null responses are not supported'));
            }

            $ctx = $this->hook->responsePrepared($ctx);
        } catch (GPBDecodeException $e) {
            return $this->writeError($ctx, TwirpError::newError(ErrorCode::Internal, 'failed to parse request proto'));
        } catch (\Throwable $e) {
            return $this->writeError($ctx, $e);
        }

        $data = $out->serializeToString();

        $body = $this->streamFactory->createStream($data);

        $resp = $this->responseFactory
            ->createResponse(200)
            ->withHeader('Content-Type', 'application/protobuf')
            ->withBody($body);

        $this->callResponseSent($ctx);

        return $resp;
    }
    private function handleStartTrackCompositeEgress(array $ctx, ServerRequestInterface $req): ResponseInterface
    {
        $header = $req->getHeaderLine('Content-Type');
        $i = strpos($header, ';');

        if ($i === false) {
            $i = strlen($header);
        }

        $respHeaders = [];
        $ctx[Context::RESPONSE_HEADER] = &$respHeaders;

        switch (trim(strtolower(substr($header, 0, $i)))) {
            case 'application/json':
                $resp = $this->handleStartTrackCompositeEgressJson($ctx, $req);
                break;

            case 'application/protobuf':
                $resp = $this->handleStartTrackCompositeEgressProtobuf($ctx, $req);
                break;

            default:
                $msg = sprintf('unexpected Content-Type: "%s"', $req->getHeaderLine('Content-Type'));

                return $this->writeError($ctx, $this->badRouteError($msg, $req->getMethod(), $req->getUri()->getPath()));
        }

        foreach ($respHeaders as $key => $value) {
            $resp = $resp->withHeader($key, $value);
        }

        return $resp;
    }

    private function handleStartTrackCompositeEgressJson(array $ctx, ServerRequestInterface $req): ResponseInterface
    {
        $ctx = Context::withMethodName($ctx, 'StartTrackCompositeEgress');

        try {
            $ctx = $this->hook->requestRouted($ctx);

            $in = new \Livekit\TrackCompositeEgressRequest();
            $in->mergeFromJsonString((string)$req->getBody(), true);

            $out = $this->svc->StartTrackCompositeEgress($ctx, $in);

            if ($out === null) {
                return $this->writeError($ctx, TwirpError::newError(ErrorCode::Internal, 'received a null response while calling StartTrackCompositeEgress. null responses are not supported'));
            }

            $ctx = $this->hook->responsePrepared($ctx);
        } catch (GPBDecodeException $e) {
            return $this->writeError($ctx, TwirpError::newError(ErrorCode::Internal, 'failed to parse request json'));
        } catch (\Throwable $e) {
            return $this->writeError($ctx, $e);
        }

        $data = $out->serializeToJsonString();

        $body = $this->streamFactory->createStream($data);

        $resp = $this->responseFactory
            ->createResponse(200)
            ->withHeader('Content-Type', 'application/json')
            ->withBody($body);

        $this->callResponseSent($ctx);

        return $resp;
    }

    private function handleStartTrackCompositeEgressProtobuf(array $ctx, ServerRequestInterface $req): ResponseInterface
    {
        $ctx = Context::withMethodName($ctx, 'StartTrackCompositeEgress');

        try {
            $ctx = $this->hook->requestRouted($ctx);

            $in = new \Livekit\TrackCompositeEgressRequest();
            $in->mergeFromString((string)$req->getBody());

            $out = $this->svc->StartTrackCompositeEgress($ctx, $in);

            if ($out === null) {
                return $this->writeError($ctx, TwirpError::newError(ErrorCode::Internal, 'received a null response while calling StartTrackCompositeEgress. null responses are not supported'));
            }

            $ctx = $this->hook->responsePrepared($ctx);
        } catch (GPBDecodeException $e) {
            return $this->writeError($ctx, TwirpError::newError(ErrorCode::Internal, 'failed to parse request proto'));
        } catch (\Throwable $e) {
            return $this->writeError($ctx, $e);
        }

        $data = $out->serializeToString();

        $body = $this->streamFactory->createStream($data);

        $resp = $this->responseFactory
            ->createResponse(200)
            ->withHeader('Content-Type', 'application/protobuf')
            ->withBody($body);

        $this->callResponseSent($ctx);

        return $resp;
    }
    private function handleStartTrackEgress(array $ctx, ServerRequestInterface $req): ResponseInterface
    {
        $header = $req->getHeaderLine('Content-Type');
        $i = strpos($header, ';');

        if ($i === false) {
            $i = strlen($header);
        }

        $respHeaders = [];
        $ctx[Context::RESPONSE_HEADER] = &$respHeaders;

        switch (trim(strtolower(substr($header, 0, $i)))) {
            case 'application/json':
                $resp = $this->handleStartTrackEgressJson($ctx, $req);
                break;

            case 'application/protobuf':
                $resp = $this->handleStartTrackEgressProtobuf($ctx, $req);
                break;

            default:
                $msg = sprintf('unexpected Content-Type: "%s"', $req->getHeaderLine('Content-Type'));

                return $this->writeError($ctx, $this->badRouteError($msg, $req->getMethod(), $req->getUri()->getPath()));
        }

        foreach ($respHeaders as $key => $value) {
            $resp = $resp->withHeader($key, $value);
        }

        return $resp;
    }

    private function handleStartTrackEgressJson(array $ctx, ServerRequestInterface $req): ResponseInterface
    {
        $ctx = Context::withMethodName($ctx, 'StartTrackEgress');

        try {
            $ctx = $this->hook->requestRouted($ctx);

            $in = new \Livekit\TrackEgressRequest();
            $in->mergeFromJsonString((string)$req->getBody(), true);

            $out = $this->svc->StartTrackEgress($ctx, $in);

            if ($out === null) {
                return $this->writeError($ctx, TwirpError::newError(ErrorCode::Internal, 'received a null response while calling StartTrackEgress. null responses are not supported'));
            }

            $ctx = $this->hook->responsePrepared($ctx);
        } catch (GPBDecodeException $e) {
            return $this->writeError($ctx, TwirpError::newError(ErrorCode::Internal, 'failed to parse request json'));
        } catch (\Throwable $e) {
            return $this->writeError($ctx, $e);
        }

        $data = $out->serializeToJsonString();

        $body = $this->streamFactory->createStream($data);

        $resp = $this->responseFactory
            ->createResponse(200)
            ->withHeader('Content-Type', 'application/json')
            ->withBody($body);

        $this->callResponseSent($ctx);

        return $resp;
    }

    private function handleStartTrackEgressProtobuf(array $ctx, ServerRequestInterface $req): ResponseInterface
    {
        $ctx = Context::withMethodName($ctx, 'StartTrackEgress');

        try {
            $ctx = $this->hook->requestRouted($ctx);

            $in = new \Livekit\TrackEgressRequest();
            $in->mergeFromString((string)$req->getBody());

            $out = $this->svc->StartTrackEgress($ctx, $in);

            if ($out === null) {
                return $this->writeError($ctx, TwirpError::newError(ErrorCode::Internal, 'received a null response while calling StartTrackEgress. null responses are not supported'));
            }

            $ctx = $this->hook->responsePrepared($ctx);
        } catch (GPBDecodeException $e) {
            return $this->writeError($ctx, TwirpError::newError(ErrorCode::Internal, 'failed to parse request proto'));
        } catch (\Throwable $e) {
            return $this->writeError($ctx, $e);
        }

        $data = $out->serializeToString();

        $body = $this->streamFactory->createStream($data);

        $resp = $this->responseFactory
            ->createResponse(200)
            ->withHeader('Content-Type', 'application/protobuf')
            ->withBody($body);

        $this->callResponseSent($ctx);

        return $resp;
    }
    private function handleStartWebEgress(array $ctx, ServerRequestInterface $req): ResponseInterface
    {
        $header = $req->getHeaderLine('Content-Type');
        $i = strpos($header, ';');

        if ($i === false) {
            $i = strlen($header);
        }

        $respHeaders = [];
        $ctx[Context::RESPONSE_HEADER] = &$respHeaders;

        switch (trim(strtolower(substr($header, 0, $i)))) {
            case 'application/json':
                $resp = $this->handleStartWebEgressJson($ctx, $req);
                break;

            case 'application/protobuf':
                $resp = $this->handleStartWebEgressProtobuf($ctx, $req);
                break;

            default:
                $msg = sprintf('unexpected Content-Type: "%s"', $req->getHeaderLine('Content-Type'));

                return $this->writeError($ctx, $this->badRouteError($msg, $req->getMethod(), $req->getUri()->getPath()));
        }

        foreach ($respHeaders as $key => $value) {
            $resp = $resp->withHeader($key, $value);
        }

        return $resp;
    }

    private function handleStartWebEgressJson(array $ctx, ServerRequestInterface $req): ResponseInterface
    {
        $ctx = Context::withMethodName($ctx, 'StartWebEgress');

        try {
            $ctx = $this->hook->requestRouted($ctx);

            $in = new \Livekit\WebEgressRequest();
            $in->mergeFromJsonString((string)$req->getBody(), true);

            $out = $this->svc->StartWebEgress($ctx, $in);

            if ($out === null) {
                return $this->writeError($ctx, TwirpError::newError(ErrorCode::Internal, 'received a null response while calling StartWebEgress. null responses are not supported'));
            }

            $ctx = $this->hook->responsePrepared($ctx);
        } catch (GPBDecodeException $e) {
            return $this->writeError($ctx, TwirpError::newError(ErrorCode::Internal, 'failed to parse request json'));
        } catch (\Throwable $e) {
            return $this->writeError($ctx, $e);
        }

        $data = $out->serializeToJsonString();

        $body = $this->streamFactory->createStream($data);

        $resp = $this->responseFactory
            ->createResponse(200)
            ->withHeader('Content-Type', 'application/json')
            ->withBody($body);

        $this->callResponseSent($ctx);

        return $resp;
    }

    private function handleStartWebEgressProtobuf(array $ctx, ServerRequestInterface $req): ResponseInterface
    {
        $ctx = Context::withMethodName($ctx, 'StartWebEgress');

        try {
            $ctx = $this->hook->requestRouted($ctx);

            $in = new \Livekit\WebEgressRequest();
            $in->mergeFromString((string)$req->getBody());

            $out = $this->svc->StartWebEgress($ctx, $in);

            if ($out === null) {
                return $this->writeError($ctx, TwirpError::newError(ErrorCode::Internal, 'received a null response while calling StartWebEgress. null responses are not supported'));
            }

            $ctx = $this->hook->responsePrepared($ctx);
        } catch (GPBDecodeException $e) {
            return $this->writeError($ctx, TwirpError::newError(ErrorCode::Internal, 'failed to parse request proto'));
        } catch (\Throwable $e) {
            return $this->writeError($ctx, $e);
        }

        $data = $out->serializeToString();

        $body = $this->streamFactory->createStream($data);

        $resp = $this->responseFactory
            ->createResponse(200)
            ->withHeader('Content-Type', 'application/protobuf')
            ->withBody($body);

        $this->callResponseSent($ctx);

        return $resp;
    }
    private function handleUpdateLayout(array $ctx, ServerRequestInterface $req): ResponseInterface
    {
        $header = $req->getHeaderLine('Content-Type');
        $i = strpos($header, ';');

        if ($i === false) {
            $i = strlen($header);
        }

        $respHeaders = [];
        $ctx[Context::RESPONSE_HEADER] = &$respHeaders;

        switch (trim(strtolower(substr($header, 0, $i)))) {
            case 'application/json':
                $resp = $this->handleUpdateLayoutJson($ctx, $req);
                break;

            case 'application/protobuf':
                $resp = $this->handleUpdateLayoutProtobuf($ctx, $req);
                break;

            default:
                $msg = sprintf('unexpected Content-Type: "%s"', $req->getHeaderLine('Content-Type'));

                return $this->writeError($ctx, $this->badRouteError($msg, $req->getMethod(), $req->getUri()->getPath()));
        }

        foreach ($respHeaders as $key => $value) {
            $resp = $resp->withHeader($key, $value);
        }

        return $resp;
    }

    private function handleUpdateLayoutJson(array $ctx, ServerRequestInterface $req): ResponseInterface
    {
        $ctx = Context::withMethodName($ctx, 'UpdateLayout');

        try {
            $ctx = $this->hook->requestRouted($ctx);

            $in = new \Livekit\UpdateLayoutRequest();
            $in->mergeFromJsonString((string)$req->getBody(), true);

            $out = $this->svc->UpdateLayout($ctx, $in);

            if ($out === null) {
                return $this->writeError($ctx, TwirpError::newError(ErrorCode::Internal, 'received a null response while calling UpdateLayout. null responses are not supported'));
            }

            $ctx = $this->hook->responsePrepared($ctx);
        } catch (GPBDecodeException $e) {
            return $this->writeError($ctx, TwirpError::newError(ErrorCode::Internal, 'failed to parse request json'));
        } catch (\Throwable $e) {
            return $this->writeError($ctx, $e);
        }

        $data = $out->serializeToJsonString();

        $body = $this->streamFactory->createStream($data);

        $resp = $this->responseFactory
            ->createResponse(200)
            ->withHeader('Content-Type', 'application/json')
            ->withBody($body);

        $this->callResponseSent($ctx);

        return $resp;
    }

    private function handleUpdateLayoutProtobuf(array $ctx, ServerRequestInterface $req): ResponseInterface
    {
        $ctx = Context::withMethodName($ctx, 'UpdateLayout');

        try {
            $ctx = $this->hook->requestRouted($ctx);

            $in = new \Livekit\UpdateLayoutRequest();
            $in->mergeFromString((string)$req->getBody());

            $out = $this->svc->UpdateLayout($ctx, $in);

            if ($out === null) {
                return $this->writeError($ctx, TwirpError::newError(ErrorCode::Internal, 'received a null response while calling UpdateLayout. null responses are not supported'));
            }

            $ctx = $this->hook->responsePrepared($ctx);
        } catch (GPBDecodeException $e) {
            return $this->writeError($ctx, TwirpError::newError(ErrorCode::Internal, 'failed to parse request proto'));
        } catch (\Throwable $e) {
            return $this->writeError($ctx, $e);
        }

        $data = $out->serializeToString();

        $body = $this->streamFactory->createStream($data);

        $resp = $this->responseFactory
            ->createResponse(200)
            ->withHeader('Content-Type', 'application/protobuf')
            ->withBody($body);

        $this->callResponseSent($ctx);

        return $resp;
    }
    private function handleUpdateStream(array $ctx, ServerRequestInterface $req): ResponseInterface
    {
        $header = $req->getHeaderLine('Content-Type');
        $i = strpos($header, ';');

        if ($i === false) {
            $i = strlen($header);
        }

        $respHeaders = [];
        $ctx[Context::RESPONSE_HEADER] = &$respHeaders;

        switch (trim(strtolower(substr($header, 0, $i)))) {
            case 'application/json':
                $resp = $this->handleUpdateStreamJson($ctx, $req);
                break;

            case 'application/protobuf':
                $resp = $this->handleUpdateStreamProtobuf($ctx, $req);
                break;

            default:
                $msg = sprintf('unexpected Content-Type: "%s"', $req->getHeaderLine('Content-Type'));

                return $this->writeError($ctx, $this->badRouteError($msg, $req->getMethod(), $req->getUri()->getPath()));
        }

        foreach ($respHeaders as $key => $value) {
            $resp = $resp->withHeader($key, $value);
        }

        return $resp;
    }

    private function handleUpdateStreamJson(array $ctx, ServerRequestInterface $req): ResponseInterface
    {
        $ctx = Context::withMethodName($ctx, 'UpdateStream');

        try {
            $ctx = $this->hook->requestRouted($ctx);

            $in = new \Livekit\UpdateStreamRequest();
            $in->mergeFromJsonString((string)$req->getBody(), true);

            $out = $this->svc->UpdateStream($ctx, $in);

            if ($out === null) {
                return $this->writeError($ctx, TwirpError::newError(ErrorCode::Internal, 'received a null response while calling UpdateStream. null responses are not supported'));
            }

            $ctx = $this->hook->responsePrepared($ctx);
        } catch (GPBDecodeException $e) {
            return $this->writeError($ctx, TwirpError::newError(ErrorCode::Internal, 'failed to parse request json'));
        } catch (\Throwable $e) {
            return $this->writeError($ctx, $e);
        }

        $data = $out->serializeToJsonString();

        $body = $this->streamFactory->createStream($data);

        $resp = $this->responseFactory
            ->createResponse(200)
            ->withHeader('Content-Type', 'application/json')
            ->withBody($body);

        $this->callResponseSent($ctx);

        return $resp;
    }

    private function handleUpdateStreamProtobuf(array $ctx, ServerRequestInterface $req): ResponseInterface
    {
        $ctx = Context::withMethodName($ctx, 'UpdateStream');

        try {
            $ctx = $this->hook->requestRouted($ctx);

            $in = new \Livekit\UpdateStreamRequest();
            $in->mergeFromString((string)$req->getBody());

            $out = $this->svc->UpdateStream($ctx, $in);

            if ($out === null) {
                return $this->writeError($ctx, TwirpError::newError(ErrorCode::Internal, 'received a null response while calling UpdateStream. null responses are not supported'));
            }

            $ctx = $this->hook->responsePrepared($ctx);
        } catch (GPBDecodeException $e) {
            return $this->writeError($ctx, TwirpError::newError(ErrorCode::Internal, 'failed to parse request proto'));
        } catch (\Throwable $e) {
            return $this->writeError($ctx, $e);
        }

        $data = $out->serializeToString();

        $body = $this->streamFactory->createStream($data);

        $resp = $this->responseFactory
            ->createResponse(200)
            ->withHeader('Content-Type', 'application/protobuf')
            ->withBody($body);

        $this->callResponseSent($ctx);

        return $resp;
    }
    private function handleListEgress(array $ctx, ServerRequestInterface $req): ResponseInterface
    {
        $header = $req->getHeaderLine('Content-Type');
        $i = strpos($header, ';');

        if ($i === false) {
            $i = strlen($header);
        }

        $respHeaders = [];
        $ctx[Context::RESPONSE_HEADER] = &$respHeaders;

        switch (trim(strtolower(substr($header, 0, $i)))) {
            case 'application/json':
                $resp = $this->handleListEgressJson($ctx, $req);
                break;

            case 'application/protobuf':
                $resp = $this->handleListEgressProtobuf($ctx, $req);
                break;

            default:
                $msg = sprintf('unexpected Content-Type: "%s"', $req->getHeaderLine('Content-Type'));

                return $this->writeError($ctx, $this->badRouteError($msg, $req->getMethod(), $req->getUri()->getPath()));
        }

        foreach ($respHeaders as $key => $value) {
            $resp = $resp->withHeader($key, $value);
        }

        return $resp;
    }

    private function handleListEgressJson(array $ctx, ServerRequestInterface $req): ResponseInterface
    {
        $ctx = Context::withMethodName($ctx, 'ListEgress');

        try {
            $ctx = $this->hook->requestRouted($ctx);

            $in = new \Livekit\ListEgressRequest();
            $in->mergeFromJsonString((string)$req->getBody(), true);

            $out = $this->svc->ListEgress($ctx, $in);

            if ($out === null) {
                return $this->writeError($ctx, TwirpError::newError(ErrorCode::Internal, 'received a null response while calling ListEgress. null responses are not supported'));
            }

            $ctx = $this->hook->responsePrepared($ctx);
        } catch (GPBDecodeException $e) {
            return $this->writeError($ctx, TwirpError::newError(ErrorCode::Internal, 'failed to parse request json'));
        } catch (\Throwable $e) {
            return $this->writeError($ctx, $e);
        }

        $data = $out->serializeToJsonString();

        $body = $this->streamFactory->createStream($data);

        $resp = $this->responseFactory
            ->createResponse(200)
            ->withHeader('Content-Type', 'application/json')
            ->withBody($body);

        $this->callResponseSent($ctx);

        return $resp;
    }

    private function handleListEgressProtobuf(array $ctx, ServerRequestInterface $req): ResponseInterface
    {
        $ctx = Context::withMethodName($ctx, 'ListEgress');

        try {
            $ctx = $this->hook->requestRouted($ctx);

            $in = new \Livekit\ListEgressRequest();
            $in->mergeFromString((string)$req->getBody());

            $out = $this->svc->ListEgress($ctx, $in);

            if ($out === null) {
                return $this->writeError($ctx, TwirpError::newError(ErrorCode::Internal, 'received a null response while calling ListEgress. null responses are not supported'));
            }

            $ctx = $this->hook->responsePrepared($ctx);
        } catch (GPBDecodeException $e) {
            return $this->writeError($ctx, TwirpError::newError(ErrorCode::Internal, 'failed to parse request proto'));
        } catch (\Throwable $e) {
            return $this->writeError($ctx, $e);
        }

        $data = $out->serializeToString();

        $body = $this->streamFactory->createStream($data);

        $resp = $this->responseFactory
            ->createResponse(200)
            ->withHeader('Content-Type', 'application/protobuf')
            ->withBody($body);

        $this->callResponseSent($ctx);

        return $resp;
    }
    private function handleStopEgress(array $ctx, ServerRequestInterface $req): ResponseInterface
    {
        $header = $req->getHeaderLine('Content-Type');
        $i = strpos($header, ';');

        if ($i === false) {
            $i = strlen($header);
        }

        $respHeaders = [];
        $ctx[Context::RESPONSE_HEADER] = &$respHeaders;

        switch (trim(strtolower(substr($header, 0, $i)))) {
            case 'application/json':
                $resp = $this->handleStopEgressJson($ctx, $req);
                break;

            case 'application/protobuf':
                $resp = $this->handleStopEgressProtobuf($ctx, $req);
                break;

            default:
                $msg = sprintf('unexpected Content-Type: "%s"', $req->getHeaderLine('Content-Type'));

                return $this->writeError($ctx, $this->badRouteError($msg, $req->getMethod(), $req->getUri()->getPath()));
        }

        foreach ($respHeaders as $key => $value) {
            $resp = $resp->withHeader($key, $value);
        }

        return $resp;
    }

    private function handleStopEgressJson(array $ctx, ServerRequestInterface $req): ResponseInterface
    {
        $ctx = Context::withMethodName($ctx, 'StopEgress');

        try {
            $ctx = $this->hook->requestRouted($ctx);

            $in = new \Livekit\StopEgressRequest();
            $in->mergeFromJsonString((string)$req->getBody(), true);

            $out = $this->svc->StopEgress($ctx, $in);

            if ($out === null) {
                return $this->writeError($ctx, TwirpError::newError(ErrorCode::Internal, 'received a null response while calling StopEgress. null responses are not supported'));
            }

            $ctx = $this->hook->responsePrepared($ctx);
        } catch (GPBDecodeException $e) {
            return $this->writeError($ctx, TwirpError::newError(ErrorCode::Internal, 'failed to parse request json'));
        } catch (\Throwable $e) {
            return $this->writeError($ctx, $e);
        }

        $data = $out->serializeToJsonString();

        $body = $this->streamFactory->createStream($data);

        $resp = $this->responseFactory
            ->createResponse(200)
            ->withHeader('Content-Type', 'application/json')
            ->withBody($body);

        $this->callResponseSent($ctx);

        return $resp;
    }

    private function handleStopEgressProtobuf(array $ctx, ServerRequestInterface $req): ResponseInterface
    {
        $ctx = Context::withMethodName($ctx, 'StopEgress');

        try {
            $ctx = $this->hook->requestRouted($ctx);

            $in = new \Livekit\StopEgressRequest();
            $in->mergeFromString((string)$req->getBody());

            $out = $this->svc->StopEgress($ctx, $in);

            if ($out === null) {
                return $this->writeError($ctx, TwirpError::newError(ErrorCode::Internal, 'received a null response while calling StopEgress. null responses are not supported'));
            }

            $ctx = $this->hook->responsePrepared($ctx);
        } catch (GPBDecodeException $e) {
            return $this->writeError($ctx, TwirpError::newError(ErrorCode::Internal, 'failed to parse request proto'));
        } catch (\Throwable $e) {
            return $this->writeError($ctx, $e);
        }

        $data = $out->serializeToString();

        $body = $this->streamFactory->createStream($data);

        $resp = $this->responseFactory
            ->createResponse(200)
            ->withHeader('Content-Type', 'application/protobuf')
            ->withBody($body);

        $this->callResponseSent($ctx);

        return $resp;
    }

    /**
     * Extracts components from a path.
     *
     * Expected format: "[<prefix>]/<package>.<Service>/<Method>"
     */
    private function parsePath(string $path): array
    {
        $parts = explode('/', $path);

        if (count($parts) < 2) {
            return ["", "", ""];
        }

        $method = $parts[count($parts) - 1];
        $service = $parts[count($parts) - 2];
        $prefix = implode('/', array_slice($parts, 0, count($parts) - 2));

        return [$prefix, $service, $method];
    }

    /**
     * Used when there is no route for a request.
     */
    private function noRouteError(ServerRequestInterface $req): TwirpError
    {
        $msg = sprintf('no handler for path "%s"', $req->getUri()->getPath());

        return $this->badRouteError($msg, $req->getMethod(), $req->getUri()->getPath());
    }

    /**
     * Used when the twirp server cannot route a request.
     */
    private function badRouteError(string $msg, string $method, string $url): TwirpError
    {
        $e = TwirpError::newError(ErrorCode::BadRoute, $msg);
        $e->setMeta('twirp_invalid_route', $method . ' ' . $url);

        return $e;
    }

    /**
     * Writes errors in the response and triggers hooks.
     */
    private function writeError(array $ctx, \Throwable $e): ResponseInterface
    {
        // Non-twirp errors are mapped to be internal errors
        if ($e instanceof \Twirp\Error) {
            $statusCode = $e->getErrorCode();
        } else {
            $statusCode = ErrorCode::Internal;
        }

        $statusCode = ErrorCode::serverHTTPStatusFromErrorCode($statusCode);
        $ctx = Context::withStatusCode($ctx, $statusCode);

        try {
            $ctx = $this->hook->error($ctx, $e);
        } catch (\Throwable $e) {
            // We have three options here. We could log the error, call the Error
            // hook, or just silently ignore the error.
            //
            // Logging is unacceptable because we don't have a user-controlled
            // logger; writing out to stderr without permission is too rude.
            //
            // Calling the Error hook would confuse users: it would mean the Error
            // hook got called twice for one request, which is likely to lead to
            // duplicated log messages and metrics, no matter how well we document
            // the behavior.
            //
            // Silently ignoring the error is our least-bad option. It's highly
            // likely that the connection is broken and the original 'err' says
            // so anyway.
        }

        $this->callResponseSent($ctx);

        if (!$e instanceof \Twirp\Error) {
            $e = TwirpError::errorFrom($e, 'internal error');
        }

        $body = $this->streamFactory->createStream(json_encode([
            'code' => $e->getErrorCode(),
            'msg' => $e->getMessage(),
            'meta' => $e->getMetaMap(),
        ]));

        return $this->responseFactory
            ->createResponse($statusCode)
            ->withHeader('Content-Type', 'application/json') // Error responses are always JSON (instead of protobuf)
            ->withBody($body);
    }

    /**
     * Triggers response sent hook.
     */
    private function callResponseSent(array $ctx): void
    {
        try {
            $this->hook->responseSent($ctx);
        } catch (\Throwable $e) {
            // We have three options here. We could log the error, call the Error
            // hook, or just silently ignore the error.
            //
            // Logging is unacceptable because we don't have a user-controlled
            // logger; writing out to stderr without permission is too rude.
            //
            // Calling the Error hook could confuse users: this hook is triggered
            // by the error hook itself, which is likely to lead to
            // duplicated log messages and metrics, no matter how well we document
            // the behavior.
            //
            // Silently ignoring the error is our least-bad option. It's highly
            // likely that the connection is broken and the original 'err' says
            // so anyway.
        }
    }
}