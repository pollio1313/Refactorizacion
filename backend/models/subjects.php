<?php
function getAllSubjects($conn) 
{
    $sql = "SELECT * FROM subjects";
    return $conn->query($sql);
}

function getSubjectById($conn, $id) 
{
    $sql = "SELECT * FROM subjects WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result();
}

function createSubject($conn, $name) 
{
    $sql = "INSERT INTO subjects (name) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $name);
    return $stmt->execute();
}

function updateSubject($conn, $id, $name) 
{
    $sql = "UPDATE subjects SET name = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $name, $id);
    return $stmt->execute();
}

function deleteSubject($conn, $id) 
{
    // Verificar si la materia está asignada a estudiantes
    $check = $conn->prepare("SELECT COUNT(*) FROM students_subjects WHERE subject_id = ?");
    $check->bind_param("i", $id);
    $check->execute();
    $check->bind_result($count);
    $check->fetch();
    $check->close();

    if ($count > 0) {
        return false; // La materia tiene estudiantes asignados
    }

    // Si no tiene relaciones, eliminar
    $stmt = $conn->prepare("DELETE FROM subjects WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}
?>
