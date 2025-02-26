#include <stdio.h>
#include <unistd.h>
#include <stdlib.h>
#include <pthread.h>

#define NUMERO_FILOSOFI 5


/* RISORSE CONDIVISE */

// - bacchette[i] è alla sinistra del filosofo i, 
// - bacchette[i+1] è alla destra del filosof i (e così via...).
pthread_mutex_t bacchette[NUMERO_FILOSOFI]; 


/* FUNZIONE DI PARTENZA DEI THREAD */

void* vita_filosofo(void* id){
    
    int i = *(int*)id; // cast da void* a int
    int indice_destro = (i+1)%NUMERO_FILOSOFI;
    
    unsigned int counter = 0;
    while(counter < 5){
        printf("Filosofo %d: sta pensando\n", i);

        // Il filosofo i prende la bacchetta sinistra.
        pthread_mutex_lock(&bacchette[i]);
        printf("Filosofo %d: ha preso la bacchetta sinistra\n", i);

        sleep(rand()%3); // Rallentiamo l'esecuzione per causare deadlock.

        // Il filosofo i prende la bacchetta destra.
        pthread_mutex_lock(&bacchette[indice_destro]);
        printf("Filosofo %d: ha preso la bacchetta destra\n", i);

        printf("Filosofo %d: sta mangiando\n", i);

        // Il filosof i rilascia le bacchette.
        pthread_mutex_unlock(&bacchette[i]);
        pthread_mutex_unlock(&bacchette[indice_destro]);
        printf("Filosofo %d: ha rilasciato le bacchette\n", i);
        counter++;
    }
    return NULL;
}


/* MAIN */

int main(){
    int filosofi_id[NUMERO_FILOSOFI];
    pthread_t filosofi_thread[NUMERO_FILOSOFI];

    // Inizializziamo i mutex.
    for (int i = 0; i < NUMERO_FILOSOFI; ++i){
        if (pthread_mutex_init(&bacchette[i], NULL) != 0){
            printf("\nMutex initialization error!\n");
            exit(-1);
        }
    }

    // Creiamo i thread che inizieranno l'esecuzione dalla funzione vita_filosofo().
    for (int i = 0; i < NUMERO_FILOSOFI; ++i){
        filosofi_id[i] = i;
        
        if (pthread_create(&filosofi_thread[i], NULL, vita_filosofo, &filosofi_id[i]) != 0){
            printf("\nAn error occurred while creating a thread!\n");
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
    
    // Distruzione mutex.
    for (int i = 0; i < NUMERO_FILOSOFI; i++) {
        if (pthread_mutex_destroy(&bacchette[i]) != 0){
            printf("\nMutex destroy error!\n");
            exit(-1);
        }
    }
    return 0;
}
