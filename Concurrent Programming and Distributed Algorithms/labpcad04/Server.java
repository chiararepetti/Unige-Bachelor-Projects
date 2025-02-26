import java.io.*;
import java.net.*;
import java.util.LinkedList;
import java.util.Queue;

public class Server {
    // Chosen port number
    static final private int PORT = 4242;
    public static Queue<String> products = new LinkedList<>();
    // Second Version with a maximum size 
    private final static int MAX_SIZE = 5;

    public static void main(String[] args) {
        ServerSocket server = null;
        try {
            server = new ServerSocket(PORT);
            System.out.println("SERVER IS LISTENING ON PORT: " + PORT);
            while (true) {
                Socket socket = server.accept();
                Service serv = new Service(socket);
                Thread thread = new Thread(serv);
                thread.start();
            }
        } catch (Exception e) {
            System.out.println(e);
            e.printStackTrace();
        } finally {
            if (server != null) {
                try {
                    server.close();
                } catch (IOException e) {
                    System.out.println(e);
                    e.printStackTrace();
                }
            }
        }
    }
  
    public static class Service implements Runnable {
        public Socket socket;

        public Service(Socket s) {
            this.socket = s;
        }

        public void produce(PrintWriter out, BufferedReader in) throws IOException {
            synchronized (products) {
                // If there are already 5 items in the queue, wait until some are consumed
                while (products.size() == MAX_SIZE) {
                    try {
                        System.out.println("---a producer is waiting because queue is full...");
                        products.wait();
                    } catch (InterruptedException e) {
                        Thread.currentThread().interrupt();
                        throw new RuntimeException(e);
                    }
                }

                out.println("okprod\n");
                out.flush();

                String string = in.readLine();
                string = in.readLine();

                products.add(string);

                System.out.println("---a producer sent: " + string + "; queue size: " + products.size());

                // Notify any waiting consumers that an item has been produced
                products.notifyAll();
            }
        }

        public void consume(PrintWriter out) {
            synchronized (products) {
                // If there are no items in the queue, wait until some are produced
                while (products.isEmpty()) {
                    try {
                        System.out.println("---a consumer is waiting for something to consume...");
                        products.wait();
                    } catch (InterruptedException e) {
                        Thread.currentThread().interrupt();
                        throw new RuntimeException(e);
                    }
                }

                String string = products.remove();

                out.println("okcons\n");
                out.flush();
                out.println(string + "\n");
                out.flush();

                System.out.println("---a consumer received: " + string + "; queue size: " + products.size());

                // Notify any waiting producers that an item has been consumed
                products.notifyAll();
            }
        }

        @Override
        public void run() {
            try {
                BufferedReader input = new BufferedReader(new InputStreamReader(socket.getInputStream()));
                PrintWriter output = new PrintWriter(new OutputStreamWriter(socket.getOutputStream()));

                String message = input.readLine();
                System.out.println("SERVER RECEIVED MESSAGE: " + message);
                if (message.equals("producer")) {
                    produce(output, input);
                }
                if (message.equals("consumer")) {
                    consume(output);
                }
                output.flush();
                output.close();
                input.close();
                socket.close();
            } catch (IOException e) {
                throw new RuntimeException(e);
            }
        }

    }
}