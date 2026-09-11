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

use core_ai\hook\after_ai_action_settings_form_hook;
use core_ai\hook\after_ai_provider_form_hook;

/**
 * Hook listener for the Sarvam provider.
 *
 * @package   aiprovider_sarvam
 * @copyright 2026 Santosh Nagargoje <santosh.nag2217@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class hook_listener
{
    public static function set_form_definition_for_aiprovider_sarvam(after_ai_provider_form_hook $hook): void
    {
        if ($hook->plugin !== 'aiprovider_sarvam') {
            return;
        }

        $mform = $hook->mform;
        $mform->addElement(
            'passwordunmask',
            'apikey',
            get_string('apikey', 'aiprovider_sarvam'),
            ['size' => 75],
        );
        $mform->addHelpButton('apikey', 'apikey', 'aiprovider_sarvam');
        $mform->addRule('apikey', get_string('required'), 'required', null, 'client');
    }

    public static function set_model_form_definition_for_aiprovider_sarvam(after_ai_action_settings_form_hook $hook): void
    {
        if ($hook->plugin !== 'aiprovider_sarvam') {
            return;
        }

        $mform = $hook->mform;
        if (!isset($mform->_elementIndex['model'])) {
            return;
        }

        $model = $mform->getElementValue('model');
        if (is_array($model)) {
            $model = $model[0] ?? '';
        }

        if ($model === 'custom') {
            $mform->addElement('header', 'modelsettingsheader', get_string('settings', 'aiprovider_sarvam'));
            $mform->addElement(
                'textarea',
                'modelextraparams',
                get_string('modelextraparams', 'aiprovider_sarvam'),
                ['rows' => 5, 'cols' => 40],
            );
            $mform->setType('modelextraparams', PARAM_RAW);
            $mform->addHelpButton('modelextraparams', 'modelextraparams', 'aiprovider_sarvam');
        }
    }
}
