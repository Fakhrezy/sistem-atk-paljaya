<?php
header('Content-Type: application/json');

// Example data for chart testing
$data = [
    'labels' => ['January', 'February', 'March', 'April', 'May'],
    'datasets' => [
        [
            'label' => 'Sample Data',
            'data' => [10, 20, 15, 30, 25],
            'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
            'borderColor' => 'rgba(75, 192, 192, 1)',

        ]
    ],
];

function logDebugInfo($message){
    error_log("[DEBUG] $message");
}
logDebugInfo("Debug chart data generated");

echo json_encode($data);

