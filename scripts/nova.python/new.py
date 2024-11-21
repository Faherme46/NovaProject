import os
s = 'Pingüino: Málaga es una ciudad fantástica y en Logroño me pica el... moño ÁÉÍÓÚÜ'
a,b = 'áéíóúüñÁÉÍÓÚÜ','aeiouunAEIOUU'
trans = str.maketrans(a,b)

print(s.translate(trans))
