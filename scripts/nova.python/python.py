import hid
import ctypes

h = hid.device()
h.open(0x10C4, 0x1819)

print()
1902 21792644 UYEA