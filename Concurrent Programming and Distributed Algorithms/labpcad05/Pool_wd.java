import java.util.concurrent.Semaphore;

public class Pool_wd {
    // Numero di spogliatoi e armadietti
    private static final int NS = 3;  
    private static final int NC = 10; 
    private final Semaphore spogliatoi = new Semaphore(NS, true);
    private final Semaphore armadietti = new Semaphore(NC, true);

    public void UsePool(int clientId) throws InterruptedException {
        while (true) {
            if (spogliatoi.tryAcquire()) {
                try {
                    if (armadietti.tryAcquire()) {
                        try {
                            // (a): Prende la chiave di uno spogliatoio
                            System.out.println("Cliente " + clientId + " ha preso la chiave dello spogliatoio");

                            // (b): Prende le chiavi di un armadietto
                            System.out.println("Cliente " + clientId + " ha preso la chiave dell'armadietto");

                            // (c): Si cambia nello spogliatoio
                            System.out.println("Cliente " + clientId + " si sta cambiando nello spogliatoio");
                            Thread.sleep(1000); // Simula il tempo di cambio

                            // (d): Libera lo spogliatoio
                            System.out.println("Cliente " + clientId + " ha liberato lo spogliatoio");

                            // (e): Mette i suoi vestiti nell'armadietto
                            System.out.println("Cliente " + clientId + " ha messo i suoi vestiti nell'armadietto");

                            // (f): Rida la chiave dello spogliatoio
                            spogliatoi.release();
                            System.out.println("Cliente " + clientId + " ha ridato la chiave dello spogliatoio");

                            // (g): Nuota
                            System.out.println("Cliente " + clientId + " sta nuotando");
                            Thread.sleep(2000); // Simula il tempo di nuoto

                            // (h): Prende la chiave di un spogliatoio
                            spogliatoi.acquire();
                            System.out.println("Cliente " + clientId + " ha preso di nuovo la chiave dello spogliatoio");

                            // (i): Ricupera i suoi vestiti nell'armadietto
                            System.out.println("Cliente " + clientId + " sta recuperando i suoi vestiti dall'armadietto");

                            // (j): Si riveste nello spogliatoio
                            System.out.println("Cliente " + clientId + " si sta rivestendo nello spogliatoio");
                            Thread.sleep(1000); // Simula il tempo per rivestirsi

                            // (k): Libera lo spogliatoio
                            System.out.println("Cliente " + clientId + " ha liberato di nuovo lo spogliatoio");

                            // (l): Rida le chiave dello spogliatoio e del armadietto
                            spogliatoi.release();
                            armadietti.release();
                            System.out.println("Cliente " + clientId + " ha ridato la chiave dello spogliatoio e del armadietto");
                            break;
                        } 
                        finally{
                            armadietti.release(); // Assicurarsi che la chiave dell'armadietto venga sempre rilasciata
                        }
                    }
                } 
                finally{
                    spogliatoi.release(); // Assicurarsi che la chiave dello spogliatoio venga sempre rilasciata
                }
            }
            Thread.sleep(100); 
        }
    }
}