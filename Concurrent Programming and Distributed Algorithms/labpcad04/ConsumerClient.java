import java.io.*;
import java.net.*;

public class ConsumerClient {
    private static final String SERVER_IP = "127.0.0.1";
    private static final int PORT = 4242;
    private static final int RUNS = 25;

    public static void main(String[] args) throws InterruptedException {
        int counter = 0;
        while (counter < RUNS) {
            Thread.sleep((long)(Math.random() * 10000));
            try {
                Socket socket = new Socket(SERVER_IP, PORT);
                BufferedReader input = new BufferedReader(new InputStreamReader(socket.getInputStream()));
                PrintWriter output = new PrintWriter(new OutputStreamWriter(socket.getOutputStream()));

                output.println("consumer\n");
                output.flush();

                String response = input.readLine();
                if (response.equals("okcons")) {
                    String consumed = input.readLine();
                    consumed = input.readLine();
                    System.out.println("CONSUMED STRING: " + consumed);
                } else {
                    System.out.println("ERROR!");
                }
                socket.close();
            } catch (IOException e) {
                e.printStackTrace();
            } finally {
                counter++;
            }
        }
    }
}
