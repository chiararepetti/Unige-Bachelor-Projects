public class Main {
    public static void main(String[] args) {
        RWext rw = new RWext();

        final int reader_number = 50;
        final int writer_number = 50;

        Thread[] reader_threads = new Thread[reader_number];
        Thread[] writer_threads = new Thread[writer_number];

        // initializing and running readers and writers
        for (int i = 0; i < reader_number; i++) {
            reader_threads[i] = new Thread(new Reader(rw));
            reader_threads[i].start();
        }
        for (int i = 0; i < writer_number; i++) {
            writer_threads[i] = new Thread(new Writer(rw));
            writer_threads[i].start();
        }

        // wait for their execution to finish
        for (Thread thread : reader_threads) {
            try {
                thread.join();
            } catch (InterruptedException e) {
                e.printStackTrace();
            }
        }
        for (Thread thread : writer_threads) {
            try {
                thread.join();
            } catch (InterruptedException e) {
                e.printStackTrace();
            }
        }

        // print result
        System.out.println("DATA VALUE: " + rw.read());
    }
}