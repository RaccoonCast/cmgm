<?php

function fetchFromDb(mysqli $conn, string $asn): ?array
{
  try {
    // enable exceptions on error
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $stmt = $conn->prepare("SELECT * FROM faa WHERE asn = ?");
    $stmt->bind_param("s", $asn);
    $stmt->execute();

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $stmt->close();
    return $row !== null ? $row : null;
  } catch (mysqli_sql_exception $e) {
    error_log("fetchFromDb error: " . $e->getMessage());
    return null;
  }
}

function addToDb(mysqli $conn, string $asn, string $json): bool
{
  try {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    // Re‑encode while stripping invalid UTF8
    $decoded = json_decode($json, true, 512, JSON_INVALID_UTF8_SUBSTITUTE);
    $sanitized_json = json_encode($decoded, JSON_UNESCAPED_UNICODE);

    $stmt = $conn->prepare(
      "INSERT INTO cmgm.faa (
        last_updated,
        asn,
        `data`
      ) VALUES (
          NOW(),
          ?,
          ?
      )
      ON DUPLICATE KEY UPDATE
        last_updated = NOW(),
        `data` = ?"
    );
    // bind asn, json, json (for updates)
    $stmt->bind_param("sss", $asn, $sanitized_json, $sanitized_json);
    $stmt->execute();

    $stmt->close();
    return true;
  } catch (mysqli_sql_exception $e) {
    error_log("addToDb error: " . $e->getMessage());
    return false;
  }
}
