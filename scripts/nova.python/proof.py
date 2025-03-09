import matplotlib.pyplot as plt

# Datos de ejemplo con muchas categorías
import matplotlib.pyplot as plt

# Datos de ejemplo (puedes agregar más valores)
categorias = ['Producto A', 'Producto B', 'Producto C', 'Producto D', 'Producto E', 'Producto F']
valores = [120, 85, 95, 130, 75, 110]

# Ordenar los datos de mayor a menor
categorias, valores = zip(*sorted(zip(categorias, valores), key=lambda x: x[1], reverse=False))

# Ajustar tamaño de la figura dinámicamente según la cantidad de datos
fig, ax = plt.subplots(figsize=(8, 0.5 * len(categorias) + 2))

# Crear gráfico de barras horizontales
ax.barh(categorias, valores, color='#34495E', edgecolor='black', height=0.6)

# Agregar valores al final de cada barra
for i, v in enumerate(valores):
    ax.text(v + max(valores) * 0.02, i, str(v), ha='left', va='center', fontsize=10, color='black')

# Mejoras en la presentación
ax.set_xlabel("Cantidad", fontsize=12)
ax.set_title("Rendimiento de Productos", fontsize=14, fontweight='bold')

# Quitar bordes innecesarios para un diseño más limpio
ax.spines['top'].set_visible(False)
ax.spines['right'].set_visible(False)

# Ajustar el espaciado
plt.tight_layout()

# Mostrar gráfico
plt.show()
