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

namespace aiprovider_sarvam\form;

/**
 * Action settings form for text generation actions.
 *
 * @package   aiprovider_sarvam
 * @copyright 2026 Santosh Nagargoje <santosh.nag2217@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class action_generate_text_form extends action_form {
    #[\Override]
    protected function definition(): void {
        parent::definition();

        $mform = $this->_form;

        $mform->addElement(
            'select',
            'model',
            get_string('model', 'aiprovider_sarvam'),
            \aiprovider_sarvam\helper::get_model_list(),
        );
        $mform->setType('model', PARAM_TEXT);
        $mform->setDefault('model', $this->actionconfig['model'] ?? 'sarvam-105b');
        $mform->addHelpButton('model', 'model', 'aiprovider_sarvam');

        $mform->addElement(
            'text',
            'endpoint',
            get_string('endpoint', 'aiprovider_sarvam'),
            ['size' => 80],
        );
        $mform->setType('endpoint', PARAM_URL);
        $mform->setDefault('endpoint', $this->actionconfig['endpoint'] ?? 'https://api.sarvam.ai/v1/chat/completions');
        $mform->addHelpButton('endpoint', 'endpoint', 'aiprovider_sarvam');

        $mform->addElement('header', 'modelsettingsheader', get_string('settings', 'aiprovider_sarvam'));

        $mform->addElement('text', 'max_tokens', get_string('settings_max_tokens', 'aiprovider_sarvam'), ['size' => 10]);
        $mform->setType('max_tokens', PARAM_INT);
        $mform->setDefault('max_tokens', $this->actionconfig['max_tokens'] ?? '');
        $mform->addHelpButton('max_tokens', 'settings_max_tokens', 'aiprovider_sarvam');

        $mform->addElement('text', 'temperature', get_string('settings_temperature', 'aiprovider_sarvam'), ['size' => 10]);
        $mform->setType('temperature', PARAM_FLOAT);
        $mform->setDefault('temperature', $this->actionconfig['temperature'] ?? '');
        $mform->addHelpButton('temperature', 'settings_temperature', 'aiprovider_sarvam');

        $mform->addElement('textarea', 'systeminstruction', get_string('systeminstruction', 'aiprovider_sarvam'), ['rows' => 4, 'cols' => 80]);
        $mform->setType('systeminstruction', PARAM_TEXT);
        $mform->setDefault('systeminstruction', $this->actionconfig['systeminstruction'] ?? '');
        $mform->addHelpButton('systeminstruction', 'systeminstruction', 'aiprovider_sarvam');

        $mform->addElement('textarea', 'modelextraparams', get_string('modelextraparams', 'aiprovider_sarvam'), ['rows' => 4, 'cols' => 80]);
        $mform->setType('modelextraparams', PARAM_RAW);
        $mform->setDefault('modelextraparams', $this->actionconfig['modelextraparams'] ?? '{}');
        $mform->addHelpButton('modelextraparams', 'modelextraparams', 'aiprovider_sarvam');

        if ($this->returnurl) {
            $mform->addElement('hidden', 'returnurl', $this->returnurl);
            $mform->setType('returnurl', PARAM_LOCALURL);
        }

        $mform->addElement('hidden', 'action', $this->action);
        $mform->setType('action', PARAM_TEXT);

        $mform->addElement('hidden', 'provider', $this->providername);
        $mform->setType('provider', PARAM_ALPHANUMEXT);

        $mform->addElement('hidden', 'providerid', $this->providerid);
        $mform->setType('providerid', PARAM_INT);
    }
}
