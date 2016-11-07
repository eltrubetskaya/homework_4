<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 07.11.16
 * Time: 15:33
 */

namespace Controllers;

use Repositories\UniversityRepository;
use Repositories\DepartmentRepository;
use Repositories\StudentsRepository;
use Repositories\DisciplinesRepository;
use Repositories\TeacherRepository;
use Repositories\HomeworkRepository;

class StudentsController
{
    private $resultsStudents;

    private $resultsDepartment;

    private $resultsUniversity;

    private $resultsDisciplines;

    private $resultsTeacher;

    private $resultsHomework;

    private $loader;

    private $twig;

    /**
     * StudentsController constructor.
     * @param $connector
     */
    public function __construct($connector)
    {
        $this->resultsStudents = new StudentsRepository($connector);
        $this->resultsDepartment = new DepartmentRepository($connector);
        $this->resultsUniversity = new UniversityRepository($connector);
        $this->resultsDisciplines = new DisciplinesRepository($connector);
        $this->resultsTeacher = new TeacherRepository($connector);
        $this->resultsHomework = new HomeworkRepository($connector);
        $this->loader = new \Twig_Loader_Filesystem('src/Views/templates/');
        $this->twig = new \Twig_Environment($this->loader, array(
            'cache' => false,
        ));
    }

    /**
     * @return string
     */
    public function indexAction()
    {
        $resultsDataStudents = $this->resultsStudents->findAll(1000, 0);
        $resultsDataDepartment = $this->resultsDepartment->findAll(1000, 0);
        $resultsDataUniversity = $this->resultsUniversity->findAll(1000, 0);
        $resultsDataDisciplines = $this->resultsDisciplines->findAll(1000, 0);
        $resultsDataTeacher = $this->resultsTeacher->findAll(1000, 0);
        $resultsDataHomework = $this->resultsHomework->findAll(1000, 0);
        $get_table = $_GET['controller'];
        return $this->twig->render('tables.html.twig', [
            'resultsDataUniversity' => $resultsDataUniversity,
            'resultsDataDepartment' => $resultsDataDepartment,
            'resultsDataStudents' => $resultsDataStudents,
            'resultsDataDisciplines' => $resultsDataDisciplines,
            'resultsDataTeacher' => $resultsDataTeacher,
            'resultsDataHomework' => $resultsDataHomework,
            'get_table' => $get_table
        ]);
    }

    /**
     * @return string
     */
    public function createAction()
    {
        if (isset($_POST['first_name'])) {
            $this->resultsStudents->insert(
                [
                    'first_name' => $_POST['first_name'],
                    'last_name'  => $_POST['last_name'],
                    'email'      => $_POST['email'],
                    'tel'        => $_POST['tel'],
                ]
            );

            return $this->indexAction();
        }
        return $this->twig->render('students_form.html.twig',
            [
                'first_name' => '',
                'last_name' => '',
                'email' => '',
                'tel' => '',
                'action' => 'create'
            ]
        );
    }

    /**
     * @return string
     */
    public function editAction()
    {
        if (isset($_POST['first_name'])) {
            $this->resultsStudents->update(
                [
                    'first_name' => $_POST['first_name'],
                    'last_name'  => $_POST['last_name'],
                    'email'      => $_POST['email'],
                    'tel'        => $_POST['tel'],
                    'id'    => (int) $_POST['student_id'],
                ]
            );
            return $this->indexAction();
        }

        $resultsData = $this->resultsStudents->find((int) $_GET['id']);

        return $this->twig->render('students_form.html.twig',
            [
                'first_name' => $resultsData['first_name'],
                'last_name' => $resultsData['last_name'],
                'email' => $resultsData['email'],
                'tel' => $resultsData['tel'],
                'student_id' => $resultsData['id'],
                'action' => 'edit'
            ]
        );
    }

    /**
     * @return string
     */
    public function deleteAction()
    {
        if (isset($_POST['student_id'])) {
            $id = (int) $_POST['student_id'];
            $this->resultsStudents->remove(['id' => $id]);
            return $this->indexAction();
        }

        $resultsData = $this->resultsStudents->find((int) $_GET['id']);

        return $this->twig->render('students_form.html.twig',
            [
                'first_name' => $resultsData['first_name'],
                'last_name' => $resultsData['last_name'],
                'email' => $resultsData['email'],
                'tel' => $resultsData['tel'],
                'student_id' => $resultsData['id'],
                'action' => 'delete'
            ]
        );
    }
}