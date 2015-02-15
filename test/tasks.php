<?php
/**
* Some Tasks.
*/

/**
 * Delete and insert some scores records.
 *
 * @param object
 *      The database instance. 
 */
function task_clean_database($db){
    $db->exec('DELETE FROM "score";'); 
    $db->exec('DELETE FROM "sqlite_sequence" WHERE name = "score";');     
    $db->exec('DELETE FROM "keys";'); 
    $db->exec('DELETE FROM "sqlite_sequence" WHERE name = "keys";'); 
    
    $db->exec('INSERT INTO "score" ("id","player_name","score","sqltime") 
               VALUES (NULL,"evariste",5025,"2015-01-14 01:01:11"),
                      (NULL,"pierre",108,"2015-01-14 01:01:30");'); 
}

/**
 * Display the test results.
 * 
 * @param object Test The test instance.
 */
function task_display_results($test){    
    $printResult = '<p style="color:%s"><b>%s</b></p>';   
    
    $pass = $fail = 0;
    foreach ($test->results() as $result) {
            echo sprintf($printResult,"blue","Test ".$result['text']);
            if ($result['status']){
                echo sprintf($printResult,"green","Pass");
                $pass ++;
            } else {                
                echo sprintf($printResult,"red",'Fail ('.$result['source'].')');
                $fail ++;
            }
    }
    
    echo "<hr>";
    echo sprintf($printResult,"green","Passed: ".$pass);
    echo sprintf($printResult,"red","Failed: ".$fail);    
}