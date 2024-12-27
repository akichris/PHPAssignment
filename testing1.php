<?php

    $id = '';
    $item = '';
    $completed = '';
    $readyToStore = false;
    $readyToDelete = false;
    $readyToUpdate = false;

    // connecting to DB
    function connectToDB()
            {
                // create connection to DB todolist
                $conn = new mysqli("localhost", "root", "", "todolist");

                // check connection
                if ( $conn -> connect_error)
                    {
                        return false;
                        die ("Connection failed: " . $conn -> connect_error);
                    }
                
                return $conn;
            }
        
        $conn = connectToDB();

    // get input via post
    if  (   
            $_SERVER['REQUEST_METHOD'] === "POST" &&
            isset($_POST['submit']) &&
            isset($_POST['item'])  
        ) 
            {
                $item = htmlspecialchars($_POST['item']);
                $readyToStore = true;
            }

    // store the data
    if($readyToStore && $conn) 
        {
            
            // Connected successfully, insert data
                        
            $sql = "INSERT INTO list (item) VALUES ('$item')";
            
            if($conn->query($sql) === FALSE) 
                {
                    echo "Error: " . $sql. "<br>" . $conn->error;
                }   

        }

    // get delete via post
    if  (  $_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['deleteButton']) ) 
            {
                $id = intval($_POST['itemId']);
                $readyToDelete = true;
            }

    // delete the data
    if($readyToDelete && $conn) 
        {
            
            // Connected successfully, delete data
                        
            $sql = "DELETE FROM list WHERE id = ('$id')";
            
            if($conn->query($sql) === FALSE) 
                {
                    echo "Error: " . $sql. "<br>" . $conn->error;
                }   

        }

        // get update via post
        if  (  $_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['checkboxButton']) ) 
                {
                    $id = intval($_POST['itemId']);
                    $completed = intval($_POST['completedStatus']);
                    $readyToUpdate = true;
                }

        // update the data
        if($readyToUpdate && $conn) 
            {
                
                // Connected successfully, update data
                            
                $sql = "UPDATE list SET completed = $completed WHERE id = $id";
                
                if($conn->query($sql) === FALSE) 
                    {
                        echo "Error: " . $sql. "<br>" . $conn->error;
                    }   

            }

    

    
    // reading to data
    if($conn) 
        {
            // get the data
            $sql = "SELECT * FROM list ORDER BY created_at DESC";
            $result = $conn->query($sql);
            $postItem = "";

            if($result -> num_rows > 0) 
                {
                    while($row = $result->fetch_assoc()) 
                    {
                        // check completed
                        $classCheckboxCompleted = $row['completed'] ? "checkbox checked" : "checkbox";
                        $classItemCompleted = $row['completed'] ? "todo-text completed" : "todo-text";
                                        
                        // show the list
                        $postItem .= ' <form method="post" class="todo-item">
                                            <div class="todo-content">
                                                <input type = "hidden" name = "itemId" value = " '.$row['id'].'">
                                                <input type = "hidden" name = "completedStatus" value = "'.($row['completed'] ? 0 : 1).'">
                                                <div class="todo-content">
                                                    <button name = "checkboxButton" type = "submit" class = "'.$classCheckboxCompleted.'" name = "toggleCompleted">
                                                        <span class= "'.$classItemCompleted.'">'.$row['item'].'</span>
                                                    </button>
                                                </div>                                                
                                                <button name = "deleteButton" type = "submit" class="delete-button">
                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </form>';
                    }
                } else
                {
                    print "No to do item.";
                }

            // print the no of completed item
            $sqlNoOfCompletedItem = "SELECT COUNT(*) as countCompleted from list WHERE completed = 1";
            $resultOfNoOfCompletedItem= $conn->query($sqlNoOfCompletedItem);
            $noOfItem = "";

            if($resultOfNoOfCompletedItem) 
                {
                    $rowCompleted = $resultOfNoOfCompletedItem->fetch_assoc();
                    $rowCompleted ? $noOfCompletedItem = intval($rowCompleted['countCompleted']) : $noOfCompletedItem = 0 ;
                } else
                {
                    print "No to do item.";
                }

            // show the no of completed items
            $noOfItem .= ' <div class="progress">
                                <svg class="progress-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span> '.$noOfCompletedItem. '/' .$result -> num_rows. ' completed</span>
                            </div>';
        }

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Modern Todo List</title>
        <style>
            /* Reset and base styles */
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            }

            body {
                min-height: 100vh;
                background: linear-gradient(135deg, #f0f4ff 0%, #f5f7ff 100%);
                padding: 2rem 1rem;
            }

            /* Container styles */
            .container {
                max-width: 42rem;
                margin: 0 auto;
            }

            .todo-card {
                background: white;
                border-radius: 1rem;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                padding: 1.5rem;
            }

            /* Header styles */
            .header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 2rem;
            }

            .title {
                font-size: 1.875rem;
                font-weight: bold;
                color: #1f2937;
            }

            .progress {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                font-size: 0.875rem;
                color: #6b7280;
            }

            .progress-icon {
                color: #10b981;
                width: 1.25rem;
                height: 1.25rem;
            }

            /* Form styles */
            .todo-form {
                margin-bottom: 1.5rem;
            }

            .input-group {
                display: flex;
                gap: 0.5rem;
            }

            .todo-input {
                flex: 1;
                padding: 0.75rem 1rem;
                border: 1px solid #e5e7eb;
                border-radius: 0.5rem;
                font-size: 1rem;
                transition: all 0.2s;
            }

            .todo-input:focus {
                outline: none;
                border-color: #3b82f6;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
            }

            .add-button {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.75rem 1.5rem;
                background-color: #3b82f6;
                color: white;
                border: none;
                border-radius: 0.5rem;
                font-size: 1rem;
                cursor: pointer;
                transition: background-color 0.2s;
            }

            .add-button:hover {
                background-color: #2563eb;
            }

            /* Todo item styles */
            .todo-list {
                display: flex;
                flex-direction: column;
                gap: 0.75rem;
            }

            .todo-item {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 1rem;
                background: white;
                border: 1px solid #f3f4f6;
                border-radius: 0.5rem;
                transition: all 0.2s;
            }

            .todo-item:hover {
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            }

            .todo-content {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                flex-grow: 1;
            }

            .checkbox {
                width: 1.25rem;
                height: 1.25rem;
                border: 2px solid #d1d5db;
                border-radius: 50%;
                cursor: pointer;
                transition: all 0.2s;
            }

            .checkbox.checked {
                background-color: #10b981;
                border-color: #10b981;
                position: relative;
            }

            .checkbox.checked::after {
                content: "✓";
                position: absolute;
                color: white;
                font-size: 0.75rem;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
            }

            .todo-text {
                color: #374151;
                margin-left: 50px;
            }

            .todo-text.completed {
                color: #9ca3af;
                text-decoration: line-through;
            }

            .delete-button {
                opacity: 0;
                color: #ef4444;
                background: none;
                border: none;
                cursor: pointer;
                padding: 0.25rem;
                transition: all 0.2s;
            }

            .todo-item:hover .delete-button {
                opacity: 1;
            }

            .delete-button:hover {
                color: #dc2626;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="todo-card">
                <div class="header">
                    <h1 class="title">My Tasks</h1>
                    <?= $noOfItem ?>
                </div>

                <form class="todo-form" method = "post">
                    <div class="input-group">
                        <input name = "item" type="text" class="todo-input" placeholder="Add a new task...">
                        <button name = "submit" type="submit" class="add-button">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="8" x2="12" y2="16" />
                                <line x1="8" y1="12" x2="16" y2="12" />
                            </svg>
                            Add Task
                        </button>
                    </div>
                </form>

                <div class="todo-list">
                    <?= $postItem ?>
                </div>
            </div>
        </div>




    </body>
</html>