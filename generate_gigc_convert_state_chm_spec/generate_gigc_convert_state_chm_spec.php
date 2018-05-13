<?php
// $ hplin 5/12/2018 1333 $
$file = file_get_contents("spec.txt");
$data = explode("\r\n", $file);

$tmpGet = "";
$tmpSet = "";

echo "Generating GIGCWRFChem Specs for gigc_convert_state_mod...\n";
foreach($data as &$species) {
	$wrfSpec = trim($species);
	$gigcSpec = strtoupper($wrfSpec);

	switch($wrfSpec) {
		case "isoprene": $gigcSpec = "ISOP"; break;
		case "hcho": $gigcSpec = "CH2O"; break;
		case "ch3ooh": $gigcSpec = "MP"; break;
		case "benzene": $gigcSpec = "BENZ"; break;
		case "toluene": $gigcSpec = "TOLU"; break;
		case "xylenes": $gigcSpec = "XYLE"; break;
		case "limon": $gigcSpec = "LIMO"; break;
	}

	// v/v from ppmv is multiplied by 1.0e-6_fp
	$tmpGet .= "State_Chm%Species(II, JJ, k, IND_('{$gigcSpec}')) = chem(i, k, j, p_" . $wrfSpec . ") * 1.0e-6_fp\r\n";
	$tmpSet .= "chem(i, k, j, p_" . $wrfSpec . ") = State_Chm%Species(II, JJ, k, IND_('{$gigcSpec}')) * 1.0e+6_fp\r\n";
}

file_put_contents("GIGCWRFChem_GetSpec.txt", $tmpGet);
file_put_contents("GIGCWRFChem_SetSpec.txt", $tmpSet);