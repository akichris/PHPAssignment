<?php

    /*
        Use foreach loop to go through the three employees.
        Use array_sum() to sum the monthly salaries.
        Include the employee's name and their total annual salary in the output.
        Extra points: Show the average salary of all three employees combined.

    */


    $employees = 
        [
            [
                'name' => 'Alice',
                'monthly_salaries' => [3000, 3100, 2900, 3000, 3100, 3200, 3000, 3100,
                2900, 3000, 3100, 3200]
            ],
            [
                'name' => 'Bob',
                'monthly_salaries' => [2500, 2600, 2700, 2500, 2600, 2700, 2500, 2600,
                2700, 2500, 2600, 2700]
            ],
            [
                'name' => 'Charlie',
                'monthly_salaries' => [4000, 4100, 4200, 4300, 4400, 4500, 4000, 4100,
                4200, 4300, 4400, 4500]
            ]
        ];

$allStaffSalaries = 0;         // set initial 
$allStaffMonthlyAverageSalaries  = 0;               // set initial
$numberOfStaff = count($employees);

    foreach ($employees as $staff) 
        {
            $totalSalaries = 0;         // set initial 
 
            $totalSalaries += array_sum($staff['monthly_salaries']);
            $averageSalary = $totalSalaries / count($staff['monthly_salaries']);
            
            $allStaffSalaries += $totalSalaries; 
            $allStaffMonthlyAverageSalaries += $averageSalary;

            echo "{$staff['name']}'s total annual salary is $totalSalaries and monthly salaries average is $averageSalary. <br> <br>";

            $totalSalaries = 0;         // reset the totalSalaries

        }


    
    echo " $numberOfStaff staff total annual salary is $allStaffSalaries and monthly salaries average is $allStaffMonthlyAverageSalaries. <br>";


?>