# The WRF-GC Project
# This file parses species concentration sums in rsl.out.0000 for debugging purposes.
#
# Haipeng Lin <linhaipeng@pku.edu.cn>
# September 15, 2018

import re
import matplotlib.pyplot as plt

rsl = open("rsl.out.0000", "r")
rsl_txt = rsl.read()

regex = re.compile(r'Sums at L = 1[\r\n]+ O3:  ([0-9\.]+E[+-][0-9]+)[\r\n]+ CO:  ([0-9\.]+E[+-][0-9]+)[\r\n]+ NH3:  ([0-9\.]+E[+-][0-9]+)', re.M)
matches = re.finditer(regex, rsl_txt)
values = [(float(m.group(1)), float(m.group(2)), float(m.group(3))) for m in matches]

o3s = [v[0] for v in values]

plt.plot(o3s)
plt.ylabel("O3")
plt.show()

cos = [v[1] for v in values]
plt.plot(cos)
plt.ylabel("CO")
plt.show()

nh3s = [v[2] for v in values]
plt.plot(nh3s)
plt.ylabel("NH3 over Chemistry Timestep")
plt.show()
