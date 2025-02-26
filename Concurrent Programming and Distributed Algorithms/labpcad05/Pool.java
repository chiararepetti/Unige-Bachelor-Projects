import java.util.concurrent.Semaphore;

class Pool {
    // Numero di spogliatoi e armadietti
    private static final int NS = 3;  
    private static final int NC = 10; 
    private final Semaphore spogliatoi = new Semaphore(NS, true);
    private final Semaphore armadietti = new Semaphore(NC, true);

    public void UsePool(int clientId) throws InterruptedException {
        // (a): Prende la chiave di uno spogliatoio
        spogliatoi.acquire();
        System.out.println("Cliente " + clientId + " ha preso la chiave dello spogliatoio");

        // (b): Prende la chiave di un armadietto
        armadietti.acquire();
        System.out.println("Cliente " + clientId + " ha preso la chiave dell'armadietto");

        // (c): Si cambia nello spogliatoio
        System.out.println("Cliente " + clientId + " si sta cambiando nello spogliatoio");

        // (d): Libera lo spogliatoio
        spogliatoi.release();
        System.out.println("Cliente " + clientId + " ha liberato lo spogliatoio");

        // (e): Mette i suoi vestiti nell'armadietto
        System.out.println("Cliente " + clientId + " ha messo i suoi vestiti nell'armadietto");

        // (f): Rida la chiave dello spogliatoio
        System.out.println("Cliente " + clientId + " ha ridato la chiave dello spogliatoio");

        // (g): Nuota (tenendosi la chiave del armadietto);
        System.out.println("Cliente " + clientId + " sta nuotando");
        // Sleep per il tempo che il cliente sta nuotando
        Thread.sleep(1000);

        // (h): Prende la chiave di un spogliatoio
        spogliatoi.acquire();
        System.out.println("Cliente " + clientId + " ha preso di nuovo la chiave dello spogliatoio");

        // (i): Ricupera i suoi vestiti nell'armadietto
        System.out.println("Cliente " + clientId + " sta recuperando i suoi vestiti dall'armadietto");

        // (j): Si riveste nello spogliatoio
        System.out.println("Cliente " + clientId + " si sta rivestendo nello spogliatoio");
        // Sleep per il tempo che il cliente si sta rivestendo
        Thread.sleep(1000);

        // (k): Libera lo spogliatoio
        System.out.println("Cliente " + clientId + " ha liberato di nuovo lo spogliatoio");

        // (l): Rida le chiavi dello spogliatoio e dell'armadietto
        spogliatoi.release();
        armadietti.release();
        System.out.println("Cliente " + clientId + " ha ridato la chiave dello spogliatoio e del armadietto");
    }
}