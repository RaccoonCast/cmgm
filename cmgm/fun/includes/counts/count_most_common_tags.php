<?php
  $sql = "
WITH RECURSIVE split AS (
    SELECT
        id,                       
        TRIM(SUBSTRING_INDEX(tags, ',', 1)) AS tag,
        SUBSTRING(tags, LENGTH(SUBSTRING_INDEX(tags, ',', 1)) + 2) AS rest
    FROM db
    WHERE tags IS NOT NULL AND $db_vars
      AND tags <> ''

    UNION ALL

    SELECT
        id,
        TRIM(SUBSTRING_INDEX(rest, ',', 1)) AS tag,
        SUBSTRING(rest, LENGTH(SUBSTRING_INDEX(rest, ',', 1)) + 2) AS rest
    FROM split
    WHERE rest <> ''
)
SELECT
    tag,
    COUNT(*) AS tag_count
FROM split
WHERE tag <> ''
GROUP BY tag
ORDER BY tag_count DESC
LIMIT $limit;
  ";

  $result = $conn->query($sql);

    
if (!isset($json_flag)) echo "<h3>Most Common Tags</h3>";  
  
while ($row = $result->fetch_assoc()) {  
    $tag = $row['tag'];  
    $tag_for_url = $current_url . "&tags=" . urlencode($tag);  
    $url = '<a href="'. $tag_for_url.'">'.$tag.'</a>';  
    $plural_namething = ($row["tag_count"] > 1) ? " records" : " record";  
      
    if (isset($_GET['percents_view'])) {  
        echo getPercent($row["tag_count"]) . " with tag " . $url . "<br>";  
    } elseif (isset($json_flag)) {  
        $json_array[$tag] = $row["tag_count"];  
    } else {  
        echo $row["tag_count"] . $plural_namething . " with tag " . $url . "<br>";  
    }  
}  

  echo (!isset($json_flag)) ? "<br>" : "";
?>
