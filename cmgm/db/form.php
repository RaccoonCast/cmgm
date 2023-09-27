<?php
function generateForm($data,$fields_array) {
    echo '<form method="post" action="process_form.php">';
    foreach ($data as $field => $value_of_field) {
        $form = $fields_array[$field];

        $input_type = isset($form['input_type']) ? $form['input_type'] : 'text';
        $class = isset($form['class']) ? $form['class'] : '';
        $id = isset($form['id']) ? $form['id'] : '';
        $inlineHtml = isset($form['inline-html']) ? $form['inline-html'] : '';
        $placeholder = isset($form['placeholder']) ? $form['placeholder'] : '';
        $visibility = !empty($form) ? $form : 'hidden';
        
    
    echo !empty($form['label']) ? '<label class="' . $form['label']['class'] . '">' . $form['label']['name'] . '</label>' : "";

    // All text & number inputs (lte1-9, nr1-4, etc)
    if ($visibility !== "hidden" && ($input_type == "text" OR $input_type == "number")) {
        echo '<input';
        echo ' type="' . $input_type . '" name="' . $field . '"';
        echo !empty($inlineHtml) ? ' ' . $inlineHtml . '' : '';
        echo !empty($class) ? ' class="' . $class . '"' : '';
        echo !empty($id) ? ' id="' . $id . '"' : '';
        echo !empty($value_of_field) ? ' value="' . $value_of_field . '"' : '';
        echo !empty($placeholder) ? ' placeholder="' . $placeholder . '"' : '';
        echo '>';
    }

    // Verified/Unverified + Carrier + Concealed/Unconcealed
    if ($input_type == 'select-dropdown') {
        echo '<select id="' . $form['id'] . '" name="' . $form['name'] . '" class="' . $form['class'] . '">';
        foreach ($form['options'] as $value => $option) {
            $option_name = isset($option['label']) ? $option['label'] : $option; // If label name is specified in an array in an array, use that. + support fallback
            $style = isset($option['style']) ? ' style="' . $option['style'] . '"' : '';
            $value = !empty($value) ? ' value="' . $value . '"' : '';
            echo "<option $value $style>$option_name</option>";
        }
        echo '</select>';
    }

    // Cell Site Type Dropdown
    if ($input_type == 'cellsite_type_dropdown') {
        echo '<select class="' . $form['class'] . '" id="' . $form['id'] . '" name="' . $form['name'] . '">';
        echo '<option style="display:none" value="">&nbsp;</option>';
        foreach ($form['options'] as $category => $subcategories) {
            echo PHP_EOL . '<optgroup label="' . $category . '">' . PHP_EOL;
            foreach ($subcategories as $sub_key => $sub_val) {
                echo '    <option class="subtype_' . str_replace('_', '-', strtolower($category)) . '" ';
                if (@$cellsite_type == $sub_key) echo 'selected ';
                echo 'value="' . $sub_key . '">' . $sub_val . '</option>' . PHP_EOL;
            }
            echo '</optgroup>' . PHP_EOL;
        }
        echo '</select>';
    }


    }
    echo '<input type="submit" value="Submit">';
    echo '</form>';
}
?>