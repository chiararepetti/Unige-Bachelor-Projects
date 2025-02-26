import random
import time
import math
import matplotlib.pyplot as plt
import numpy as np


def scambia(v, i, j):
    v[i], v[j] = v[j], v[i]

def partizionaInPlace(v, l, r):
    p = l + random.randint(0, r - l)  # pivot casuale
    scambia(v, l, p)  # sposta il pivot all'inizio
    i = l + 1
    comp = r - l  # numero di confronti
    for j in range(l + 1, r + 1):
        if v[j] < v[l]:  # confronto con il pivot
            scambia(v, j, i)
            i += 1
    scambia(v, l, i - 1)  # sposta il pivot alla sua posizione finale
    return i - 1, comp

def qs(v, l, r):
    comp = 0
    if l < r:
        index, comp_part = partizionaInPlace(v, l, r)
        comp += comp_part
        comp += qs(v, l, index - 1)
        comp += qs(v, index + 1, r)
    return comp

def lv_quick_sort(v):
    random.seed(time.time())  # srand ha un costo non trascurabile: poiché basta chiamarla una sola volta all'interno del programma per fissare il seme della generazione pseudo-casuale, possiamo chiamarla in quickSortRandom prima di  qs(v, 0, len(v) - 1) e non chiamarla più!
    return qs(v, 0, len(v) - 1)
    
def run_quick_sort(sequence):
    n = len(sequence)
    return [lv_quick_sort(seq) for seq in (sequence[:] for _ in range(int(1e5)))]

def stat(comp):
    mean = sum(comp) / len(comp)
    variance = sum((x - mean) ** 2 for x in comp) / (len(comp) - 1)
    std_dev = math.sqrt(variance)
    return mean, variance, std_dev

S = 10**4
sequence = [random.randint(1, S) for _ in range(S)]

# Tempo di esecuzione
start_time = time.time()
comp = run_quick_sort(sequence)
end_time = time.time()
elapsed_time = end_time - start_time
print(f"Il tempo di esecuzione è {elapsed_time} secondi.")

# Calcolo delle statistiche
mean, variance, std_dev = stat(comp)
print("Valore medio dei confronti:", mean)
print("Varianza dei confronti:", variance)
print("Deviazione standard dei confronti:", std_dev)

# Istogramma
fig, ax = plt.subplots(figsize=(6.4*0.8, 4.8*0.8))
ax.hist(comp, bins=50, edgecolor='black', density=True)
ax.axvline(mean, color='r', linestyle='--', label='Media')
ax.set_title("Histogram Normalized")
ax.set_xlabel('comp')
ax.set_ylabel('Normalized Counts')
ax.legend()

# Calcolo i limiti superiori con le disuguaglianze di Markov e Chebyshev
markov_upper_bound_2 = mean / (2 * mean)
chebyshev_upper_bound_2 = variance / ((2 * mean - mean) ** 2)
markov_upper_bound_3 = mean / (3 * mean)
chebyshev_upper_bound_3 = variance / ((3 * mean - mean) ** 2)
# Frequenza empirica
empirical_frequency_2 = np.sum(np.array(comp) >= 2 * mean) / len(comp)
empirical_frequency_3 = np.sum(np.array(comp) >= 3 * mean) / len(comp)

print(f"Frequenza empirica per 2*mean: {empirical_frequency_2}")
print(f"Limite superiore con la disuguaglianza di Markov per 2*mean: {markov_upper_bound_2}")
print(f"Limite superiore con la disuguaglianza di Chebyshev per 2*mean: {chebyshev_upper_bound_2}")
print(f"Frequenza empirica per 3*mean: {empirical_frequency_3}")
print(f"Limite superiore con la disuguaglianza di Markov per 3*mean: {markov_upper_bound_3}")
print(f"Limite superiore con la disuguaglianza di Chebyshev per 3*mean: {chebyshev_upper_bound_3}")

# Calcolo il massimo numero di confronti e il rapporto con il valore atteso
max_comp = np.max(comp)
ratio = max_comp / mean
print(f"Il massimo dei confronti sono : {max_comp},\n esegue {ratio} volte il valore atteso dei confronti.")

plt.show()

