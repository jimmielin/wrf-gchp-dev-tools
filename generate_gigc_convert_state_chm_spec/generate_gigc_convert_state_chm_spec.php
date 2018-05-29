<?php
/**
 * The WRF-GCHP Project
 *
 * Automatic spec generation for WRF-GC's "State Conversion Module",
 * registry and species information, given a list of WRF species
 * column-pasted from Registry.chem
 */

/**
 * Information regarding GEOS-Chem chemistry species.
 * Gases come first in WRF-Chem, then aerosols.
 */
$gigcSpecNotGas = array("AERI", "ASOAN", "BCPI", "BCPO", "BrSALA", "BrSALC", "DMS", "DST1", "DST2", "DST3", "DST4", "INDIOL", "IONITA", "ISALA", "ISALC", "ISN1OA", "ISN1OG", "ISOA1", "ISOA2", "ISOA3", "MONITA", "MSA", "NH4", "NIT", "NITS", "OCPI", "OCPO", "OPOA1", "OPOA2", "POA1", "POA2", "SALA", "SALC", "SO4", "SO4S", "SOAIE", "SOAGX", "SOAME", "SOAMG", "SOAP", "SOAS", "TSOA0", "TSOA1", "TSOA2", "TSOA3");

// $ hplin 5/12/2018 1333 $
$file = file_get_contents("spec.txt");
$data = explode("\n", $file);

$tmpGet = "";
$tmpSet = "";
$tmpRegistryGas = "";
$tmpRegistryNotGas = "";

echo "%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%\n";
echo "(c) 2018 Haipeng Lin <linhaipeng at pku dot edu dot cn>\n";
echo "%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%\n";
echo "Generating GIGCWRFChem Specs for gigc_convert_state_mod...\n";
echo "%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%\n";
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
	
	// Check if gas if yes put in front
	if(!in_array($gigcSpec, $gigcSpecNotGas))
		$tmpRegistryGas .= $wrfSpec . ",";
	else
		$tmpRegistryNotGas .= $wrfSpec . ",";
}

$tmpRegistryGas = substr($tmpRegistryGas, 0, strlen($tmpRegistryGas) - 1); // remove trailing comma
$tmpRegistryNotGas = substr($tmpRegistryNotGas, 0, strlen($tmpRegistryNotGas) - 1); // remove trailing comma
$tmpRegistry = "package   gchp                  chem_opt==233       -             chem:" . $tmpRegistryGas . "," . $tmpRegistryNotGas;

echo "Finished, note that gas registry is:\n";
echo $tmpRegistryGas;
echo "\n\nThe non-gas registry is:\n";
echo $tmpRegistryNotGas;
echo "\n\nYou may want to update module_input_chem_data.F & chem_utilities.F\n\n";

file_put_contents("GIGCWRFChem_GetSpec.txt", $tmpGet);
file_put_contents("GIGCWRFChem_SetSpec.txt", $tmpSet);
file_put_contents("GIGCWRFChem_RegistryPackage.txt", $tmpRegistry);
