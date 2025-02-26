#include "my_semaphore.h"

#define N 10
#define C 10

pthread_mutex_t lock_contatore_passeggeri = PTHREAD_MUTEX_INITIALIZER;
int contatore_passeggeri = 0;

my_semaphore bus_pieno; // semaforo che blocca i passeggeri se il bus è pieno.
my_semaphore bus_vuoto; // semaforo che blocca il bus se i posti non sono tutti occupati.
my_semaphore entrata; // semaforo che controlla che C passeggeri entrino nel bus.
my_semaphore uscita; // semaforo che controlla che C passeggeri abbandonino il bus a giro finito.

void *bus(void *arg) {
    while (1) {
        printf("Il bus apre le porte. Possono entrare %d passeggeri.\n", C);
        sleep(2);
        my_sem_wait(&bus_pieno); // il bus aspetta che tutti i C passeggeri siano entrati

        printf("Il bus e' pieno, si parte.\n");
        sleep(3);

        // Finisce il giro
        printf("Il giro e' finito, i %d passeggeri devono uscire.\n", contatore_passeggeri);
        sleep(2);

        for (int i = 0; i < C; ++i){
            my_sem_signal(&uscita); // lasciamo uscite dal bus C thread
        }

        my_sem_wait(&bus_vuoto); // il bus aspetta che tutti i C passeggeri siano usciti

        printf("Il bus e' adesso vuoto.\n");

        for (int i = 0; i < C; ++i){
          my_sem_signal(&entrata); // lasciamo uscire dal bus C thread
        }
    }
}

void *passeggero(void *arg) {
    int id_passeggero = *(int *)arg;

    while (1) {
        my_sem_wait(&entrata); // i thread aspettano per entrare

        pthread_mutex_lock(&lock_contatore_passeggeri);
        contatore_passeggeri++;
        printf("%d passeggeri sono entrati nel bus...\n", contatore_passeggeri);
        sleep(1);
        // l'ultimo thread che entra sveglia il bus in attesa di essere pieno.
        if (contatore_passeggeri == C){
            my_sem_signal(&bus_pieno);
        }
        pthread_mutex_unlock(&lock_contatore_passeggeri);

        my_sem_wait(&uscita);

        pthread_mutex_lock(&lock_contatore_passeggeri);
        contatore_passeggeri--;
        printf("Rimangono ancora %d passeggeri sul bus che devono ancora uscire.\n", contatore_passeggeri);
        sleep(1);
        if (contatore_passeggeri == 0){ //l'ultimo thread ad uscire segnala al bus che tutti sono usciti.
            my_sem_signal(&bus_vuoto);
        }
        pthread_mutex_unlock(&lock_contatore_passeggeri);
    }
}

int main() {
    pthread_t thread_bus;
    pthread_t thread_passeggeri[N];
    int id_passeggero[N];

    my_sem_init(&entrata, C);
    my_sem_init(&bus_pieno, 0);
    my_sem_init(&bus_vuoto, 0);
    my_sem_init(&uscita, 0);

    pthread_create(&thread_bus, NULL, bus, NULL);
    for (int i = 0; i < N; ++i) {
        id_passeggero[i] = i+1;
        pthread_create(&thread_passeggeri[i], NULL, passeggero, &id_passeggero[i]);
    }

    // Wait dei thread.
    pthread_join(thread_bus, NULL);
    for (int i = 0; i < N; i++) {
        if (pthread_join(thread_passeggeri[i], NULL) != 0){
            printf("\nAn error occurred while joining threads!\n");
            exit(-1);
        }
    }
    pthread_mutex_destroy(&lock_contatore_passeggeri);
    my_sem_destroy(&entrata);
    my_sem_destroy(&uscita);
    my_sem_destroy(&bus_vuoto);
    my_sem_destroy(&bus_pieno);
    return 0;
}
