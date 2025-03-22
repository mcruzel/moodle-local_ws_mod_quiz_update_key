<?php
/**
 * Web service definitions for the local_ws_mod_quiz_update_key plugin
 *
 * @package    local_ws_mod_quiz_update_key
 * @category   external
 * @copyright  2025 Maxime Cruzel
 * @license    https://opensource.org/licenses/MIT MIT
 */

defined('MOODLE_INTERNAL') || die();

$functions = array(
    'local_ws_mod_quiz_update_key_update' => array(
        'classname'     => 'local_ws_mod_quiz_update_key_external',
        'methodname'    => 'execute',
        'description'   => 'Met à jour la clé d\'un quiz',
        'type'          => 'write',
        'capabilities'  => 'mod/quiz:manage',
        'services'      => array(MOODLE_OFFICIAL_MOBILE_SERVICE)
    ),
); 