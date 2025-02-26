public class RWext extends RWbasic {
    // this time we guarantee that everytime a value is written,
    // at least one reader reads it.
    // * this version only works if we have the same number of readers and writers!

    private int readers_counter = 0;
    private int writers_counter = 0;
    private boolean written = false;
    // counter to check if one group of threads finish faster than reads, which happens in most cases.
    private int last_thread_check = 0;

    @Override
    public int read() {
        try {
            Thread.sleep((long) (Math.random() * 35000));
        } catch (InterruptedException e) {
            e.printStackTrace();
        }
        read_start();
        int value = super.read();
        read_end();
        return value;
    }

    @Override
    public synchronized void write() {
        write_start();
        super.write();
        System.out.println("WRITE");
        write_end();
    }

    private synchronized void write_start() {
        last_thread_check--;
        while (written || readers_counter > 0 || writers_counter > 0) {
            try {
                if (last_thread_check == 0) {
                    break;
                }
                wait();
            } catch (InterruptedException e) {
                e.printStackTrace();
            }
        }
        writers_counter++;
    }

    private synchronized void write_end() {
        written = true;
        writers_counter--;
        notifyAll();
    }

    private synchronized void read_start() {
        readers_counter++;
    }

    private synchronized void read_end() {
        readers_counter--;
        last_thread_check++;
        if (readers_counter == 0) {
            written = false;
            notifyAll();
        }
    }
}
