<?php
/**
 * External functions for the local_ws_mod_quiz_update_key plugin
 *
 * @package    local_ws_mod_quiz_update_key
 * @category   external
 * @copyright  2025 Maxime Cruzel
 * @license    https://opensource.org/licenses/MIT MIT
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');
require_once($CFG->libdir . '/moodlelib.php');

class local_ws_mod_quiz_update_key_external extends external_api {
    
    /**
     * Retourne la description des paramètres pour execute
     *
     * @return external_function_parameters
     */
    public static function execute_parameters() {
        return new external_function_parameters(
            array(
                'cmid' => new external_value(PARAM_INT, 'ID du module de cours (course module id)'),
                'key' => new external_value(PARAM_TEXT, 'Nouvelle clé du quiz')
            )
        );
    }

    /**
     * Met à jour la clé d'un quiz
     *
     * @param int $cmid ID du module de cours
     * @param string $key Nouvelle clé
     * @return bool
     * @throws moodle_exception
     */
    public static function execute($cmid, $key) {
        global $DB;

        // Valider les paramètres
        $params = self::validate_parameters(self::execute_parameters(), array('cmid' => $cmid, 'key' => $key));

        // Vérifier que le module existe et est un quiz
        $sql = "SELECT cm.instance AS quizid 
                FROM {course_modules} cm
                JOIN {modules} m ON m.id = cm.module
                WHERE cm.id = :cmid AND m.name = 'quiz'";
        
        $record = $DB->get_record_sql($sql, array('cmid' => $params['cmid']));

        if (!$record) {
            throw new moodle_exception('modulenotquiz', 'local_ws_mod_quiz_update_key');
        }

        // Récupérer le cours associé
        $cm = $DB->get_record('course_modules', array('id' => $params['cmid']), '*', MUST_EXIST);
        $course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
        
        // Vérifier les permissions de l'utilisateur
        $context = context_course::instance($course->id);
        require_capability('mod/quiz:manage', $context);

        // Mise à jour de la clé du quiz
        $quiz = array(
            'id' => $record->quizid,
            'password' => $params['key']
        );

        return $DB->update_record('quiz', $quiz);
    }

    /**
     * Retourne la description de la valeur de retour pour execute
     *
     * @return external_value
     */
    public static function execute_returns() {
        return new external_value(PARAM_BOOL, 'true si succès, false sinon');
    }
} 