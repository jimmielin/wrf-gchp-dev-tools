<?php
/**
 * The WRF-GCHP Project
 *
 * Generates deallocation routines for State_Chm_Mod
 * as part of moving USE-associated arrays to State_Chm derived type object.
 */

// $ hplin 5/12/2018 1333 $
$file = file_get_contents("spec.txt");
$data = explode("\n", $file);

$tmp = "";

echo "%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%\n";
echo "(c) 2018 Haipeng Lin <linhaipeng at pku dot edu dot cn>\n";
foreach($data as &$field_read) {
	$field = trim($field_read);

	$tmp .= "
    IF ( ASSOCIATED( State_Chm%" . $field . " ) ) THEN
       DEALLOCATE( State_Chm%" . $field . ", STAT=RC )
       CALL GC_CheckVar( 'State_Chm%" . $field . "', 3, RC )
       RETURN
    ENDIF
";
}

file_put_contents("Deallocs.txt", $tmp);