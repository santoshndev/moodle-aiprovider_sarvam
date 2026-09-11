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

/**
 * Helper methods for the Sarvam AI provider.
 *
 * @package   aiprovider_sarvam
 * @copyright 2026 Santosh Nagargoje <santosh.nag2217@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class helper
{
    /**
     * Return supported model IDs.
     *
     * @return array
     */
    public static function get_model_list(): array
    {
        return [
            'sarvam-105b' => 'Sarvam-105B',
            'sarvam-m' => 'Sarvam M',
            'sarvam-2' => 'Sarvam 2',
            'custom' => 'Custom model',
        ];
    }

    /**
     * Resolve a model name.
     *
     * @param  string $modelname
     * @return string|null
     */
    public static function get_model_class(string $modelname): ?string
    {
        return self::get_model_list()[$modelname] ?? null;
    }
}
