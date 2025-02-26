public class RWexclusive extends RWbasic {
    // in this version we have mutual exclusion on data access.
    // this means only one reader OR only one writer can access data at a time.
    // this version always gives the correct result

    @Override
    public int read() {
        try {
            Thread.sleep((long) (Math.random() * 1500));
        } catch (InterruptedException e) {
            e.printStackTrace();
        }
        synchronized (this) {
            return super.read();
        }
    }

    @Override
    public void write() {
        try {
            Thread.sleep((long) (Math.random() * 1000));
        } catch (InterruptedException e) {
            e.printStackTrace();
        }
        synchronized (this) {
            super.write();
            System.out.println("WRITE");
        }
    }
}
