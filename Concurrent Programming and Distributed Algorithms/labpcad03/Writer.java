public class Writer implements Runnable {
    private RWext rw;

    public Writer(RWext rwx) {
        rw = rwx;
    }

    @Override
    public void run() {
        rw.write();
    }
}
