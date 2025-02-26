public class Reader implements Runnable {
    private RWext rw;

    public Reader(RWext rwx) {
        rw = rwx;
    }

    @Override
    public void run() {
        System.out.println(rw.read());
    }
}
