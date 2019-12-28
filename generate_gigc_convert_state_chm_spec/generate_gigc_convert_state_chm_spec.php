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

// Extra registries used for two-way coupling
$registryExtra = "diag_so4_a1,diag_so4_a2,diag_so4_a3,diag_so4_a4,diag_nit_a1,diag_nit_a2,diag_nit_a3,diag_nit_a4,diag_nh4_a1,diag_nh4_a2,diag_nh4_a3,diag_nh4_a4,diag_ocpi_a1,diag_ocpi_a2,diag_ocpi_a3,diag_ocpi_a4,diag_ocpo_a1,diag_ocpo_a2,diag_ocpo_a3,diag_ocpo_a4,diag_bcpi_a1,diag_bcpi_a2,diag_bcpi_a3,diag_bcpi_a4,diag_bcpo_a1,diag_bcpo_a2,diag_bcpo_a3,diag_bcpo_a4,diag_seas_a1,diag_seas_a2,diag_seas_a3,diag_seas_a4,diag_dst_a1,diag_dst_a2,diag_dst_a3,diag_dst_a4,diag_soas_a1,diag_soas_a2,diag_soas_a3,diag_soas_a4,diag_so4_cw1,diag_so4_cw2,diag_so4_cw3,diag_so4_cw4,diag_nit_cw1,diag_nit_cw2,diag_nit_cw3,diag_nit_cw4,diag_nh4_cw1,diag_nh4_cw2,diag_nh4_cw3,diag_nh4_cw4,diag_ocpi_cw1,diag_ocpi_cw2,diag_ocpi_cw3,diag_ocpi_cw4,diag_ocpo_cw1,diag_ocpo_cw2,diag_ocpo_cw3,diag_ocpo_cw4,diag_bcpi_cw1,diag_bcpi_cw2,diag_bcpi_cw3,diag_bcpi_cw4,diag_bcpo_cw1,diag_bcpo_cw2,diag_bcpo_cw3,diag_bcpo_cw4,diag_seas_cw1,diag_seas_cw2,diag_seas_cw3,diag_seas_cw4,diag_dst_cw1,diag_dst_cw2,diag_dst_cw3,diag_dst_cw4,diag_soas_cw1,diag_soas_cw2,diag_soas_cw3,diag_soas_cw4,diag_water_a1,diag_water_a2,diag_water_a3,diag_water_a4,diag_num_a1,diag_num_a2,diag_num_a3,diag_num_a4,diag_num_cw1,diag_num_cw2,diag_num_cw3,diag_num_cw4";

// $ hplin 5/12/2018 1333 $
$file = file_get_contents("spec.txt");
$data = explode("\n", $file);

$tmpGet = "";
$tmpSet = "";
$tmpIdxArray = "integer :: ";
$tmpIdxLookup = "";

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
	$tmpGet .= "State_Chm%Species(II, JJ, k, gi_" . strtolower($gigcSpec) . ") = chem(i, k, j, p_" . $wrfSpec . ") * 1.0e-6_fp\r\n";
	$tmpSet .= "chem(i, k, j, p_" . $wrfSpec . ") = State_Chm%Species(II, JJ, k, gi_" . strtolower($gigcSpec) . ") * 1.0e+6_fp\r\n";
	$tmpIdxArray .= "gi_" . strtolower($gigcSpec) . ", ";
	$tmpIdxLookup .= "gi_" . strtolower($gigcSpec) . " = IND_('{$gigcSpec}')\r\n";
	
	// Check if gas if yes put in front
	if(!in_array($gigcSpec, $gigcSpecNotGas))
		$tmpRegistryGas .= $wrfSpec . ",";
	else
		$tmpRegistryNotGas .= $wrfSpec . ",";
}

$tmpRegistryGas = substr($tmpRegistryGas, 0, strlen($tmpRegistryGas) - 1); // remove trailing comma
$tmpRegistryNotGas = substr($tmpRegistryNotGas, 0, strlen($tmpRegistryNotGas) - 1); // remove trailing comma
$tmpIdxArray = substr($tmpIdxArray, 0, strlen($tmpIdxArray) - 1); // remove trailing comma

$tmpRegistry = "package   gchp                  chem_opt==233       -             chem:" . $tmpRegistryGas . "," . $tmpRegistryNotGas . "," . $registryExtra;

echo "Finished, note that gas registry is:\n";
echo $tmpRegistryGas;
echo "\n\nThe non-gas registry is:\n";
echo $tmpRegistryNotGas;
echo "\n\nYou may want to update module_input_chem_data.F & chem_utilities.F\n\n";

file_put_contents("GIGCWRFChem_GetSpec.txt", $tmpGet);
file_put_contents("GIGCWRFChem_SetSpec.txt", $tmpSet);
file_put_contents("GIGCWRFChem_IdxSpec.txt", $tmpIdxArray);
file_put_contents("GIGCWRFChem_IdxLookupSpec.txt", $tmpIdxLookup);
file_put_contents("GIGCWRFChem_RegistryPackage.txt", $tmpRegistry);
