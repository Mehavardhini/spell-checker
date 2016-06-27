<?php
error_reporting(E_ALL);
require 'db.php';

function spellcheck($word) {
    $output = array();
    $word = mysql_real_escape_string($word);
    $words = mysql_query("SELECT  `word` 
FROM  `english` 
WHERE LEFT(  `word` , 2 ) =  '" . substr($word, 0, 2) . "'
LIMIT 0 , 30");
    $words_row = mysql_fetch_assoc($words);
    while (($words_row = mysql_fetch_assoc($words)) !== false && in_array($word, $words_row) == false) {
        similar_text($word, $words_row['word'], $percent);
        if ($percent > 0) {
            $output[] = $words_row['word'];
        }
    }
    return (empty($output)) ? false : $output;
}

if (isset($_GET['word']) && trim($_GET['word']) !== null) {
    $word = $_GET['word'];
    $spellcheck = spellcheck($word);

    if ($spellcheck !== false) {
        echo '<pre>' . print_r($spellcheck, true) . '</pre>';
    } else {
        echo '<p>' . $word . ' spelled correctly, or no suggestion found';
    }
}
?>

<form action="" method="GET">
    Check single word spelling
    <input type="text" name="word"/>
    <input type="submit" value="Check"/>

</form>