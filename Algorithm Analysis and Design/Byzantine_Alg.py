import matplotlib.pyplot as plt
import random
from collections import defaultdict

random.seed()

# Definizione delle variabili
r = 2**10  # Round
t = 1  # Maliziosi
p = 2 * t + 1  # Affidabili
rounds = defaultdict(int)  # Array di rounds
b = [0] * 4  # b (bit in trasmissione) di dimensione 4

# Prendo maj e considero solo il strettamente maggiore (non uguale)
def maj(v):
    c1 = sum(bit for bit in v)
    c0 = len(v) - c1

    return 1 if c1 > c0 else 0

# Ritorna il numero di occorrenze di maj
def tally(v):
    c1 = sum(bit for bit in v)
    c0 = len(v) - c1

    return c1 if c1 > c0 else c0

# Algoritmo MCByzantine utilizzando lo pseudo-codice fornito
def MCByzantine(r, p, maj, tally, rounds, b):
    for i in range(r):
        m = [[1, 1, 0, 0], [1, 1, 0, 0], [1, 1, 0, 1]]
        cround = 0

        while True:
            coin = random.randint(0, 1)
            cround += 1

            for j in range(p):
                mag = maj(m[j])
                tl = tally(m[j])
                b[j] = mag if tl >= p else coin

            for v in m:
                v[:] = b

            for j in range(p):
                m[j][-1] = 1 - b[j]

            if m[0][0] == m[0][1] and m[0][1] == m[0][2]:
                break

        rounds[cround] += 1
    return rounds

# Creazione Grafico 
def plot_graph(data, r):
    # Per fare il plot dei dati, li ordino
    sorted_data = dict(sorted(data.items()))

    x = list(range(1, max(sorted_data.keys())+1))
    y = [r/2**i for i in range(1, max(sorted_data.keys())+1)]

    # Plot dei dati
    plt.plot(x, y, marker='d', label='Dati Empirici', color='red')
    plt.plot(list(sorted_data.keys()), list(sorted_data.values()), marker='d', label='Dati Teorici', color='green')
    
    plt.legend()

    plt.title('MCByzantine')
    plt.xlabel('Round')
    plt.ylabel('Frequenza ')
    plt.grid(True)
    plt.show()

#Stampa del grafico
data = MCByzantine(r, p, maj, tally, rounds, b)
plot_graph(data, r)
