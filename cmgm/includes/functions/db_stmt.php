<?php
function db_stmt(mysqli $conn, string $sql, string $types = "", array $params = [])
{
    // Prepare the query
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    // Bind parameters if any
    if ($types !== "" && !empty($params)) {
        if (strlen($types) !== count($params)) {
            throw new Exception("Type string length does not match parameter count.");
        }

        $stmt->bind_param($types, ...$params);
    }

    // Execute
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }

    // If it's a SELECT then return mysqli_result
    if (stripos(trim($sql), "SELECT") === 0) {
        return $stmt->get_result();
    }

    // otherwise return the original statement
    return $stmt;
}
