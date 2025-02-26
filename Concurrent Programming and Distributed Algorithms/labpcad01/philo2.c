#include <stdio.h>
#include <unistd.h>
#include <stdlib.h>
#include <pthread.h>

#define NUMERO_FILOSOFI 5

/*  
    RISULTATI OTTENUTI SU 10 RUN: Il programma non è mai andato in deadlock!

    Questo programma contiene lo stesso codice di philo.c
    a cui è stato aggiunto l'algoritmo di Filter Lock per
    proteggere la sezione critica (i.e. acquisizione delle bacchette)
    introducendo meccanismi di mutua esclusione.
*/


/* RISORSE CONDIVISE */
pthread_mutex_t bacchette[NUMERO_FILOSOFI]; // Il mutex in bacchette[0] è alla sinistra del filosofo 0 e così via.

// Risorse per implementazione di FILTER LOCK.
int level[NUMERO_FILOSOFI];
int victim[NUMERO_FILOSOFI]; // victim[0] non verrà usata!


/* FILTER LOCK IMPLEMENTATION */

// Funzione che implementa l'algoritmo di attesa del Filter Lock:
// Un thread non va avanti se non è la vittima o se non c'è nessuno ad un livello superiore.
void filter_lock(int me){
    for (int j = 1; j<NUMERO_FILOSOFI; ++j){
        level[me] = j;
        victim[j] = me;

        bool wait = true;
        while(wait){
            wait = false;
                for (int k = 0; k<NUMERO_FILOSOFI; ++k){
                    if (k!= me && level[k] >= j && victim[j] == me){
                        wait = true;
                        break;
                    }
                }
        }
    }
}

void filter_unlock(int me){
    level[me] = 0;
}


/* FUNZIONE DI PARTENZA DEI THREAD */

void* vita_filosofo(void* id){
    
    int i = *(int*)id;
    int indice_destro = (i+1)%NUMERO_FILOSOFI;
    
    unsigned int counter = 0;
    while(counter < 5){
        printf("Filosofo %d: sta pensando\n", i);

        filter_lock(i);

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

        filter_unlock(i);
        
        printf("Filosofo %d: ha rilasciato le bacchette\n", i);
        
        counter++;
    }
    return NULL;
}


/* MAIN */

int main(){
    pthread_t filosofi_thread[NUMERO_FILOSOFI];
    int filosofi_id[NUMERO_FILOSOFI];

    // Inizializziamo i mutex E L'ARRAY LEVEL.
    for (int i = 0; i < NUMERO_FILOSOFI; ++i){
        level[i] = 0;
        if (pthread_mutex_init(&bacchette[i], NULL) != 0){
            printf("\nMutex initialization error!\n");
            exit(-1);
        }
    }

    for (int i = 0; i < NUMERO_FILOSOFI; ++i){
        filosofi_id[i] = i;
        if (pthread_create(&filosofi_thread[i], NULL, vita_filosofo, &filosofi_id[i]) != 0){
            printf("\nAn error occurred while creating a thread!\n");
            exit(-1);
        }
    }
    
    for (int i = 0; i < NUMERO_FILOSOFI; i++) {
        if (pthread_join(filosofi_thread[i], NULL) != 0){
            printf("\nAn error occurred while joining threads!\n");
            exit(-1);
        }
    }
    
    for (int i = 0; i < NUMERO_FILOSOFI; i++) {
        if (pthread_mutex_destroy(&bacchette[i]) != 0){
            printf("\nMutex destroy error!\n");
            exit(-1);
        }
    }
    return 0;
}