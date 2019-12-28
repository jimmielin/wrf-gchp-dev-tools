# The WRF-GC Project
# This file parses species concentration sums in rsl.out.0000 for debugging purposes.
#
# Haipeng Lin <linhaipeng@pku.edu.cn>
# September 15, 2018

import re

rsl = open("rsl.out.0000", "r")
rsl_txt = rsl.read()

regex = re.compile(r'grid%tauaer1\(162, 2, 110\)[ \t]+([0-9\.]+E[+-][0-9]+)[\r\n ]+grid%extaer1\(162, 2, 110\)[ \t]+([0-9\.]+E[+-][0-9]+)[\r\n ]+grid%gaer1\(162, 2, 110\)[ \t]+([0-9\.]+)[\r\n ]+grid%waer1\(162, 2, 110\)[ \t]+([0-9\.]+)[\r\n ]+grid%bscoef1\(162, 2, 110\)[ \t]+([0-9\.]+E[+-][0-9]+)[\r\n ]+grid%extaerlw1\(162, 2, 110\)[ \t]+([0-9\.]+E[+-][0-9]+)[\r\n ]+grid%tauaerlw1\(162, 2, 110\)[ \t]+([0-9\.]+E[+-][0-9]+)', re.M)
matches = re.finditer(regex, rsl_txt)
values = [(float(m.group(1)), float(m.group(2)), float(m.group(3)), float(m.group(4)), float(m.group(5)), float(m.group(6)), float(m.group(7))) for m in matches]

print("tauaer1 time series")
for i in range(len(values)):
    print(values[i][0])

print("extaer1 time series")
for i in range(len(values)):
    print(values[i][1])

print("gaer1 time series")
for i in range(len(values)):
    print(values[i][2])

print("waer1 time series")
for i in range(len(values)):
    print(values[i][3])

print("bscoef1 time series")
for i in range(len(values)):
    print(values[i][4])

print("extaerlw1 time series")
for i in range(len(values)):
    print(values[i][5])

print("tauaerlw1 time series")
for i in range(len(values)):
    print(values[i][6])