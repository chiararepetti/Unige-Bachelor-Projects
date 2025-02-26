public class RW extends RWbasic {
    // this time we use wait(), notify() and notifyAll() to implement a version of RW where
    // there is mutual exclusion in writing, while multiple readers can read at the same time and
    // if some readers are reading, writing stops.
    private int readers_counter = 0;
    private int writers_counter = 0;

    @Override
    public int read() {
        try {
            Thread.sleep((long) (Math.random() * 15000));
        } catch (InterruptedException e) {
            e.printStackTrace();
        }
        read_start();
        int value = super.read();
        read_end();
        return value;
    }

    @Override
    public void write() {
        write_start();
        super.write();
        System.out.println("WRITE"); // to have a better idea of execution.
        write_end();
    }

    private synchronized void read_start() {
        readers_counter++;
    }

    private synchronized void read_end() {
        readers_counter--;
        notify();
    }


    private synchronized void write_start() {
        while (readers_counter > 0 || writers_counter > 0){
            try {
                wait();
            } catch (InterruptedException e) {
                e.printStackTrace();
            }
        }
        writers_counter++;
    }

    private synchronized void write_end() {
        writers_counter--;
        notifyAll();
    }
}