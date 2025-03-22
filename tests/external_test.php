<?php
/**
 * Tests for the local_ws_mod_quiz_update_key plugin
 *
 * @package    local_ws_mod_quiz_update_key
 * @category   test
 * @copyright  2025 Maxime Cruzel
 * @license    https://opensource.org/licenses/MIT MIT
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/webservice/tests/helpers.php');

class local_ws_mod_quiz_update_key_external_testcase extends externallib_advanced_testcase {
    
    /**
     * Test la mise à jour de la clé d'un quiz
     */
    public function test_execute() {
        global $DB;
        
        $this->resetAfterTest(true);
        
        // Créer un cours
        $course = $this->getDataGenerator()->create_course();
        
        // Créer un quiz
        $quiz = $this->getDataGenerator()->create_module('quiz', array('course' => $course->id));
        
        // Créer un utilisateur avec les permissions nécessaires
        $user = $this->getDataGenerator()->create_user();
        $this->getDataGenerator()->enrol_user($user->id, $course->id, 'editingteacher');
        
        // Créer un token pour l'utilisateur
        $token = $this->getDataGenerator()->create_user_token($user->id);
        
        // Récupérer le cmid du quiz
        $cm = get_coursemodule_from_instance('quiz', $quiz->id, $course->id);
        
        // Tester la mise à jour de la clé
        $result = $this->execute_ws_function('local_ws_mod_quiz_update_key_update', array(
            'cmid' => $cm->id,
            'key' => 'nouvellecle123'
        ), $token);
        
        $this->assertTrue($result);
        
        // Vérifier que la clé a été mise à jour dans la base de données
        $updatedquiz = $DB->get_record('quiz', array('id' => $quiz->id));
        $this->assertEquals('nouvellecle123', $updatedquiz->password);
    }
    
    /**
     * Test que la fonction renvoie une erreur si le module n'est pas un quiz
     */
    public function test_execute_not_quiz() {
        global $DB;
        
        $this->resetAfterTest(true);
        
        // Créer un cours
        $course = $this->getDataGenerator()->create_course();
        
        // Créer une activité qui n'est pas un quiz (par exemple, un forum)
        $forum = $this->getDataGenerator()->create_module('forum', array('course' => $course->id));
        
        // Créer un utilisateur avec les permissions nécessaires
        $user = $this->getDataGenerator()->create_user();
        $this->getDataGenerator()->enrol_user($user->id, $course->id, 'editingteacher');
        
        // Créer un token pour l'utilisateur
        $token = $this->getDataGenerator()->create_user_token($user->id);
        
        // Récupérer le cmid du forum
        $cm = get_coursemodule_from_instance('forum', $forum->id, $course->id);
        
        // Tester que la fonction renvoie une erreur
        $this->expectException(moodle_exception::class);
        $this->expectExceptionMessage('The specified module is not a quiz activity');
        
        $this->execute_ws_function('local_ws_mod_quiz_update_key_update', array(
            'cmid' => $cm->id,
            'key' => 'nouvellecle123'
        ), $token);
    }
} 