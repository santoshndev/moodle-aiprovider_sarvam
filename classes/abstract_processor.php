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
abstract class abstract_processor extends process_base {
    /**
     * Get the configured API endpoint for the provider action.
     *
     * @return UriInterface The endpoint URI.
     */
    protected function get_endpoint(): UriInterface {
        $endpoint = $this->provider->actionconfig[$this->action::class]['settings']['endpoint']
            ?? 'https://api.sarvam.ai/v1/chat/completions';
        return new Uri($endpoint);
    }

    /**
     * Get the configured model name.
     *
     * @return string The model identifier.
     */
    protected function get_model(): string {
        return $this->provider->actionconfig[$this->action::class]['settings']['model'] ?? 'sarvam-105b';
    }

    /**
     * Get the merged model settings, including JSON extra parameters.
     *
     * @return array The resolved model settings.
     */
    protected function get_model_settings(): array {
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

    /**
     * Get the configured system instruction text.
     *
     * @return string The system instruction.
     */
    protected function get_system_instruction(): string {
        return $this->provider->actionconfig[$this->action::class]['settings']['systeminstruction'] ?? '';
    }

    /**
     * Create the provider-specific request object.
     *
     * @param string $userid The generated user identifier.
     * @return RequestInterface The prepared HTTP request.
     */
    abstract protected function create_request_object(string $userid): RequestInterface;

    /**
     * Handle a successful API response.
     *
     * @param ResponseInterface $response The HTTP response.
     * @return array The normalized success payload.
     */
    abstract protected function handle_api_success(ResponseInterface $response): array;

    /**
     * Send the request to the AI provider and normalize the result.
     *
     * @return array The API response data or error payload.
     */
    #[\Override]
    protected function query_ai_api(): array {
        $request = $this->create_request_object(
            userid: $this->provider->generate_userid($this->action->get_configuration('userid')),
        );
        $request = $this->provider->add_authentication_headers($request);

        $client = \core\di::get(http_client::class);
        try {
            $response = $client->send(
                $request,
                [
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

    /**
     * Convert an API error response into a Moodle AI error payload.
     *
     * @param ResponseInterface $response The HTTP error response.
     * @return array The normalized error details.
     */
    protected function handle_api_error(ResponseInterface $response): array {
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
