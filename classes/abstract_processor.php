<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace aiprovider_sarvam;

use core\http_client;
use core_ai\process_base;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

/**
 * Base processor for the Sarvam AI provider.
 *
 * @package   aiprovider_sarvam
 * @copyright 2026 Santosh Nagargoje <santosh.nag2217@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
abstract class abstract_processor extends process_base
{
    protected function get_endpoint(): UriInterface
    {
        $endpoint = $this->provider->actionconfig[$this->action::class]['settings']['endpoint'] ?? 'https://api.sarvam.ai/v1/chat/completions';
        return new Uri($endpoint);
    }

    protected function get_model(): string
    {
        return $this->provider->actionconfig[$this->action::class]['settings']['model'] ?? 'sarvam-105b';
    }

    protected function get_model_settings(): array
    {
        $settings = $this->provider->actionconfig[$this->action::class]['settings'];
        if (!empty($settings['modelextraparams'])) {
            $params = json_decode($settings['modelextraparams'], true);
            if (is_array($params)) {
                foreach ($params as $key => $param) {
                    $settings[$key] = $param;
                }
            }
        }

        unset(
            $settings['model'],
            $settings['endpoint'],
            $settings['systeminstruction'],
            $settings['providerid'],
            $settings['modelextraparams'],
        );

        return $settings;
    }

    protected function get_system_instruction(): string
    {
        return $this->provider->actionconfig[$this->action::class]['settings']['systeminstruction'] ?? '';
    }

    abstract protected function create_request_object(string $userid): RequestInterface;

    abstract protected function handle_api_success(ResponseInterface $response): array;

    #[\Override]
    protected function query_ai_api(): array
    {
        $request = $this->create_request_object(
            userid: $this->provider->generate_userid($this->action->get_configuration('userid')),
        );
        $request = $this->provider->add_authentication_headers($request);

        $client = \core\di::get(http_client::class);
        try {
            $response = $client->send(
                $request, [
                'base_uri' => $this->get_endpoint(),
                RequestOptions::HTTP_ERRORS => false,
                ]
            );
        } catch (RequestException $e) {
            return \core_ai\error\factory::create($e->getCode(), $e->getMessage())->get_error_details();
        }

        $status = $response->getStatusCode();
        if ($status === 200) {
            return $this->handle_api_success($response);
        }

        return $this->handle_api_error($response);
    }

    protected function handle_api_error(ResponseInterface $response): array
    {
        $status = $response->getStatusCode();
        if ($status >= 500 && $status < 600) {
            $errormessage = $response->getReasonPhrase();
        } else {
            $bodyobj = json_decode($response->getBody()->getContents());
            $errormessage = $bodyobj->error->message ?? $response->getReasonPhrase();
        }

        return \core_ai\error\factory::create($status, $errormessage)->get_error_details();
    }
}
