#include "my_barrier.h"

/*
    NOTE SUL PROGRAMMA:
    Abbiamo implementato un semplice programma per testare che la barriera funzionasse.

    Nel nostro programma vogliamo che i thread lavorino su una stessa stringa e vogliamo che il risultato
    delle operazioni su quest'ultima risulti in una stringa composta da NUM_THREADS*"a" seguite da
    NUM_THREADS*"b".

    Ad esempio, se NUM_THREADS = 5, vogliamo che la stringa risultante sia uguale a "aaaaabbbbb".

    Questo programma si presta bene come test per le barriere poiché in questo modo i thread dopo
    aver scritto "a" sulla stringa comune, si aspettano e sincronizzano alla barriera per poi scrivere
    "b", in modo ordinato.

    Una volta compilato e mandato in esecuzione, il programma:
        1. Prima stamperà la stringa risultante SENZA l'utilizzo di barriere (chiamando without_barrier());
        2. In seguito stamperà la stringa risultante CON l'utilizzo di barriere (chiamando with_barrier()).
*/


#define NUM_THREADS 5

/* RISORSE CONDIVISE */

my_barrier barrier;

char shared_no_barrier[NUM_THREADS*2];
char shared_with_barrier[NUM_THREADS*2];

/* FUNZIONI DA IMPLEMENTARE */

unsigned int pthread_my_barrier_init(my_barrier *mb , unsigned int v){
    if (v==0){
        return -1;
    }

    mb->vinit = v;
    mb->val = v;

    pthread_mutex_init(&mb->lock, NULL);
    pthread_cond_init(&mb->varcond, NULL);

    return 0;
}

unsigned int pthread_my_barrier_wait(my_barrier *mb){
    pthread_mutex_lock(&mb->lock);
    mb->val--;

    if (mb->val != 0){
        pthread_cond_wait(&mb->varcond, &mb->lock);
    }
    else {
        mb->val = mb->vinit;
        pthread_cond_broadcast(&mb->varcond);
    }
    pthread_mutex_unlock(&mb->lock);
    return 0;
}


/* FUNZIONI PER TESTARE LA BARRIERA */

void *without_barrier(void *arg) {
    int id = *(int *)arg;

    sleep(rand()%3);
    strcat(shared_no_barrier, "a");

    sleep(rand()%3);
    strcat(shared_no_barrier, "b");

    return NULL;
}


void *with_barrier(void *arg) {
    int id = *(int *)arg;
    
    printf("Thread %d è arrivato alla barriera\n", id);
    sleep(rand()%3);
    strcat(shared_with_barrier, "a");

    if (pthread_my_barrier_wait(&barrier) != 0){
        printf("\nAn error has occurred while waiting in barrier!\n");
    }

    printf("Thread %d ha superato la barriera\n", id);
    sleep(rand()%3);
    strcat(shared_with_barrier, "b");

    return NULL;
}


/* MAIN */

int main(){
    
    pthread_t threads[NUM_THREADS];
    int thread_number[NUM_THREADS];
    
    // NO BARRIER
    for (int i = 0; i<NUM_THREADS; ++i){
        thread_number[i] = i;
        pthread_create(&threads[i], NULL, without_barrier, &thread_number[i]);
    }

    for (int i = 0; i < NUM_THREADS; ++i) {
        pthread_join(threads[i], NULL);
    }

    printf("\nStringa risultante dall'esecuzione SENZA BARRIERA: %s\n\n", shared_no_barrier);

    // WITH BARRIER
    printf("Inizio esecuzione con barriera...\n");
    if (pthread_my_barrier_init(&barrier, NUM_THREADS) != 0){
        printf("\nAn error has occurred during barrier initialization!\n");
    }

    for (int i = 0; i<NUM_THREADS; ++i){
        thread_number[i] = i;
        pthread_create(&threads[i], NULL, with_barrier, &thread_number[i]);
    }

    for (int i = 0; i < NUM_THREADS; ++i) {
        pthread_join(threads[i], NULL);
    }

    printf("\nStringa risultante dall'esecuzione CON BARRIERA: %s\n\n", shared_with_barrier);
    
    return 0;
}
