<?php
namespace SlashCommands\Utils;

use ParagonIE\Halite\Halite;
use ParagonIE\Halite\HiddenString;
use Psr\Http\Message\ServerRequestInterface;
use League\Route\Http\Exception\UnauthorizedException;
use ParagonIE\Halite\Asymmetric;
use const PUBLIC_KEY;

trait VerificationTrait {

    private function checkSignature(ServerRequestInterface $request): void
    {
        $signature = $request->getHeaderLine('X-Signature-Ed25519');
        $timestamp = $request->getHeaderLine('X-Signature-Timestamp');
        $body = (string)$request->getBody();

        if (((int)$timestamp <= time() - 5 OR (int) $timestamp >= time() + 5) AND !IGNORE_TIME) {
            throw new UnauthorizedException('Timestamp out of date (max 5 second diff allowed). To disable the timestamp verification set IGNORE_TIME constant to true.');
        }

        $message = $timestamp . $body;
        $key = new Asymmetric\SignaturePublicKey(new HiddenString(hex2bin(PUBLIC_KEY)));

        if (!Asymmetric\Crypto::verify($message, $key, $signature, Halite::ENCODE_HEX)) {
            throw new UnauthorizedException('Invalid signature');
        }
    }

}
