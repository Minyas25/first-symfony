<?php

namespace App\Repository;
use App\Entity\Course;
use DateTime;


class CourseRepository {

    /**
     * Récupère tous les cours
     * @return Course[]
     */
    public function findAll():array{

        $list = [];
        $connection = Database::getConnection();
        $query = $connection->prepare("SELECT * FROM course");

        $query->execute();

        foreach ( $query->fetchAll() as $line) {
            $list[] = new Course($line['title'], $line['content'], new DateTime($line['published']), $line['subject'], $line['id']);
        }
        return $list;
    }
    
    /**
     * Récupère un cours spécifique par son id
     * @param int $id l'id du cours à récupérer
     * @return Course|null une instance de Course ou null si pas de cours correspondant
     */
    public function findById(int $id):?Course{

        $connection = Database::getConnection();
        $query = $connection->prepare("SELECT * FROM course WHERE id=:id");
        $query->bindValue(':id', $id);
        $query->execute();

        foreach ( $query->fetchAll() as $line) {
            return new Course($line['title'], $line['content'], new DateTime($line['published']), $line['subject'], $line['id']);
        }
        return null;
    }

    public function persist(Course $course) {
        $connection = Database::getConnection();
        $query = $connection->prepare("INSERT INTO course (title,subject,content,published) VALUES (:title,:subject,:content,:published)");
        $query->bindValue(':title', $course->getTitle());
        $query->bindValue(':subject', $course->getSubject());
        $query->bindValue(':content', $course->getContent());
        $query->bindValue(':published', $course->getPublished()->format('Y-m-d H:i:s'));
        $query->execute();

        $course->setId($connection->lastInsertId());
    }
    public function update(Course $course) {
        $connection = Database::getConnection();
        $query = $connection->prepare("UPDATE course SET title=:title, content=:content, published=:published, subject=:subject WHERE id=:id");
        $query->bindValue(':title', $course->getTitle());
        $query->bindValue(':subject', $course->getSubject());
        $query->bindValue(':content', $course->getContent());
        $query->bindValue(':published', $course->getPublished()->format('Y-m-d H:i:s'));
        $query->bindValue(':id', $course->getId());
        $query->execute();

        
    }
}