import numpy as np

# PARAMETRI
n = 100  
el = [-2, -1, 0, 1, 2]  
kv = [5, 10, 20]  
rounds = 100  

#FUNZIONI
def matrix_rnd(n, el):
    return np.random.choice(el, size=(n, n))

def MCMatrixMultiplicationVerifier(A, B, C, k):
    # Ripeto la verifica k volte
    for _ in range(k):  
        r = np.random.choice([0, 1], size=(n, 1))  
        s = np.dot(B, r)  # B*r
        t = np.dot(A, s)  # A*(B*r)
        u = np.dot(C, r)  # C*r
        # Se t == u, restituisco True
        if np.array_equal(t, u): 
            return True 
    return False  

results = {k_v: 0 for k_v in kv}  

A = matrix_rnd(n, el)

for _ in range(rounds): 
    # Matrice B aggiornata ad ogni round
    B = matrix_rnd(n, el)  
    C = np.dot(A, B)  
    # Aggiungo un unita' a C 
    C[28, np.random.randint(0, n)] += 1  
    # Verifico moltiplicazione matrici
    for k_v in kv:  
        if MCMatrixMultiplicationVerifier(A, B, C, k_v):  
            results[k_v] += 1  

# Stampo risultati
for k_v in kv:
    print(f'Per k={k_v}, MCMatrixMultiplicationVerifier ha rilevato {results[k_v]} errori in {rounds} round')
