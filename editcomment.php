<?php

    require_once("../../../../config.php");
    require_once("mypdflib.php");

    $id   = optional_param('id', 0, PARAM_INT);          // Course module ID
    $a    = optional_param('a', 0, PARAM_INT);           // Assignment ID
    $userid = optional_param('userid', 0, PARAM_INT);
    $pageno = optional_param('pageno', 1, PARAM_INT);

    if ($id) {
        if (! $cm = get_coursemodule_from_id('assignment', $id)) {
            error("Course Module ID was incorrect");
        }

        if (! $assignment = get_record("assignment", "id", $cm->instance)) {
            error("assignment ID was incorrect");
        }

        if (! $course = get_record("course", "id", $assignment->course)) {
            error("Course is misconfigured");
        }
    } else {
        if (!$assignment = get_record("assignment", "id", $a)) {
            error("Course module is incorrect");
        }
        if (! $course = get_record("course", "id", $assignment->course)) {
            error("Course is misconfigured");
        }
        if (! $cm = get_coursemodule_from_instance("assignment", $assignment->id, $course->id)) {
            error("Course Module ID was incorrect");
        }
    }

    require_login($course->id, false, $cm);

    require_capability('mod/assignment:grade', get_context_instance(CONTEXT_MODULE, $cm->id));


require('assignment.class.php');
$assignmentinstance = new assignment_uploadpdf($cm->id, $assignment, $cm, $course);
$assignmentinstance->edit_comment_page($userid, $pageno);