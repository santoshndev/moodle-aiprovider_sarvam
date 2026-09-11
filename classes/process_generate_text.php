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

use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Process text generation for Sarvam AI.
 *
 * @package   aiprovider_sarvam
 * @copyright 2026 Santosh Nagargoje <santosh.nag2217@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class process_generate_text extends abstract_processor {
    #[\Override]
    protected function create_request_object(string $userid): RequestInterface {
        $userobj = new \stdClass();
        $userobj->role = 'user';
        $userobj->content = $this->action->get_configuration('prompttext');

        $requestobj = new \stdClass();
        $requestobj->model = $this->get_model();
        $requestobj->messages = [$userobj];

        $systeminstruction = $this->get_system_instruction();
        if (!empty($systeminstruction)) {
            $systemobj = new \stdClass();
            $systemobj->role = 'system';
            $systemobj->content = $systeminstruction;
            $requestobj->messages = [$systemobj, $userobj];
        }

        foreach ($this->get_model_settings() as $setting => $value) {
            $requestobj->$setting = $value;
        }

        return new Request(
            method: 'POST',
            uri: '',
            headers: [
                'Content-Type' => 'application/json',
            ],
            body: json_encode($requestobj),
        );
    }

    #[\Override]
    protected function handle_api_success(ResponseInterface $response): array {
        $bodyobj = json_decode($response->getBody()->getContents());

        return [
            'success' => true,
            'id' => $bodyobj->id ?? '',
            'fingerprint' => $bodyobj->system_fingerprint ?? '',
            'generatedcontent' => $bodyobj->choices[0]->message->content ?? '',
            'finishreason' => $bodyobj->choices[0]->finish_reason ?? '',
            'prompttokens' => $bodyobj->usage->prompt_tokens ?? 0,
            'completiontokens' => $bodyobj->usage->completion_tokens ?? 0,
            'model' => $bodyobj->model ?? $this->get_model(),
        ];
    }
}
