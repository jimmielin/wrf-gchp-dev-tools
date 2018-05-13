# wrf-gchp-dev-tools
Development Tools for the WRF-GCHP Coupled Model Project

This repository contains some quick-and-dirty automation and debugging tools useful for the development of the `WRF-GCHP` coupled model project.
Tools are written in whatever language that is most convenient at that time, so they are a mashup of Python, Ruby, PHP, Haskell, ... or whatever.

## Tools

- `generate_gigc_convert_state_chm_spec`: Generates the `State_Chm%Species` to WRF scalar `chem` conversions when given a list of WRF variables from `Registry.chem` of `WRF-GCHP`. These are to be used in the `gigc_convert_state_mod.F` routines `GIGC_Get_WRF` and `GIGC_Set_WRF`.