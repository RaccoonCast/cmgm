<?php
  $sql = "
    SELECT   
        TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(CONCAT(',', tags, ','), ',', numbers.n), ',', -1)) AS tag,  
        COUNT(*) AS tag_count  
    FROM db  
    CROSS JOIN (  
        SELECT 1 n UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5   
        UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10  
    ) numbers  
    WHERE CHAR_LENGTH(tags) - CHAR_LENGTH(REPLACE(tags, ',', '')) >= numbers.n - 1  
        AND tags IS NOT NULL   
        AND tags != ''  
        AND TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(CONCAT(',', tags, ','), ',', numbers.n), ',', -1)) != ''  
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
