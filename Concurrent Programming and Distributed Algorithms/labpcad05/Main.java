public class Main {
    public static void main(String[] args) {
        //Pool pool = new Pool();
        Pool_wd pool = new Pool_wd();
        int numeroClienti = 20;

        for (int i = 1; i < numeroClienti+1; i++) {
            new Thread(new Client(i, pool)).start();
        }
    }
}