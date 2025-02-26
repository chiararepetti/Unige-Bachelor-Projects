public class Client implements Runnable {
    private int id;
    // private Pool pool;
    private Pool_wd pool;

    // public Client(int id, Pool pool) {
    public Client(int id, Pool_wd pool) {
        this.id = id;
        this.pool = pool;
    }

    @Override
    public void run() {
        try {
            pool.UsePool(id);
        } catch (InterruptedException e) {
            e.printStackTrace();
        }
    }
} 