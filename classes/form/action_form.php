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

use aiprovider_sarvam\helper;
use core_ai\form\action_settings_form;

/**
 * Base Sarvam action settings form.
 *
 * @package   aiprovider_sarvam
 * @copyright 2026 Santosh Nagargoje <santosh.nag2217@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class action_form extends action_settings_form
{
    protected array $actionconfig;
    protected ?string $returnurl;
    protected string $actionname;
    protected string $action;
    protected int $providerid;
    protected string $providername;
    protected array $storedmodelsettings;

    #[\Override]
    protected function definition(): void
    {
        $mform = $this->_form;
        $this->actionconfig = $this->_customdata['actionconfig']['settings'] ?? [];
        $this->returnurl = $this->_customdata['returnurl'] ?? null;
        $this->actionname = $this->_customdata['actionname'];
        $this->action = $this->_customdata['action'];
        $this->providerid = $this->_customdata['providerid'] ?? 0;
        $this->providername = $this->_customdata['providername'] ?? 'aiprovider_sarvam';
        $this->storedmodelsettings = $this->_customdata['actionconfig']['modelsettings'] ?? [];

        $mform->addElement('header', 'generalsettingsheader', get_string('general', 'core'));
    }

    #[\Override]
    public function set_data($data): void
    {
        if (!empty($data['modelextraparams'])) {
            $data['modelextraparams'] = json_encode(json_decode($data['modelextraparams']), JSON_PRETTY_PRINT);
        }
        parent::set_data($data);
    }

    #[\Override]
    public function get_data(): ?\stdClass
    {
        $data = parent::get_data();

        if (!empty($data)) {
            unset($data->custommodel, $data->modeltemplate);
            $data = (object) array_filter((array) $data);
        }

        return $data;
    }

    #[\Override]
    public function validation($data, $files): array
    {
        $errors = parent::validation($data, $files);

        if (!empty($data['modelextraparams'])) {
            json_decode($data['modelextraparams']);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $errors['modelextraparams'] = get_string('invalidjson', 'aiprovider_sarvam');
            }
        }

        if (array_key_exists('temperature', $data) && $data['temperature'] !== '' && (!is_numeric($data['temperature']) || (float) $data['temperature'] < 0 || (float) $data['temperature'] > 2)) {
            $errors['temperature'] = get_string('settings_temperature_range', 'aiprovider_sarvam');
        }

        if (array_key_exists('max_tokens', $data) && $data['max_tokens'] !== '' && (!is_numeric($data['max_tokens']) || (int) $data['max_tokens'] < 1)) {
            $errors['max_tokens'] = get_string('settings_max_tokens_range', 'aiprovider_sarvam');
        }

        if (($data['model'] ?? '') === 'custom' && empty($data['custommodel'])) {
            $errors['custommodel'] = get_string('required');
        }

        return $errors;
    }

    #[\Override]
    public function get_defaults(): array
    {
        $data = parent::get_defaults();
        unset($data['modeltemplate'], $data['custommodel'], $data['modelextraparams']);
        return $data;
    }

    protected function add_model_fields(): void
    {
        global $PAGE;
        $PAGE->requires->js_call_amd('aiprovider_sarvam/modelchooser', 'init');
        $mform = $this->_form;

        $defaultmodel = $this->actionconfig['model'] ?? 'sarvam-105b';
        $modeltemplate = optional_param('modeltemplate', $defaultmodel, PARAM_TEXT);
        if (isset($this->storedmodelsettings[$modeltemplate])) {
            $this->storedmodelsettings = [$modeltemplate => $this->storedmodelsettings[$modeltemplate]];
        }

        $mform->addElement(
            'select',
            'modeltemplate',
            get_string("action:{$this->actionname}:model", 'aiprovider_sarvam'),
            ['custom' => get_string('custom', 'core_form')] + helper::get_model_list(),
            ['data-modelchooser-field' => 'selector', 'data-storedmodelsettings' => json_encode($this->storedmodelsettings)],
        );
        $mform->setType('modeltemplate', PARAM_TEXT);
        $mform->addRule('modeltemplate', null, 'required', null, 'client');
        $mform->setDefault('modeltemplate', $defaultmodel);
        $mform->addHelpButton('modeltemplate', "action:{$this->actionname}:model", 'aiprovider_sarvam');

        $mform->addElement('hidden', 'model', $this->actionconfig['model'] ?? 'sarvam-105b');
        $mform->setType('model', PARAM_TEXT);

        $mform->addElement('text', 'custommodel', get_string('custom_model_name', 'aiprovider_sarvam'));
        $mform->setType('custommodel', PARAM_TEXT);
        $mform->setDefault('custommodel', $this->actionconfig['model'] ?? '');
        $mform->hideIf('custommodel', 'modeltemplate', 'neq', 'custom');

        $mform->registerNoSubmitButton('updateactionsettings');
        $mform->addElement(
            'submit',
            'updateactionsettings',
            'updateactionsettings',
            ['data-modelchooser-field' => 'updateButton', 'class' => 'd-none']
        );
    }
}
