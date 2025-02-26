import java.io.FileWriter;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;
import java.util.Random;

public class LVQuickSort {

    private static Random random = new Random(System.currentTimeMillis());

    private static void scambia(int[] v, int i, int j) {
        int temp = v[i];
        v[i] = v[j];
        v[j] = temp;
    }

    private static int[] partizionaInPlaceRandom(int[] v, int l, int r) {
        int p = l + random.nextInt(r - l + 1);  
        scambia(v, l, p);  
        int i = l + 1;
        int comp = r - l;  
        for (int j = l + 1; j <= r; j++) {
            if (v[j] < v[l]) {  
                scambia(v, j, i);
                i++;
            }
        }
        scambia(v, l, i - 1); 
        return new int[]{i - 1, comp};
    }

    private static int qs(int[] v, int l, int r) {
        int comp = 0;
        if (l < r) {
            int[] result = partizionaInPlaceRandom(v, l, r);
            int index = result[0];
            comp += result[1];
            comp += qs(v, l, index - 1);
            comp += qs(v, index + 1, r);
        }
        return comp;
    }

    private static int lvQuickSort(int[] v) {
        return qs(v, 0, v.length - 1);
    }

    private static List<Integer> runQuickSort(int[] sequence) {
        List<Integer> compList = new ArrayList<>();
        for (int i = 0; i < 100000; i++) {
            int[] clonedSequence = sequence.clone();
            compList.add(lvQuickSort(clonedSequence));
        }
        return compList;
    }

    private static void saveCompListToFile(List<Integer> compList, String filename) {
        try (FileWriter writer = new FileWriter(filename)) {
            for (int comp : compList) {
                writer.write(comp + "\n");
            }
        } catch (IOException e) {
            e.printStackTrace();
        }
    }
    public static void main(String[] args) {
        int S = 10000;
        int[] sequence = new int[S];
        for (int i = 0; i < S; i++) {
            sequence[i] = random.nextInt(S) + 1;
        }
        List<Integer> compList = runQuickSort(sequence);
        saveCompListToFile(compList, "comp_list.txt");    
    }
}

