#include "my_semaphore.h"

// Soluzione al problema dei 5 filosofi con utilizzo di my_semaphore.
#define NUMERO_FILOSOFI 5

my_semaphore bacchette[NUMERO_FILOSOFI];
my_semaphore ticket;

// Funzione di partenza dei filosofi
void* vita_filosofo(void* id){
    int i = *(int*)id; // cast da void* a int
    int indice_destro = (i+1)%NUMERO_FILOSOFI;

    unsigned int counter = 0; // per limitare i cicli.
    while(counter<5){
        printf("Filosofo %d: sta pensando\n", i);
        sleep(2);

        // Il filosofo prende le bacchette e mangia.
        my_sem_wait(&ticket);
        my_sem_wait(&bacchette[i]);
        printf("Filosofo %d: ha preso la bacchetta sinistra\n", i);
        sleep(3);
        my_sem_wait(&bacchette[indice_destro]);
        printf("Filosofo %d: ha preso la bacchetta destra\n", i);
        printf("Filosofo %d: sta mangiando\n", i);
        sleep(2);

        // I filosofi rilasciano le bacchette.
        my_sem_signal(&bacchette[i]);
        my_sem_signal(&bacchette[indice_destro]);
        my_sem_signal(&ticket);
        printf("Filosofo %d: ha rilasciato le bacchette\n", i);
        counter++;
    }
    return NULL;
}

int main() {
    int filosofi_id[NUMERO_FILOSOFI];
    pthread_t filosofi_thread[NUMERO_FILOSOFI];
    my_sem_init(&ticket, NUMERO_FILOSOFI-1); // ticket.V=M con M<NUM_FILOSOFI.

    for (int i = 0; i < NUMERO_FILOSOFI; ++i){
        if (my_sem_init(&bacchette[i], NUMERO_FILOSOFI) != 0){
            printf("\nmy_semaphore initialization error!\n");
            exit(-1);
        }
    }
    // Creiamo i thread che inizieranno l'esecuzione dalla funzione vita_filosofo().
    for (int i = 0; i < NUMERO_FILOSOFI; ++i){
        filosofi_id[i] = i;

        if (pthread_create(&filosofi_thread[i], NULL, vita_filosofo, &filosofi_id[i]) != 0){
            printf("\nAn error occurred while creating threads!\n");
            exit(-1);
        }
    }
    // Wait dei thread.
    for (int i = 0; i < NUMERO_FILOSOFI; i++) {
        if (pthread_join(filosofi_thread[i], NULL) != 0){
            printf("\nAn error occurred while joining threads!\n");
            exit(-1);
        }
    }
    // Distruzione semafori.
    for (int i = 0; i < NUMERO_FILOSOFI; i++) {
        if (my_sem_destroy(&bacchette[i]) != 0){
            printf("\nmy_semaphore destroy error!\n");
            exit(-1);
        }
    }
    my_sem_destroy(&ticket);
    return 0;
}