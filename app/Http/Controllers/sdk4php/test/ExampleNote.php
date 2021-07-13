<?php

include '../src/openkm/OpenKM.php';

ini_set('display_errors', true);
error_reporting(E_ALL);

use openkm\OKMWebServicesFactory;
use openkm\OpenKM;
use openkm\bean\Note;

class ExampleNote {

    const HOST = "http://localhost:8080/OpenKM/";
    const USER = "okmAdmin";
    const PASSWORD = "admin";

    private $ws;

    public function __construct() {
        $this->ws = OKMWebServicesFactory::build(self::HOST, self::USER, self::PASSWORD);
    }

    public function testAddNote() {
        try {
            $note = $this->ws->addNote("/okm:root/SDK4PHP/logo.png", "the note text");
            var_dump($note);
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function testGetNote() {
        try {
            $notes = $this->ws->listNotes("/okm:root/SDK4PHP/logo.png");
            if (count($notes) > 0) {
                var_dump($this->ws->getNote($notes[0]->getPath()));
            }
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function testDeleteNote() {
        try {
            $notes = $this->ws->listNotes("/okm:root/SDK4PHP/logo.png");
            if (count($notes) > 0) {
                $this->ws->deleteNote($notes[0]->getPath());
                echo "deleted";
            }
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function testSetNote() {
        try {
            $notes = $this->ws->listNotes("/okm:root/SDK4PHP/logo.png");
            if (count($notes) > 0) {
                $this->ws->setNote($notes[0]->getPath(), "text modified");
                echo "updated";
            }
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function testListNotes() {
        try {
            $notes = $this->ws->listNotes("/okm:root/SDK4PHP/logo.png");
            foreach ($notes as $note) {
                var_dump($note);
            }
        } catch (Exception $e) {
            var_dump($e);
        }
    }

}

$openkm = new OpenKM(); //autoload
$exampleNote = new ExampleNote();
$exampleNote->testListNotes();
?>