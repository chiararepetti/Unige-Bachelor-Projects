public class RWbasic {
    // thiw version doesn't always work because we have no mutual exclusion.
    // some runs of this version might give the correct result but most of them produce the wrong result.
    private int data;

    public RWbasic() {
        data = 0;
    }

    public int read() {
        try {
            Thread.sleep((long)(Math.random() * 500));
        } catch (InterruptedException e) {
            e.printStackTrace();
        }
        return data;
    }

    public void write() {
        try {
            Thread.sleep((long)(Math.random() * 500));
        } catch (InterruptedException e) {
            e.printStackTrace();
        }
        var tmp = data;
        tmp++;
        data = tmp;
    }
}
