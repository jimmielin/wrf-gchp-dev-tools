# Process for updating species list

1. Get `rsl.out.0000` at high debug level

2. Save to `rsl.out.0000.version` for reference.

3. Extract species list into `gc_v...` for reference. Sort by lines.

4. Diff against latest version.

5. **Manually** update `registry.chem` master species list

6. Extract lowercase, wrf-chem names from `registry.chem` into `spec.txt`.

7. Run script and update coupler (`wrfgc_convert_state_mod.F`) and `registry.chem` master list (compressed in `chem_opt==233`)

8. If the last **gas** index changed from `p_xylenes`, update `module_input_chem_data.F` with the correct **get_last_gas** index.

9. I don't recall what needs to be updated in `module_chem_utilities.F` now... 