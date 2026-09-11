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

/**
 * Strings for component aiprovider_sarvam, language 'en'.
 *
 * @package   aiprovider_sarvam
 * @copyright 2026 Santosh Nagargoje <santosh.nag2217@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['action:explain_text:endpoint'] = 'API endpoint';
$string['action:explain_text:model'] = 'Text explanation model';
$string['action:explain_text:model_help'] = 'The model used to explain the provided text.';
$string['action:explain_text:systeminstruction'] = 'System instruction';
$string['action:explain_text:systeminstruction_help'] = 'This instruction is sent to the AI model along with the user\'s prompt. Editing this instruction is not recommended unless absolutely required.';
$string['action:generate_text:endpoint'] = 'API endpoint';
$string['action:generate_text:model'] = 'AI model';
$string['action:generate_text:model_help'] = 'The model used to generate the text response.';
$string['action:generate_text:systeminstruction'] = 'System instruction';
$string['action:generate_text:systeminstruction_help'] = 'This instruction is sent to the AI model along with the user\'s prompt. Editing this instruction is not recommended unless absolutely required.';
$string['action:summarise_text:endpoint'] = 'API endpoint';
$string['action:summarise_text:model'] = 'AI model';
$string['action:summarise_text:model_help'] = 'The model used to summarise the provided text.';
$string['action:summarise_text:systeminstruction'] = 'System instruction';
$string['action:summarise_text:systeminstruction_help'] = 'This instruction is sent to the AI model along with the user\'s prompt. Editing this instruction is not recommended unless absolutely required.';
$string['apikey'] = 'Sarvam API key';
$string['apikey_help'] = 'Create a key in your Sarvam AI dashboard.';
$string['custom_model_name'] = 'Custom model name';
$string['endpoint'] = 'API endpoint';
$string['endpoint_help'] = 'The endpoint for the Sarvam AI Chat Completions API.';
$string['extraparams'] = 'Extra parameters';
$string['extraparams_help'] = 'Extra parameters can be configured here. Use JSON format.';
$string['invalidjson'] = 'Invalid JSON string';
$string['model'] = 'AI model';
$string['model_help'] = 'The model used to generate the response.';
$string['model_sarvam-105b'] = 'Sarvam-105B';
$string['modelextraparams'] = 'Model extra parameters';
$string['modelextraparams_help'] = 'Optional JSON object with additional request parameters such as temperature, max_tokens, or top_p.';
$string['pluginname'] = 'Sarvam AI provider';
$string['systeminstruction'] = 'System instruction';
$string['systeminstruction_help'] = 'This instruction is sent to the AI model along with the user\'s prompt.';
$string['privacy:metadata'] = 'The Sarvam AI provider plugin does not store any personal data.';
$string['privacy:metadata:aiprovider_sarvam:externalpurpose'] = 'This information is sent to the Sarvam AI API in order for a response to be generated. Your Sarvam AI account settings may change how Sarvam stores and retains this data. No user data is explicitly sent to Sarvam AI or stored in Moodle LMS by this plugin.';
$string['privacy:metadata:aiprovider_sarvam:model'] = 'The model used to generate the response.';
$string['privacy:metadata:aiprovider_sarvam:prompttext'] = 'The user entered text prompt used to generate the response.';
$string['settings'] = 'Settings';
$string['settings_help'] = 'Adjust the settings below to customise how requests are sent to Sarvam AI.';
$string['settings_max_tokens'] = 'Max tokens';
$string['settings_max_tokens_help'] = 'The maximum number of tokens to generate in the response.';
$string['settings_max_tokens_range'] = 'Max tokens must be a positive number.';
$string['settings_temperature'] = 'Temperature';
$string['settings_temperature_help'] = 'What sampling temperature to use between 0 and 2.';
$string['settings_temperature_range'] = 'Temperature must be a number between 0 and 2.';
$string['settings_top_p'] = 'top_p';
$string['settings_top_p_help'] = 'Alternative to temperature using nucleus sampling.';
