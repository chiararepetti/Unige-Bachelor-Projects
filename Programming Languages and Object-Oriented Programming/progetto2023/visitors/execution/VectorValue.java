package progetto2023.visitors.execution;

import java.util.Arrays;
import static java.util.Objects.requireNonNull;


public class VectorValue implements Value {
    private final int[] array;

    // Costructor
    public VectorValue(int idx, int dim) {
        if (dim < 0) {
            throw new NegativeArraySizeException("Negative Dimension");
        }
        if (idx < 0 || idx >= dim) {
            throw new ArrayIndexOutOfBoundsException("Indix outf of range");
        }

        array = new int[dim];
        array[idx] = 1;
    }

    // Costruttore per inizializzare il vettore con un array di interi
    public VectorValue(int[] array) {
        this.array = requireNonNull(array);
    }

    @Override
    public int[] toVect() {
        return array;
    }

    @Override
    public String toString() {
        StringBuilder str = new StringBuilder("[");
        int dim = array.length;
        for (int i = 0; i < dim - 1; ++i) {
            str.append(array[i]).append(";");
        }
        if (dim > 0) {
            str.append(array[dim - 1]);
        }
        str.append("]");
        return str.toString();
    }

    @Override
    public int hashCode() {
        return Arrays.hashCode(array);
    }

    @Override
    public boolean equals(Object obj) {
        if (this == obj) {
            return true;
        }
        if (obj instanceof VectorValue vecv) {
            return Arrays.equals(array, vecv.array);
        }
        return false;
    }

    
}

