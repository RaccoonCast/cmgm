<?php
// Line 1
$fields_array = [
    'id' => [],
    'date_added' => [],
    'old_cellsite_type' => [],

    'status' => [
        'input_type' => 'select-dropdown',
        'label' => [
            "name" => "Type of cellsite",
            "class" => "cellsite_type_label"
        ],
        'id' => 'status',
        'name' => 'status',
        'class' => 'status_cw',
        "options" => [
            "" => [
                "label" => "&nbsp;",
                "style" => "display: none;"
            ],
            "verified" => "Verified",
            "unverified" => "Unverified"
            ]
        ],

    'carrier' => [ 
        'input_type' => 'select-dropdown',
        'id' => 'carrier',
        'name' => 'carrier',
        'class' => 'carrier_cw',
        "options" => [
            "Unknown" => "Unknown",
            "T-Mobile" => "T-Mobile",
            "ATT" => "AT&T",
            "Verizon" => "Verizon",
            "Sprint" => "Sprint",
            "Dish" => "Dish"
            ]
        ],

    'concealed' => [ // Status
        'input_type' => 'select-dropdown',
        'id' => 'concealed',
        'name' => 'concealed',
        'class' => 'concealed_cw',
        "options" => [
            "" => [
                "label" => "&nbsp;", 
                "style" => "display: none;"
            ],
            "true" => "Concealed",
            "false" => "Unconcealed"
            ]
        ],

    'cellsite_type' => [ // Cell Site Type
        'input_type' => 'cellsite_type_dropdown',
        'id' => 'cellsite_type',
        'name' => 'cellsite_type',
        'class' => 'cellsite_type_cw',
        "options" => $options
        ]
];

// Line 2
for ($i = 1; $i <= 9; $i++) {
    $fields_array["lte_$i"] = [
        'label' => ($i === 1) ? ['name' => 'LTE/NR IDs', 'class' => 'lte_nr_label'] : null,
        'input_type' => "number",
        'placeholder' => "LTE_$i",
        'name' => "lte_$i",
        'class' => 'lte_cw',
    ];
}

for ($i = 1; $i <= 9; $i++) {
    $fields_array["nr_$i"] = [
        'input_type' => 'number',
        'name' => "nr_$i",
        'class' => 'nr_cw',
        'placeholder' => "NR_$i"
    ];
}
?>